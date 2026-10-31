<?php

namespace App\Http\Middleware;

use App\Models\TableSession;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureTableSessionActive
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $tableSessionId = session('table_session_id');

        if (!$tableSessionId) {
            abort(403, 'Session meja tidak ditemukan, scan ulang QR code meja.');
        }

        $session = TableSession::where('id', $tableSessionId)
            ->where('is_active', true)
            ->first();

        if (!$session) {
            abort(403, 'Session meja sudah berakhir');
        }

        return $next($request);
    }
}
