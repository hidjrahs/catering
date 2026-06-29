<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();
        $permissionList=[
            ['name' => 'home','menu_id'=>'1','label_name'=>'View'],
        ];
        foreach($permissionList as $permission){
            $permission['guard_name']="web";
            Permission::insertOrIgnore($permission,$permission);
            $permission['guard_name']="api";
            Permission::insertOrIgnore($permission,$permission);
        }

        $fullThrotleSAWeb = Role::firstOrCreate(['name' => 'super_admin','guard_name'=>'web']);
        $fullThrotleSAApi = Role::firstOrCreate(['name' => 'super_admin','guard_name'=>'api']);

        $cs = Role::firstOrCreate(['name' => 'customer_service','guard_name'=>'web'], ['is_vpn'=>true]);
        $csApi = Role::firstOrCreate(['name' => 'customer_service','guard_name'=>'api'], ['is_vpn'=>true]);
        $verifikator = Role::firstOrCreate(['name' => 'cost_control','guard_name'=>'web'], ['is_vpn'=>true]);
        $verifikatorApi = Role::firstOrCreate(['name' => 'cost_control','guard_name'=>'api'], ['is_vpn'=>true]);
        $admin = Role::firstOrCreate(['name' => 'admin','guard_name'=>'web']);
        $adminApi = Role::firstOrCreate(['name' => 'admin','guard_name'=>'api']);
        $purchasing = Role::firstOrCreate(['name' => 'purchasing','guard_name'=>'web'], ['is_vpn'=>true]);
        $purchasingApi = Role::firstOrCreate(['name' => 'purchasing','guard_name'=>'api'], ['is_vpn'=>true]);

        $managementStok = Role::firstOrCreate(['name' => 'management_stok','guard_name'=>'web'], ['is_vpn'=>true]);
        $managementStokApi = Role::firstOrCreate(['name' => 'management_stok','guard_name'=>'api'], ['is_vpn'=>true]);
        $kitchen = Role::firstOrCreate(['name' => 'kitchen','guard_name'=>'web'], ['is_vpn'=>true]);
        $kitchenApi = Role::firstOrCreate(['name' => 'kitchen','guard_name'=>'api'], ['is_vpn'=>true]);

        // Assign ke role
        // $cs->givePermissionTo(['order.create']);
        // $verifikator->givePermissionTo(['order.verify']);
        // $admin->givePermissionTo(['master.manage']);
        // $purchasing->givePermissionTo(['stock.update']);

    }
}
