<?php

namespace Database\Seeders;

use App\Helpers\Helper;
use App\Http\Traits\RoleTrait;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;
class RolesSeeder extends Seeder
{
    use RoleTrait;

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
                // Reset cached roles and permissions
                app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

                $guard = 'sanctum';

                $role = Role::create([
                    'guard_name' => $guard,
                    'code' => "278932",
                    'default' => 1,
                    'status'  =>1,
                    'name_en' => $this->superAdmin,
                    'name_bn' => $this->superAdmin,
                    'name' => $this->superAdmin
                ]);
                $role->givePermissionTo(Permission::all());
                $officeHeadRole = Role::create([
                    'guard_name' => $guard,
                    'code' => "10001",
                    'default' => 1,
                    'status'  =>1,
                    'name_en' => $this->officeHead,
                    'name_bn' => $this->officeHead,
                    'name' => $this->officeHead
                ]);
                $officeHeadRole->givePermissionTo(Permission::all());
        $salt = Helper::generateSalt();
        $admin = User::create(
            [
                'full_name'            => 'CTM',
                'username'            => 'ctm-01',
                'user_id'            => 1000,
                'email'                 => 'admin@ctm.com',
                'salt'                  =>$salt,
                'password'              => bcrypt($salt . '12345678') ,
                'user_type'               => $this->superAdminId,
                'remember_token'        => Str::random(10),
                'status'            => 1,
                'is_default_password'            => 0,
                'email_verified_at'     => now(),
            ]
        );

        $admin->assignRole([$role->id]);

    }
}
