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
                'device_id' => '464bce6fa106822739d1d367907c33d9',
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
