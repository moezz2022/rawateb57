<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    
    public function run()
    {
        $this->call(GradeSeeder::class);
        $this->call(GroupSeeder::class);
        $this->call(UserSeeder::class);
        $this->call(SalaryElementSeeder::class);
        $this->call(DepartmentSeeder::class);
        $this->call(MatiereSeeder::class);
        $this->call(DairaSeeder::class);
        $this->call(CommuneSeeder::class);


    }
}
