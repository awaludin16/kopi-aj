<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Menu;
use App\Services\PriceCalculator;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{
    /**
     * Tambah item ke cart (session)
     */
    public function add(Request $request)
    {
        $order = $request->order_draft;
        $menu = Menu::findOrFail($request->menu_id);

        $item = $order->items()
            ->where('menu_id', $menu->id)
            ->first();

        if ($item) {
            $item->increment('qty');
        } else {
            $item = $order->items()->create([
                'menu_id' => $menu->id,
                'qty' => 1,
                'price' => $menu->price,
            ]);
        }

        return response()->json([
            'menu_id' => $menu->id,
            'qty' => $item->qty,
            ...$this->cartSummary($order),
        ]);
    }

    public function update(Request $request)
    {
        $order = $request->order_draft;

        $item = $order->items()
            ->where('menu_id', $request->menu_id)
            ->first();

        if (!$item) {
            return response()->json([
                'qty' => 0,
                ...$this->cartSummary($order),
            ]);
        }

        $subtotalItem = $item->price * $item->qty ?? 0;

        if ($request->action === 'increase') {
            $item->increment('qty');
            $subtotalItem += $item->price;
        } elseif ($request->action === 'decrease') {
            $item->decrement('qty');
            $subtotalItem -= $item->price;
            if ($item->qty <= 0) {
                $item->delete();
                return response()->json([
                    'qty' => 0,
                    'total_item' => $order->items->count(),
                    'subtotal_item' => $subtotalItem,
                    ...$this->cartSummary($order),
                    ...PriceCalculator::calculate($order->subtotal)
                ]);
            }
        } else {
            $item->delete();
            return response()->json([
                'qty' => 0,
                'total_item' => $order->items->count(),
                'subtotal_item' => $subtotalItem,
                ...$this->cartSummary($order),
                ...PriceCalculator::calculate($order->subtotal)
            ]);
        }

        return response()->json([
            'menu_id' => $item->menu_id,
            'qty' => $item->qty,
            'total_item' => $order->items->count(),
            'subtotal_item' => $subtotalItem,
            ...$this->cartSummary($order),
            ...PriceCalculator::calculate($order->subtotal)
        ]);
    }


    public function items(Request $request)
    {
        $order = $request->order_draft;

        return response()->json(
            $order->items->map(fn($item) => [
                'menu_id' => $item->menu_id,
                'qty' => $item->qty,
            ])
        );
    }

    /**
     * Hitung total qty & harga
     */
    public function summary(Request $request)
    {
        $order = $request->order_draft;

        if (!$order) {
            return response()->json([
                'total_qty' => 0,
                'subtotal' => 0
            ]);
        }

        $totalQty = $order->items()->sum('qty');

        $totalPrice = $order->items()
            ->selectRaw('SUM(qty * price) as total')
            ->value('total') ?? 0;

        return response()->json([
            'total_qty' => $totalQty,
            'subtotal' => $totalPrice
        ]);
    }

    private function cartSummary($order)
    {
        $totalQty = $order->items()->sum('qty');

        $totalPrice = $order->items()
            ->selectRaw('SUM(qty * price) as total')
            ->value('total') ?? 0;

        $order->update(['subtotal' => $totalPrice]);

        return [
            'total_qty' => $totalQty,
            'subtotal' => $totalPrice,
        ];
    }

    private function priceCalculate(int $subTotal): array
    {
        $step = 500;

        $pb1 = (int) round($subTotal * 0.10);
        $service = (int) round($subTotal * 0.01);

        $gross = $subTotal + $pb1 + $service;
        $rounding = $gross % $step;
        $total = $gross - $rounding;

        return [
            // 'subTotal' => $subTotal,
            'pb1' => $pb1,
            'serviceCharge' => $service,
            'otherCosts' => $pb1 + $service,
            'rounding' => $rounding,
            'total' => $total,
        ];
    }
}
