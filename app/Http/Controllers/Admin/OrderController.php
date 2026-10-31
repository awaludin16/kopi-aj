<?php

namespace App\Http\Controllers\Admin;

use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::query()
            ->where('status', OrderStatus::SUBMITTED)
            ->whereHas('payment', function ($q) {
                $q->where('status', PaymentStatus::PENDING);
            })
            ->with(['table', 'payment',])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.orders.index', compact('orders'));
    }

    public function orderList()
    {
        // query untuk mendapatkan semua order yang memiliki status SUBMITTED dan PAID, beserta relasi table dan payment
        $orders = Order::query()
            ->whereIn('status', [OrderStatus::SUBMITTED, OrderStatus::PAID])
            ->with(['table', 'payment'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.orders.order-list', compact('orders'));
    }

    public function scan(): View
    {
        return view('admin.scan');
    }

    public function process(Request $request): View | RedirectResponse
    {
        $request->validate([
            'qr_token' => ['required', 'string'],
        ]);

        // Cari order berdasarkan qr_token — tanpa filter payment_method
        // agar semua metode pembayaran (cash, online, dll) bisa diproses
        $order = Order::with('payment', 'items.menu', 'table')
            ->whereHas('payment', function ($q) use ($request) {
                $q->where('qr_token', $request->qr_token);
            })
            ->first();

        if (!$order) {
            return back()->withErrors([
                'qr_token' => 'QR pembayaran tidak valid',
            ]);
        }

        if ($order->payment->status !== PaymentStatus::PENDING) {
            return back()->withErrors([
                'qr_token' => 'Pembayaran sudah diproses',
            ]);
        }

        return view('admin.orders.show', compact('order'));
    }

    public function show(Order $order)
    {
        $order->load(['payment', 'items.menu', 'table']);

        return view('admin.orders.show', compact('order'));
    }

    /**
     * Konfirmasi pembayaran untuk semua metode (cash, online, dll)
     */
    public function confirmPayment(Order $order)
    {
        $payment = $order->payment;

        if (!$payment) {
            abort(404, 'Payment tidak ditemukan.');
        }

        if ($payment->status !== PaymentStatus::PENDING) {
            return back()->with('info', 'Pembayaran sudah diproses.');
        }

        DB::transaction(function () use ($order, $payment) {

            $payment->update([
                'status' => PaymentStatus::PAID,
                'paid_at' => now(),
            ]);

            $order->update([
                'status' => OrderStatus::PAID,
            ]);
        });

        return redirect()
            ->route('admin.orders.show', $order)
            ->with('success', 'Pembayaran berhasil dikonfirmasi.');
    }

    /**
     * Halaman cetak struk pembayaran
     */
    public function receipt(Order $order): View
    {
        $order->load(['payment', 'items.menu', 'table']);

        return view('admin.orders.receipt', compact('order'));
    }
}
