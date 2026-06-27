<?php

namespace Database\Seeders;

use App\Models\Menus;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MenusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $saveList=[
            ['id'=>'1','name'=>'Dashboard','type'=>'menu','order'=>'1','url'=>'home','is_permission'=>true,'icon'=>'dashboard'],
            ['id'=>'2','name'=>'Transaksi','type'=>'label','order'=>'2'],
            ['id'=>'3','name'=>'Customer Service','type'=>'menu','order'=>'3','url'=>'customer_service','is_permission'=>true,'icon'=>'cart'],
            ['id'=>'4','name'=>'Cost Controling','type'=>'menu','order'=>'4','url'=>'cost_controling','is_permission'=>true,'icon'=>'finance'],
            ['id'=>'5','name'=>'Purchasing','type'=>'menu','order'=>'5','url'=>'purchasing','is_permission'=>true,'icon'=>'purchase'],
            ['id'=>'6','name'=>'Manajemen Stok','type'=>'menu','order'=>'6','url'=>'management_stok','is_permission'=>true,'icon'=>'stok'],
            ['id'=>'7','name'=>'Master','type'=>'label','order'=>'7'],
            ['id'=>'8','name'=>'Data Master','type'=>'submenu','order'=>'8','is_permission'=>true,'icon'=>'data-master'],
            ['id'=>'9','name'=>'Pengaturan','type'=>'label','order'=>'9'],
            ['id'=>'10','name'=>'Daftar Pengaturan','type'=>'submenu','order'=>'10','is_permission'=>true,'icon'=>'data-option'],
            
            ['id'=>'11','name'=>'Data Customer','type'=>'menu','order'=>'1','sub_id'=>'8','url'=>'customers'],
            ['id'=>'12','name'=>'Data Supplier','type'=>'menu','order'=>'2','sub_id'=>'8','url'=>'suppliers'],
            ['id'=>'13','name'=>'Data Barang','type'=>'menu','order'=>'3','sub_id'=>'8','url'=>'ingredients'],
            ['id'=>'14','name'=>'Data Kategori Menu','type'=>'menu','order'=>'5','sub_id'=>'8','url'=>'category_menus'],
            ['id'=>'15','name'=>'Data Menu','type'=>'menu','order'=>'6','sub_id'=>'8','url'=>'menus_catering'],
            ['id'=>'16','name'=>'Data Karyawan','type'=>'menu','order'=>'7','sub_id'=>'8','url'=>'employes'],
            ['id'=>'22','name'=>'Referensi Wilayah','type'=>'menu','order'=>'8','sub_id'=>'8','url'=>'ref_wilayah'],
            ['id'=>'23','name'=>'Data Paket Menu','type'=>'menu','order'=>'4','sub_id'=>'8','url'=>'packet_menus'],

            ['id'=>'21','name'=>'Sidebar','type'=>'menu','order'=>'1','sub_id'=>'10','url'=>'menus_sidebar'],
            ['id'=>'17','name'=>'Daftar User','type'=>'menu','order'=>'2','sub_id'=>'10','url'=>'pengguna'],
            ['id'=>'18','name'=>'Role Permission','type'=>'menu','order'=>'3','sub_id'=>'10','url'=>'role_assignment'],
            ['id'=>'19','name'=>'Profil Usaha','type'=>'menu','order'=>'4','sub_id'=>'10','url'=>'profile_bussines'],
            ['id'=>'20','name'=>'Backup Data','type'=>'menu','order'=>'5','sub_id'=>'10','url'=>'backup_restores'],

            ['id'=>'24','name'=>'Kitchen','type'=>'menu','order'=>'5','url'=>'kitchen','is_permission'=>true,'icon'=>'kitchen'],

            // Versi Original
            // ['id'=>'2','name'=>'Managemen Pengguna','type'=>'submenu','order'=>'2','is_permission'=>true,'icon'=>'book-reference'],
            // ['id'=>'3','name'=>'Pelanggan & Supplier','type'=>'submenu','order'=>'3','is_permission'=>true,'icon'=>'book-reference'],
            // ['id'=>'4','name'=>'Master Data','type'=>'submenu','order'=>'4','is_permission'=>true,'icon'=>'book-reference'],
            // ['id'=>'5','name'=>'Gudang/Stok','type'=>'submenu','order'=>'5','is_permission'=>true,'icon'=>'book-reference'],
            // ['id'=>'6','name'=>'Pembelian','type'=>'submenu','order'=>'6','is_permission'=>true,'icon'=>'book-reference'],
            // ['id'=>'7','name'=>'Pemesanan','type'=>'submenu','order'=>'7','is_permission'=>true,'icon'=>'book-reference'],
            // ['id'=>'8','name'=>'Laporan','type'=>'submenu','order'=>'8','is_permission'=>true,'icon'=>'book-reference'],
            
            // ['id'=>'9','name'=>'Daftar Pegguna','type'=>'menu','order'=>'1','sub_id'=>'2','url'=>'pengguna'],
            // ['id'=>'10','name'=>'Role Permission','type'=>'menu','order'=>'2','sub_id'=>'2','url'=>'role_assignment'],
            // ['id'=>'11','name'=>'Customer','type'=>'menu','order'=>'1','sub_id'=>'3','url'=>'customer'],
            // ['id'=>'12','name'=>'Supplier','type'=>'menu','order'=>'2','sub_id'=>'3','url'=>'supplier'],
            // ['id'=>'13','name'=>'Bahan Baku/Ingredients','type'=>'menu','order'=>'1','sub_id'=>'4','url'=>'ingredients'],
            // ['id'=>'14','name'=>'Menu Makanan','type'=>'menu','order'=>'2','sub_id'=>'4','url'=>'menus'],
            // ['id'=>'15','name'=>'Stok','type'=>'menu','order'=>'1','sub_id'=>'5','url'=>'stock'],
            // ['id'=>'16','name'=>'Stok Perpindahan','type'=>'menu','order'=>'2','sub_id'=>'5','url'=>'stock_movements'],
            // ['id'=>'17','name'=>'Pembelian','type'=>'menu','order'=>'1','sub_id'=>'6','url'=>'purchases'],
            // ['id'=>'18','name'=>'Pemesanan','type'=>'menu','order'=>'1','sub_id'=>'7','url'=>'orders'],
            // ['id'=>'19','name'=>'Pemesanan','type'=>'menu','order'=>'1','sub_id'=>'8','url'=>'report_orders'],
            // ['id'=>'20','name'=>'Pembelian','type'=>'menu','order'=>'2','sub_id'=>'8','url'=>'report_purchases'],
            // ['id'=>'21','name'=>'Stok','type'=>'menu','order'=>'3','sub_id'=>'8','url'=>'report_stock'],
        ];
        foreach($saveList as $save){
            $user=Menus::insert($save);
        };
    }
}
