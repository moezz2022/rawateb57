<?php

namespace App\Imports;

use App\Models\Group; 
use Maatwebsite\Excel\Concerns\ToModel;


class GroupsImport implements ToModel
{
    public function model(array $row)
    {
        if (empty($row[0]) || empty($row[1]) || empty($row[2]) ) {
            return null;
        }

        $parent = null;
        if (is_numeric($row[3])) {
            $parent = Group::find($row[3]);
            if ($parent) {
            } else {
            }
        } else {
            $parent = Group::where('name', $row[3])->first();
            if ($parent) {
            } else {
            }
        }

        return new Group([
            'AFFECT' => $row[0],  
            'name' => $row[1], 
            'type' => $row[2],     
            'parent_id' => $parent ? $parent->id : null,
        ]);
    }
}
