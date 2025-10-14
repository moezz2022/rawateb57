<?php

namespace Database\Seeders;

use App\Models\Group;
use Illuminate\Database\Seeder;

class GroupSeeder extends Seeder
{
   
    public function run(): void
    {
        $parentGroup = Group::create([
            'AFFECT' => '57000',
            'name' => 'مديرية التربية',
            'type' => 'admin',
            'parent_id' => null, 
        ]);
        Group::create([
            'AFFECT' => '57001',
            'name' => 'مكتب الرقمنة',
            'type' => 'admin',
            'parent_id' => $parentGroup->id,
        ]);
    }
}
