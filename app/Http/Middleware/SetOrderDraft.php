<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Order;
use Illuminate\Support\Str;

class SetOrderDraft
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle($request, Closure $next)
    {
        $tableSessionId = session('table_session_id');

        if (!$tableSessionId) {
            abort(403, 'Table session not found');
        }

        $order = Order::where('table_session_id', $tableSessionId)
            ->where('status', 'draft')
            ->first();

        if (!$order) {
            $order = Order::create([
                'order_number' => 'draft-' . Str::uuid(),
                'table_id' => session('table_id'),
                'table_session_id' => $tableSessionId,
                'status' => 'draft',
                'subtotal' => 0,
            ]);
        }

        // inject ke request biar controller rapi
        $request->merge([
            'order_draft' => $order
        ]);

        return $next($request);
    }
}
