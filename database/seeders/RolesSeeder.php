<?php

namespace Database\Seeders;

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
                    'name_en' => $this->superAdmin,
                    'name_bn' => $this->superAdmin,
                    'name' => $this->superAdmin
                ]);
        $role->givePermissionTo(Permission::all());


        $admin = User::create(
            [
                'full_name'            => 'CTM',
                'username'            => 'ctm-01',
                'email'                 => 'admin@ctm.com',
                'password'              => bcrypt('@N159983a'), // password = R4d&DjVx
                'user_type'               => $this->superAdminId,
                'remember_token'        => Str::random(10),
                'status'            => 1,
                'email_verified_at'     => now(),
            ]
        );

        $admin->assignRole([$role->id]);

    }
}
