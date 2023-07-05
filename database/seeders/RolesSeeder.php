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
                    'default' => 1,
                    'name' => $this->superAdmin
                ]);
        $role->givePermissionTo(Permission::all());
        //admin
        Role::create([
            'guard_name' => $guard,
            'default' => 1,
            'name' => $this->admin
        ]);
        //merchant
        Role::create([
            'guard_name' => $guard,
            'default' => 1,
            'name' => $this->merchant
        ]);
        Role::create([
            'guard_name' => $guard,
            'default' => 1,
            'name' => $this->branchAdmin
        ]);
        Role::create([
            'guard_name' => $guard,
            'default' => 1,
            'name' => $this->DelivaryMan
        ]);
        Role::create([
            'guard_name' => $guard,
            'default' => 1,
            'name' => $this->PickupMan
        ]);

        $admin = User::create(
            [
                'full_name'            => 'CTM',
                'email'                 => 'admin@metroexpress.com',
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
