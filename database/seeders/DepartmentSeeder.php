<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DepartmentSeeder extends Seeder
{
    public function run()
    {
        DB::table('departments')->insert([
            [
                'ADM' => '1',
                'name' => ' أساتذة التعليم الابتدائي',
            ],
            [
                'ADM' => '3',
                'name' => 'أساتذة التعليم المتوسط',
            ],
            [
                'ADM' => '5',
                'name' => 'أساتذة التعليم الثانوي',
            ],
            [
                'ADM' => '2',
                'name' => 'إداريو التعليم الابتدائي',
            ],
            [
                'ADM' => '4',
                'name' => 'إداريو التعليم المتوسط والثانوي',
            ],
            [
                'ADM' => '6',
                'name' => 'إداريو التعليم الثانوي',
            ],
            [
                'ADM' => '7',
                'name' => 'موظفو مديرية التربية',
            ],
            [
                'ADM' => 'َA',
                'name' => 'عمال مهنين متعاقدين',
            ],
               [
                'ADM' => 'َE',
                'name' => 'عمال مهنين متعاقدين-ابتدائي',
            ],
               [
                'ADM' => 'َT',
                'name' => 'عمال مهنين متعاقدين- توقيت جزئي',
            ],
         
        ]);
    }
}
