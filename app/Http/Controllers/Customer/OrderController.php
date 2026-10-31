<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use App\Services\PriceCalculator;
use App\Services\XenditService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $order = $request->order_draft;

        if ($order->subtotal == 0) {
            return redirect()->route('customer.menu');
        }

        $priceCalculate = PriceCalculator::calculate($order->subtotal);

        return view('customer.order', ['order' => $order, ...$priceCalculate]);
    }

    public function show(Order $order, XenditService $xenditService)
    {
        $order->load(['table', 'items.menu', 'payment', 'user']);

        // Jika pesanan belum berstatus PAID, coba sinkronisasi status pembayaran
        if ($order->status !== OrderStatus::PAID) {
            $payment = $order->payment;

            // Jika pembayaran online via Xendit
            if ($payment && ($payment->provider === 'xendit' || $payment->reference_id || $payment->payment_method === 'pembayaran_online')) {
                $this->syncXenditPaymentStatus($order, $payment, $xenditService);
                $order->refresh();
                $order->load(['table', 'items.menu', 'payment', 'user']);
            }
        }

        // Jika setelah sinkronisasi masih belum PAID
        if ($order->status !== OrderStatus::PAID) {
            $payment = $order->payment;

            // Jika metode cash dan masih pending, arahkan ke layar QR cash
            if ($payment && $payment->payment_method === 'cash' && $payment->qr_token) {
                return redirect()->route('payment.cash', $payment->qr_token);
            }

            // Jika status pembayaran expired / failed
            if ($payment && in_array($payment->status, [PaymentStatus::FAILED, PaymentStatus::EXPIRED])) {
                return redirect()->route('payment.failed', $order->id);
            }

            // Jika pembayaran online masih pending di Xendit
            if ($payment && !empty($payment->payload['invoice_url'])) {
                return redirect()->away($payment->payload['invoice_url']);
            }

            return redirect()->route('customer.menu')->with('info', 'Pesanan Anda sedang diproses.');
        }

        return view('customer.order-summary', compact('order'));
    }

    /**
     * Sinkronisasi status invoice langsung dari API Xendit
     */
    protected function syncXenditPaymentStatus(Order $order, $payment, XenditService $xenditService): void
    {
        try {
            $invoiceData = null;

            // 1. Cek via reference_id (ID invoice Xendit)
            if ($payment->reference_id) {
                $response = $xenditService->getInvoice($payment->reference_id);
                if ($response->successful()) {
                    $invoiceData = $response->json();
                }
            }

            // 2. Jika belum ada atau gagal, cari via external_id
            if (!$invoiceData && $payment->external_id) {
                $response = $xenditService->getInvoiceByExternalId($payment->external_id);
                if ($response->successful()) {
                    $invoices = $response->json();
                    if (!empty($invoices) && is_array($invoices)) {
                        $invoiceData = $invoices[0];
                    }
                }
            }

            if (!$invoiceData) {
                return;
            }

            $xenditStatus = strtoupper($invoiceData['status'] ?? '');

            // Jika status di Xendit sudah dibayar
            if (in_array($xenditStatus, ['PAID', 'SETTLED'])) {
                DB::transaction(function () use ($order, $payment, $invoiceData) {
                    $paymentMethodName = $invoiceData['payment_method'] ?? 'Online';
                    if (!empty($invoiceData['payment_channel'])) {
                        $paymentMethodName = $invoiceData['payment_channel'];
                    }

                    $payment->update([
                        'status' => PaymentStatus::PAID,
                        'paid_at' => now(),
                        'payment_method' => $paymentMethodName,
                        'reference_id' => $invoiceData['id'] ?? $payment->reference_id,
                        'payload' => $invoiceData,
                    ]);

                    $order->update([
                        'status' => OrderStatus::PAID,
                    ]);
                });

                Log::info("Order #{$order->order_number} berhasil disinkronkan dan diupdate ke PAID via Xendit API.");
            } elseif ($xenditStatus === 'EXPIRED') {
                $payment->update([
                    'status' => PaymentStatus::EXPIRED,
                    'payload' => $invoiceData,
                ]);
            }
        } catch (\Throwable $e) {
            Log::error("Gagal sinkronisasi pembayaran Xendit untuk Order #{$order->id}: " . $e->getMessage());
        }
    }
}
