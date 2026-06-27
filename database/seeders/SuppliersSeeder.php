<?php

namespace Database\Seeders;

use App\Models\Suppliers;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SuppliersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Suppliers::factory()->count(25)->create();
        $saveList=[
            ['name'=>'UD JONGBIRU', 'penanggung_jawab'=>'', 'phone'=>'+62 822-4550-2222', 'desc'=>''],
            ['name'=>'PAK GUN', 'penanggung_jawab'=>'', 'phone'=>'+62 813-3554-8298', 'desc'=>''],
            ['name'=>'BU SUPRAPTI', 'penanggung_jawab'=>'', 'phone'=>'+62 813-3524-7314', 'desc'=>''],
            ['name'=>'JAYA MULYA', 'penanggung_jawab'=>'', 'phone'=>'+62 812-3389-299', 'desc'=>''],
            ['name'=>'DEWA DEWI MALANG', 'penanggung_jawab'=>'', 'phone'=>'+62 821-3178-1975', 'desc'=>''],
            ['name'=>'UNILEVER', 'penanggung_jawab'=>'SALES MAS LUTFI', 'phone'=>'085706110919', 'desc'=>''],
            ['name'=>'PUJI SURYA', 'penanggung_jawab'=>'SALES PAK SUJATMIKO', 'phone'=>'0812 3335 7094', 'desc'=>''],
            ['name'=>'PT ARSY', 'penanggung_jawab'=>'', 'phone'=>'+62 858-6038-7375', 'desc'=>''],
            ['name'=>'CV. WELONG JAYA - AQUA', 'penanggung_jawab'=>'', 'phone'=>'+62 813-3234-3415', 'desc'=>''],
            ['name'=>'CLEO', 'penanggung_jawab'=>'', 'phone'=>'+62 812-1754-3516', 'desc'=>''],
            ['name'=>'CV.UTAMA', 'penanggung_jawab'=>'', 'phone'=>'+62 812-3321-7997', 'desc'=>''],
            ['name'=>'PT ENSEVAL MEGATRADING Tbk', 'penanggung_jawab'=>'', 'phone'=>'+62 822-4151-7117', 'desc'=>''],
            ['name'=>'SANTRI', 'penanggung_jawab'=>'', 'phone'=>'+62 856-4882-4392', 'desc'=>''],
            ['name'=>'PAK YIT', 'penanggung_jawab'=>'', 'phone'=>'+62 813-3335-5539', 'desc'=>''],
            ['name'=>'GANDUM MAS', 'penanggung_jawab'=>'', 'phone'=>'+62 821-4342-9245', 'desc'=>''],
            ['name'=>'PERCETAKAN HARKO', 'penanggung_jawab'=>'', 'phone'=>'+62 812-3415-433', 'desc'=>''],
            ['name'=>'PERC. DALLAS', 'penanggung_jawab'=>'', 'phone'=>'+62 821-4219-4157', 'desc'=>''],
            ['name'=>'PT MULTI TAMAN PLASTIK', 'penanggung_jawab'=>'', 'phone'=>'+62 822-3355-5589', 'desc'=>''],
            ['name'=>'BERKAT ABADI PLASTIK', 'penanggung_jawab'=>'', 'phone'=>'+62 852-3056-7100', 'desc'=>''],
            ['name'=>'TELUR AYAM', 'penanggung_jawab'=>'YAHYA', 'phone'=>'+62 857-4837-2161', 'desc'=>''],
            ['name'=>'AYAM SAYUR', 'penanggung_jawab'=>'PAK CANDRA', 'phone'=>'+62 821-3211-9268', 'desc'=>'BU ANI (+62 857-4904-3313)'],
            ['name'=>'AYAM KAMPUNG', 'penanggung_jawab'=>'PAK GHOZI', 'phone'=>'+62 877-1807-8757', 'desc'=>''],
            ['name'=>'TELUR PUYUH', 'penanggung_jawab'=>'PAK NARYO', 'phone'=>'+62 815-5350-6280', 'desc'=>'MBK IKA (+62 819-1110-8757)'],
            ['name'=>'KAMBING GULING/AQIQAH', 'penanggung_jawab'=>'PAK AGUS', 'phone'=>'+62 813-3004-4048', 'desc'=>''],
            ['name'=>'BEBEK PPM', 'penanggung_jawab'=>'BEBEK', 'phone'=>'+62 819-3880-6524', 'desc'=>''],
            ['name'=>'GURAMI', 'penanggung_jawab'=>'PAK AGUS', 'phone'=>'+62 858-5615-1252', 'desc'=>'PAK ANTON (+62 812-1613-8855)'],
            ['name'=>'SEAFOOD - PAK TO', 'penanggung_jawab'=>'PAK TO', 'phone'=>'+62 821-2480-2241', 'desc'=>''],
            ['name'=>'SEAFOOD - BU NUR', 'penanggung_jawab'=>'BU NUR', 'phone'=>'+62 812-3317-4177', 'desc'=>''],
            ['name'=>'ANEKA DAGING SAPI', 'penanggung_jawab'=>'MAS YUDI', 'phone'=>'+62 821-4151-2917', 'desc'=>''],
            ['name'=>'KIKIL & CECEK', 'penanggung_jawab'=>'BU HERLIN', 'phone'=>'+62 813-5980-8292', 'desc'=>''],
            ['name'=>'YAMIKU (PT. CHAROEN POCKPAN)', 'penanggung_jawab'=>'PAK TOPAN', 'phone'=>'+62 812-3112-396', 'desc'=>''],
            ['name'=>'RUMAH SOSIS', 'penanggung_jawab'=>'MBK LIKA', 'phone'=>'+62 857-3675-9224', 'desc'=>''],
            ['name'=>'KULIT SPRING ROLL', 'penanggung_jawab'=>'', 'phone'=>'+62 821-4213-9576', 'desc'=>''],
            ['name'=>'TELUR ASIN', 'penanggung_jawab'=>'', 'phone'=>'+62 856-4974-1800', 'desc'=>''],
            ['name'=>'PT SUKANDA JAYA / DIAMOND', 'penanggung_jawab'=>'MBK REKI', 'phone'=>'+62 812-1773-3393', 'desc'=>''],
            ['name'=>'Bu Erna Sayur', 'penanggung_jawab'=>'Bu Erna', 'phone'=>'+62 81357632047', 'desc'=>''],
            ['name'=>'ANI', 'penanggung_jawab'=>'ANI', 'phone'=>'+62 85749043313', 'desc'=>''],
            ['name'=>'Pak No', 'penanggung_jawab'=>'Pak No', 'phone'=>'+62 81359388499', 'desc'=>''],
            ['name'=>'Pak Ali', 'penanggung_jawab'=>'Pak Ali', 'phone'=>'+62 85649762839', 'desc'=>''],
            ['name'=>'Pak Chandra Ayam', 'penanggung_jawab'=>'Pak Chandra', 'phone'=>'+62 82132119268', 'desc'=>''],
            ['name'=>'Muryanto Sayur', 'penanggung_jawab'=>'Muryanto', 'phone'=>'+62 85735543855', 'desc'=>''],
        ];
        foreach($saveList as $save){
            Suppliers::firstOrCreate(['name'=>$save['name'],'penanggung_jawab'=>$save['penanggung_jawab']],$save);
        }
    }
}
