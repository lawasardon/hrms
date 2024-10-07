<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Create roles
        Role::create(['name' => 'admin']);
        Role::create(['name' => 'hr']);
        Role::create(['name' => 'employee']);

        $admin = new User();
        $admin->name = 'Admin';
        $admin->email = 'admin@gmail.com';
        $admin->email_verified_at = now();
        $admin->password = bcrypt('admin@gmail.com');
        $admin->save();
        $admin->assignRole('admin');

        $hr = new User();
        $hr->name = 'HR User';
        $hr->email = 'hr@gmail.com';
        $hr->email_verified_at = now();
        $hr->password = bcrypt('hr@gmail.com');
        $hr->save();
        $hr->assignRole('hr');

        $employee = new User();
        $employee->name = 'Employee User';
        $employee->email = 'employee@gmail.com';
        $employee->email_verified_at = now();
        $employee->password = bcrypt('employee@gmail.com');
        $employee->save();
        $employee->assignRole('employee');
    }
}
