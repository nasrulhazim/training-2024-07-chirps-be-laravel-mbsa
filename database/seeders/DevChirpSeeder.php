<?php

namespace Database\Seeders;

use App\Services\Chirps;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DevChirpSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if(app()->isProduction()) {
            return;
        }
        $apiClient = new Chirps();

        for ($i=0; $i < rand(5, 10); $i++) {
            $registerResponse = $apiClient->register(fake()->name, fake()->unique()->safeEmail(), 'password', 'password');
            print_r($registerResponse);
        }
    }
}
