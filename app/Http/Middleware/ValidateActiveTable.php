<?php

namespace App\Http\Middleware;

use App\Models\Meja;
use Closure;
use Illuminate\Http\Request;

class ValidateActiveTable
{
    public function handle(Request $request, Closure $next)
    {
        // Ambil token dari route
        $token = $request->route('token');

        if (!$token) {
            abort(403, 'Token meja tidak ditemukan');
        }

        // Cari meja berdasarkan token
        $table = Meja::where('qr_token', $token)
            ->where('is_active', true)
            ->first();

        if (!$table) {
            abort(403, 'Meja tidak aktif atau QR tidak valid');
        }

        // Simpan nomor meja ke session
        session([
            'table_id'   => $table->id,
            'table_number' => $table->table_number
        ]);

        return $next($request);
    }
}
