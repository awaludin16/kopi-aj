<?php

namespace App\Http\Controllers\Admin;

use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    /**
     * Endpoint polling notifikasi pesanan realtime untuk admin
     */
    public function check(Request $request): JsonResponse
    {
        $lastOrderId = (int) $request->query('last_order_id', 0);

        // Pesanan yang sedang menunggu konfirmasi (status SUBMITTED & payment PENDING)
        $pendingQuery = Order::query()
            ->where('status', OrderStatus::SUBMITTED)
            ->whereHas('payment', function ($q) {
                $q->where('status', PaymentStatus::PENDING);
            })
            ->with(['table', 'payment']);

        $pendingCount = $pendingQuery->count();

        // Cari pesanan baru yang ID-nya lebih besar dari ID yang tersimpan di frontend
        $newOrder = null;
        if ($lastOrderId > 0) {
            $newOrder = (clone $pendingQuery)
                ->where('id', '>', $lastOrderId)
                ->orderBy('id', 'desc')
                ->first();
        } else {
            // Saat pertama kali load, ambil order ID tertinggi sebagai patokan
            $highestOrder = (clone $pendingQuery)->orderBy('id', 'desc')->first();
            $lastOrderId = $highestOrder ? $highestOrder->id : 0;
        }

        // Ambil 5 pesanan pending terkini untuk preview dropdown notifikasi
        $pendingOrders = (clone $pendingQuery)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get()
            ->map(function ($order) {
                return [
                    'id' => $order->id,
                    'order_number' => $order->order_number,
                    'table_number' => $order->table->table_number ?? '-',
                    'total' => $order->total,
                    'formatted_total' => 'Rp ' . number_format($order->total, 0, ',', '.'),
                    'payment_method' => $order->payment?->payment_method ?? 'cash',
                    'time_ago' => $order->created_at->diffForHumans(),
                    'created_at_time' => $order->created_at->format('H:i') . ' WIB',
                    'detail_url' => route('admin.orders.show', $order),
                ];
            });

        $latestOrderData = null;
        if ($newOrder) {
            $latestOrderData = [
                'id' => $newOrder->id,
                'order_number' => $newOrder->order_number,
                'table_number' => $newOrder->table->table_number ?? '-',
                'total' => $newOrder->total,
                'formatted_total' => 'Rp ' . number_format($newOrder->total, 0, ',', '.'),
                'payment_method' => $newOrder->payment?->payment_method ?? 'cash',
                'created_at_time' => $newOrder->created_at->format('H:i') . ' WIB',
                'detail_url' => route('admin.orders.show', $newOrder),
            ];
        }

        return response()->json([
            'pending_count' => $pendingCount,
            'has_new' => $newOrder !== null,
            'latest_order' => $latestOrderData,
            'current_max_id' => $pendingOrders->first()['id'] ?? $lastOrderId,
            'pending_orders' => $pendingOrders,
        ]);
    }
}

