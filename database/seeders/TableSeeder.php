<?php

namespace Database\Seeders;

use App\Models\Meja;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class TableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for ($i = 1; $i <= 10; $i++) {
            Meja::create([
                'table_number' => $i,
                'qr_token' => Str::random(20),
                'is_active' => true,
            ]);
        }
    }
}
