<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = [
            ['name' => 'Shykot' , 'email' => 'shykot@gmail.com', 'password' => '12345678'],
            ['name' => 'hasan' , 'email' => 'hasan@gmail.com', 'password' => '12345678'],
            ['name' => 'shourav' , 'email' => 'shourav@gmail.com', 'password' => '12345678'],
        ];
        User::insert($users);

    }
}
