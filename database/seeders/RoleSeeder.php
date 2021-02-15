<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roles = ['admin ', 'common'];
        foreach($roles as $role) {
            DB::table('roles')->insert([
                'name' => $role,
                'description' => $role,
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }
        User::create([
           'name' => 'Administrador',
           'email' => 'admin@test.com',
           'password' =>  Hash::make('secret'),
           'role_id' => 1
        ]);
    }
}
