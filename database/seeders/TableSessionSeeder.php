<?php

namespace Database\Seeders;

use App\Models\Meja;
use App\Models\TableSession;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class TableSessionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Meja::all()->each(function ($table) {
            TableSession::create([
                'table_id' => $table->id,
                'token' => Str::uuid(),
                'is_active' => true,
            ]);
        });
    }
}
