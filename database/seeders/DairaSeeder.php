<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Daira;

class DairaSeeder extends Seeder
{
    public function run()
    {
        Daira::create([
            'code_daira' => '5727',
            'name' => 'المغير',
        ]);

        Daira::create([
            'code_daira' => '5728',
            'name' => 'جامعة',
        ]);
    }
}
