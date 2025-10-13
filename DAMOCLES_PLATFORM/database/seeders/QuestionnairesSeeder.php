<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class QuestionnairesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('questionnaires')->insert([
            [
                'name' => 'Susceptibility to Persuasion II',
                'likert_scales' => 7,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Big Five Inventory',
                'likert_scales' => 5,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Trait Emotional Intelligence',
                'likert_scales' => 7,
                'created_at' => now(),
                'updated_at' => now()
            ],
        ]);
    }
}
