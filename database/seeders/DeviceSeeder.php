<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Device;

class DeviceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $devices = [
            [
                'id' => 1,
                'user_id' => 1000,
                'name' => 'juned_laptop',
                'device_name' => 'MSI',
                'device_id' => '903b624e16a7c55c70d287a7ecc7432b',
                'ip_address' => '127.0.0.1',
                'createdBy' => 1,
                'device_type' => 1,
                'status' => 1,
            ],
            [
                'id' => 2,
                'user_id' => 1000,
                'name' => 'tarikul',
                'device_name' => 'laptop',
                'device_id' => '82431752d5f6807aeeaa87a24eb0251b',
                'ip_address' => '127.0.0.1',
                'createdBy' => 1,
                'device_type' => 1,
                'status' => 1,
            ],
            [
                'id' => 3,
                'user_id' => 1000,
                'name' => 'sujon',
                'device_name' => 'laptop',
                'device_id' => '29c6aeadc0aad291085383b56dcedad8',
                'ip_address' => '127.0.0.1',
                'createdBy' => 1,
                'device_type' => 1,
                'status' => 1,
            ],
            [
                'id' => 4,
                'user_id' => 1000,
                'name' => 'Jesmin',
                'device_name' => 'laptop',
                'device_id' => '8526c03dbf24cc59691a63e4523856bc',
                'ip_address' => '127.0.0.1',
                'createdBy' => 1,
                'device_type' => 1,
                'status' => 1,
            ],
            [
                'id' => 5,
                'user_id' => 1000,
                'name' => 'MD',
                'device_name' => 'laptop',
                'device_id' => 'd0e84bcb428d71a92ef936cb17638608',
                'ip_address' => '127.0.0.1',
                'createdBy' => 1,
                'device_type' => 1,
                'status' => 1,
            ],
        ];

        foreach ($devices as $value) {
            $device = new Device;
            $device->id           = $value['id'];
            $device->user_id         = $value['user_id'];
            $device->name  = $value['name'];
            $device->device_name  = $value['device_name'];
            $device->device_id        = $value['device_id'];
            $device->ip_address        = $value['ip_address'];
            $device->createdBy        = $value['createdBy'];
            $device->device_type        = $value['device_type'];
            $device->status        = $value['status'];
            $device->save();
        }
    }
}
