<?php

namespace Database\Seeders;

use App\Models\Interest;
use Illuminate\Database\Seeder;

class InterestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $interests = ['Deportes', 'Tecnológia', 'Cine', 'Ciencias', 'Politica', 'Animales'];
        foreach($interests as $interest) {
            Interest::create([
                'name' => $interest,
                'description' => $interest
            ]);
        }
    }
}
