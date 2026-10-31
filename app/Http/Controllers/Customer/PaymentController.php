<?php

namespace App\Http\Controllers\Customer;

use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\StorePaymentRequest;
use App\Models\Meja;
use App\Models\Order;
use App\Models\Payment;
use App\Models\User;
use App\Services\PriceCalculator;
use App\Services\XenditService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PaymentController extends Controller
{
    public function index(Request $request)
    {
        $order = $request->order_draft;

        $tableNumber = Meja::where('id', $order->table_id)->first()->table_number;

        $priceCalculate = PriceCalculator::calculate($order->subtotal);

        return view('customer.payment', [
            'tableNumber' => $tableNumber,
            ...$priceCalculate
        ]);
    }

    public function store(StorePaymentRequest $request)
    {
        $validated = $request->validated();

        $table = Meja::where('table_number', $validated['nomor_meja'])->firstOrFail();
        $order = $request->order_draft;

        $priceCalculate = PriceCalculator::calculate($order->subtotal);

        // CUSTOMER
        /**
         * USER / CUSTOMER * Kalau nanti sudah pakai magic link:
         * Auth::guard('customer')->user()
         */
        $user = User::query()
            ->where('email', $validated['email'] ?? '')
            ->orWhere('phone_number', $validated['no_hp'] ?? '')
            ->orWhere('name', $validated['nama_lengkap'] ?? '')
            ->first();

        if (!$user) {
            $user = User::create([
                'name' => $validated['nama_lengkap'],
                'phone_number' => $validated['no_hp'] ?? null,
                'email' => $validated['email'] ?? null
            ]);
        }

        // UPDATE ORDER
        $order->update([
            'order_number'     => $order->generateOrderCode($order->id, $table->table_number),
            'table_id'         => $table->id,
            'table_session_id' => session('table_session_id'),
            'user_id'          => $user->id,
            'subtotal'         => $order->subtotal,
            'pb1'              => $priceCalculate['pb1'],
            'service_charge'   => $order->service_charge,
            'rounding'         => $priceCalculate['rounding'],
            'total'            => $priceCalculate['total'],
            'status'           => OrderStatus::SUBMITTED,
            'note'             => $order->note,
            'payment_method'   => $validated['payment_method'],
        ]);

        // CREATE PAYMENT
        $payment = Payment::create([
            'order_id'       => $order->id,
            'payment_method' => $validated['payment_method'],
            'amount'         => $order->total,
            'status'         => PaymentStatus::PENDING,
            'qr_token'       => $validated['payment_method'] === 'cash'
                ? Str::uuid()
                : null,
        ]);

        // === BRANCH ===
        if ($payment->payment_method === 'pembayaran_online') {
            return $this->payWithXendit($order);
        }

        return redirect()->route('payment.cash', $payment->qr_token);
    }

    public function cash(string $qrToken)
    {
        $payment = Payment::with('order.items.menu')
            ->where('qr_token', $qrToken)
            ->where('payment_method', 'cash')
            ->where('status', PaymentStatus::PENDING)
            ->firstOrFail();

        return view('customer.payment-cash', compact('payment'));
    }

    public function status(string $token)
    {
        $payment = Payment::where('qr_token', $token)
            ->where('payment_method', 'cash')
            ->firstOrFail();

        return response()->json([
            'status' => $payment->status->value,
            'paid' => $payment->status === PaymentStatus::PAID,
        ]);
    }

    protected function payWithXendit(Order $order)
    {
        $payment = $order->payment;

        if (!$payment) {
            abort(500, 'Payment record tidak ditemukan');
        }

        // Update payment info (bukan create)
        $payment->update([
            'payment_method' => 'pembayaran_online',
            'provider'       => 'xendit',
            'external_id'    => $payment->external_id
                ?? 'order-' . $order->id . '-' . Str::uuid(),
            'amount'         => $order->total,
            'status'         => PaymentStatus::PENDING,
        ]);

        $amount = (int) ceil($payment->amount);

        if ($amount <= 0) {
            abort(400, 'Total pembayaran tidak valid');
        }

        // Payload Xendit
        $payload = [
            'external_id' => $payment->external_id,
            'amount'      => $amount,
            'currency'    => 'IDR',
            'description' => 'Pembayaran Order #' . $order->order_number,
            'invoice_duration' => 900,
            'success_redirect_url' => route('order.summary', $order->id),
            'failure_redirect_url' => route('payment.failed', $order->id),
        ];

        if ($order->user?->email) {
            $payload['payer_email'] = $order->user->email;
        }

        $xendit = app(XenditService::class)->createInvoice($payload);

        if ($xendit->status() !== 200) {
            $payment->update([
                'status'  => PaymentStatus::FAILED,
                'payload' => $xendit->body(),
            ]);

            abort(500, 'Gagal membuat invoice pembayaran');
        }

        $response = $xendit->object();

        // dd($response);

        $payment->update([
            'reference_id' => $response->id,
            'payload'      => $response,
        ]);

        return redirect()->away($response->invoice_url);
    }
}
