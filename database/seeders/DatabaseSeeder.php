<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        // Categories
        \App\Models\Category::create([
            'name' => 'Seguridad',
        ]);
        \App\Models\Category::create([
            'name' => 'Sensores',
        ]);
        \App\Models\Category::create([
            'name' => 'Otros',
        ]);

        // Currencies
        \App\Models\Currency::create([
            'name' => 'Soles',
            'short_name' => 'PEN',
            'symbol' => 'S/',
        ]);
        \App\Models\Currency::create([
            'name' => 'Dólares americanos',
            'short_name' => 'USD',
            'symbol' => '$',
        ]);

        // Units
        \App\Models\Unit::create([
            'name' => 'UNIDAD (BIENES)',
            'code' => 'NIU',
        ]);
        \App\Models\Unit::create([
            'name' => 'UNIDAD (SERVICIOS)',
            'code' => 'ZZ',
        ]);
        \App\Models\Unit::create([
            'name' => 'METRO',
            'code' => 'MTR',
        ]);

        // Taxes
        \App\Models\Tax::create([
            'code' => '10',
            'name' => 'IGV',
            'percentage' => 0.18,
            'type' => '1000',
        ]);
        \App\Models\Tax::create([
            'code' => '10',
            'name' => 'IGV',
            'percentage' => 0.10,
            'type' => '1000',
        ]);
        \App\Models\Tax::create([
            'code' => '20',
            'name' => 'Exonerado',
            'percentage' => 0.00,
            'type' => '9996',
        ]);
    }
}
