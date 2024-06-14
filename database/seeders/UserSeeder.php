<?php

namespace Database\Seeders;

use App\Models\Module;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
                'name' => 'super-admin',
                'email' => 'web@tuf.edu.pk',
                'password'=>bcrypt('b3cIO4VRPB06'),
                'status'  => 1,
                'module_id'  => 1,
                'email_verified_at'  => Carbon::now()->toDateTimeString(),
            ]
        ];
        foreach ($users as $user){
            $user = User::create($user);
            $user->assignRole(Role::all());
        }
    }
}
