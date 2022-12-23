<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $admine = new \App\Models\User();
        $admine->fullName = 'Admine App';
        $admine->role = 'Admine';
        $admine->verified_at = date('D M j');
        $admine->email = 'admine@gmail.com';
        $admine->password = bcrypt('077257675');
        $admine->save();

        $user = new \App\Models\User();
        $user->fullName = 'user bot';
        $user->role = 'user';
        $user->verified_at = date('D M j');
        $user->email = 'ulvyromy156@gmail.com';
        $user->password = bcrypt('077257676');
        $user->save();
    }
}
