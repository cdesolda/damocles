<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ThreatsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('threats')->insert([
            ['name' => 'Phishing', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Spear phishing', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Smishing', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Ramsomware', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Password', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Misconfiguration', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
