<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Url;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        Url::create(['url' => 'https://solartop.staging-server.at']);
        Url::create(['url' => 'https://www.tlsoft.at/']);
        Url::create(['url' => 'https://remax.staging-server.at/']);
        Url::create(['url' => 'https://www.heubach.com/']);
    }
}
