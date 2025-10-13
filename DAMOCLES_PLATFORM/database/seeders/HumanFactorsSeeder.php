<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class HumanFactorsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('human_factors')->insert([

            // HAIS => 5
            // DAMOCLES => 5

            // StPIIB => 7
            ['name' => 'Lack of premeditation', 'likert_scales' => 7, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Need for consistency', 'likert_scales' => 7, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Sensation seeking', 'likert_scales' => 7, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Lack of self-control', 'likert_scales' => 7, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Social influence', 'likert_scales' => 7, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Need for avoidance of similarity', 'likert_scales' => 7, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Risk preferences', 'likert_scales' => 7, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Need for cognition', 'likert_scales' => 7, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Need for uniqueness', 'likert_scales' => 7, 'created_at' => now(), 'updated_at' => now()],

            // BFI2XS => 5 
            ['name' => 'Extroversion', 'likert_scales' => 5, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Agreeableness', 'likert_scales' => 5, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Conscientiousness', 'likert_scales' => 5, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Negative Emotionality', 'likert_scales' => 5, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Open Mindedness', 'likert_scales' => 5, 'created_at' => now(), 'updated_at' => now()],

            //TEIQueSF => 7
            ['name' => 'Total TEI', 'likert_scales' => 7, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Well being', 'likert_scales' => 7, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Self Controll', 'likert_scales' => 7, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Emotionality', 'likert_scales' => 7, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Sociability', 'likert_scales' => 7, 'created_at' => now(), 'updated_at' => now()],

            // ['name' => 'Educational Level', 'likert_scales' => 0, 'created_at' => now(), 'updated_at' => now()],
            // ['name' => 'Neuroticism', 'likert_scales' => 0, 'created_at' => now(), 'updated_at' => now()],
            // ['name' => 'Vigilance', 'likert_scales' => 0, 'created_at' => now(), 'updated_at' => now()],
            // ['name' => 'Misperception', 'likert_scales' => 0, 'created_at' => now(), 'updated_at' => now()],
            // ['name' => 'Uncertainty', 'likert_scales' => 0, 'created_at' => now(), 'updated_at' => now()],
            // ['name' => 'Distraction', 'likert_scales' => 0, 'created_at' => now(), 'updated_at' => now()],
            // ['name' => 'Cognitive fatigue', 'likert_scales' => 0, 'created_at' => now(), 'updated_at' => now()],
            // ['name' => 'Lack of Awareness', 'likert_scales' => 0, 'created_at' => now(), 'updated_at' => now()],
            // ['name' => 'Lack of communication', 'likert_scales' => 0, 'created_at' => now(), 'updated_at' => now()],
            // ['name' => 'Lack of Knowledge', 'likert_scales' => 0, 'created_at' => now(), 'updated_at' => now()],
            // ['name' => 'Lack of Resources', 'created_at' => now(), 'updated_at' => now()],
            // ['name' => 'Norms', 'likert_scales' => 0, 'created_at' => now(), 'updated_at' => now()],
            // ['name' => 'Risk attitude', 'likert_scales' => 0, 'created_at' => now(), 'updated_at' => now()],
            // ['name' => 'Overconfidence', 'likert_scales' => 0, 'created_at' => now(), 'updated_at' => now()],
            // ['name' => 'Risk-Taking', 'likert_scales' => 0, 'created_at' => now(), 'updated_at' => now()],
            // ['name' => 'Susceptibility', 'likert_scales' => 0, 'created_at' => now(), 'updated_at' => now()],
            // ['name' => 'Anxiousness', 'likert_scales' => 0, 'created_at' => now(), 'updated_at' => now()],
            // ['name' => 'Emotional stability', 'created_at' => now(), 'updated_at' => now()],
            // ['name' => 'Stress', 'likert_scales' => 0, 'created_at' => now(), 'updated_at' => now()],
            // ['name' => 'Frustration', 'likert_scales' => 0, 'created_at' => now(), 'updated_at' => now()],
            // ['name' => 'Impulsivity', 'likert_scales' => 0, 'created_at' => now(), 'updated_at' => now()],
            // ['name' => 'Fatigue', 'likert_scales' => 0, 'created_at' => now(), 'updated_at' => now()],
            // ['name' => 'Lack of trust', 'likert_scales' => 0, 'created_at' => now(), 'updated_at' => now()],
            // ['name' => 'Laziness', 'likert_scales' => 0, 'created_at' => now(), 'updated_at' => now()],
            // ['name' => 'Complacency', 'likert_scales' => 0, 'created_at' => now(), 'updated_at' => now()],
            // ['name' => 'Recurrence', 'likert_scales' => 0, 'created_at' => now(), 'updated_at' => now()],
            // ['name' => 'Bias', 'likert_scales' => 0, 'created_at' => now(), 'updated_at' => now()],
            // ['name' => 'Compulsive behavior', 'likert_scales' => 0, 'created_at' => now(), 'updated_at' => now()],
            // ['name' => 'Physical fatigue', 'likert_scales' => 0, 'created_at' => now(), 'updated_at' => now()],
            // ['name' => 'Internet addiction', 'likert_scales' => 0, 'created_at' => now(), 'updated_at' => now()],
            // ['name' => 'Social Proof', 'likert_scales' => 0, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
