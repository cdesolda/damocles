<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\UserHfThreat;
use App\Models\HumanFactor;
use App\Models\Threat;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;

class UserHfThreatSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();

        $threats = Threat::all();
        $humanFactors = HumanFactor::all();
        $userIds = User::where('type', 'Fake')->pluck('id');

        foreach ($userIds as $userId) {
            $numThreats = rand(1, 3);

            for ($i = 0; $i < $numThreats; $i++) {
                $threat = $threats->random();
                $humanFactor = $humanFactors->random();

                UserHfThreat::create([
                    'user_id' => $userId,
                    'hf_id' => $humanFactor->id,
                    'threat_id' => $threat->id,
                    'severityLevel' => round($faker->randomFloat(2, 1, $humanFactor->likert_scales), 2),
                ]);
            }
        }
    }
}
