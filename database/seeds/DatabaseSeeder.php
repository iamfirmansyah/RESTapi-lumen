<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'id'        => Str::random(36),
            'role'      => "cashier",
            'name'      => "kasir user",
            'email'     => "kasir@gmail.com",
            'password'  => Hash::make("adminadmin"),
        ]);
        DB::table('users')->insert([
            'id'        => Str::random(36),
            'role'      => "supervisor",
            'name'      => "supervisor user",
            'email'     => "supervisor@gmail.com",
            'password'  => Hash::make("adminadmin"),
        ]);
        DB::table('users')->insert([
            'id'        => Str::random(36),
            'role'      => "staff",
            'name'      => "staff user",
            'email'     => "staff@gmail.com",
            'password'  => Hash::make("adminadmin"),
        ]);
    }
}
