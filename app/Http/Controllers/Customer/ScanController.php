<?php

namespace App\Http\Controllers\Customer;

use Illuminate\Http\Request;
use App\Models\TableSession;
use Illuminate\Support\Str;
use App\Http\Controllers\Controller;
use App\Models\Meja;

class ScanController extends Controller
{
    public function handle(Request $request, string $tableNumber)
    {
        $table = Meja::where('table_number', $tableNumber)->first();

        if (!$table) {
            abort(404, 'Meja tidak ditemukan');
        }

        // 1. Matikan SEMUA session aktif untuk meja ini
        TableSession::where('table_id', $table->id)
            ->where('is_active', true)
            ->update([
                'is_active' => false,
                'ended_at'  => now(),
            ]);

        // 2. Buat session baru (SATU-SATUNYA)
        $newSession = TableSession::create([
            'table_id'   => $table->id,
            'token'      => Str::uuid(),
            'is_active'  => true,
            'started_at' => now(),
        ]);

        // 3. Reset browser session (hapus cart lama)
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // 4. Simpan konteks meja & session
        session([
            'table_id'         => $table->id,
            'table_session_id' => $newSession->id,
        ]);

        // 5. Redirect ke menu
        return redirect()->route('customer.menu');
    }
}
