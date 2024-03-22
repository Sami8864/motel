<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('roles')->insert([
            ['name' => 'admin','guard_name'=>'web'],['name' => 'maintenance','guard_name'=>'web'],['name' => 'user','guard_name'=>'web'],['name' => 'receptionist','guard_name'=>'web'],['name' => 'housekeeping','guard_name'=>'web']
        ]);
    }
}
