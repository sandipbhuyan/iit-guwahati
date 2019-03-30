<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UsersTableSeeder::class);
        DB::table('users')->insert([
            'id' => '1',
            'name' => 'ADMIN',
            'email' => env('ADMIN_USERNAME'),
            'password' => bcrypt(env('ADMIN_PASSWORD'))
        ]);
    }
}
