<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert(
            [
                [
                    'name' => 'Антон',
                    'email' => 'anton@gmail.com',
                    'password' => Hash::make('root'),
                    'created_at' => date("Y-m-d H:i:s"),
                    'updated_at' => date("Y-m-d H:i:s"),
                ],
                [
                    'name' => 'Дмитрий',
                    'email' => 'dima@gmail.com',
                    'password' => Hash::make('root'),
                    'created_at' => date("Y-m-d H:i:s"),
                    'updated_at' => date("Y-m-d H:i:s"),
                ],
                [
                    'name' => 'Ирина',
                    'email' => 'irina@gmail.com',
                    'password' => Hash::make('root'),
                    'created_at' => date("Y-m-d H:i:s"),
                    'updated_at' => date("Y-m-d H:i:s"),
                ],
            ]
        );
    }
}
