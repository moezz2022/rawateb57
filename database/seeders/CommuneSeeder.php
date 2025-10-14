<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Commune;

class CommuneSeeder extends Seeder
{
    public function run()
    {
        $communes = [
            ['code_commune' => '57271', 'name' => 'المغير', 'daira_id' => '1'],
            ['code_commune' => '57272', 'name' => 'سيدي خليل', 'daira_id' => '1'],
            ['code_commune' => '57273', 'name' => 'أم الطيور', 'daira_id' => '1'],
            ['code_commune' => '57274', 'name' => 'اسطيل', 'daira_id' => '1'],
            ['code_commune' => '57281', 'name' => 'جامعة', 'daira_id' => '2'],
            ['code_commune' => '57282', 'name' => 'تندلة', 'daira_id' => '2'],
            ['code_commune' => '57283', 'name' => 'المرارة', 'daira_id' => '2'],
            ['code_commune' => '57284', 'name' => 'سيدي عمران', 'daira_id' => '2'],
        ];

        Commune::insert($communes);
    }
}
