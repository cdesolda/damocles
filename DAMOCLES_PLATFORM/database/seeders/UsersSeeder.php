<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();
        $users = [];

        // Admin and Evaluator users
        $users[] = [
            'name' => 'Admin',
            'surname' => 'Admin',
            'gender' => 'Male',
            'dob' => '1970-01-01',
            'email' => 'admin@damocles.com',
            'password' => Hash::make('secretAdmin'),
            'role' => 'Admin',
            'company_role' => null,
            'type' => 'Real',
            'is_accept' => true,
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now()
        ];

        $users[] = [
            'name' => 'Evaluator',
            'surname' => 'Evaluator',
            'gender' => 'Male',
            'dob' => '1970-01-01',
            'email' => 'evaluator@damocles.com',
            'password' => Hash::make('secretEvaluator'),
            'role' => 'Evaluator',
            'company_role' => null,
            'type' => 'Real',
            'is_accept' => true,
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now()
        ];

        // Random users
        $genders = ['Male', 'Female', 'Other'];

        for ($i = 0; $i < 6; $i++) {
            $gender = $genders[array_rand($genders)];
            $dob = $faker->dateTimeBetween('-80 years', '-18 years')->format('Y-m-d');
            $users[] = [
                'name' => $faker->firstName,
                'surname' => $faker->lastName,
                'gender' => $gender,
                'dob' => $dob,
                'email' => 'user' . $i . '@damocles.com',
                'password' => Hash::make('secretUser'),
                'role' => 'User',
                'company_role' => 'Employee',
                'type' => 'Fake',
                'is_accept' => true,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now()
            ];
        }

        DB::table('users')->insert($users);
    }
}
