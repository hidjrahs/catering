<?php

namespace Database\Seeders;

use App\Models\RefCity;
use App\Models\RefDistrict;
use App\Models\RefProvince;
use App\Models\RefVilage;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Seeder untuk mengisi referensi wilayah Jawa Timur
 * Sumber data: laravolt/indonesia (tabel indonesia_provinces, indonesia_cities, indonesia_districts, indonesia_villages)
 * Target: tabel lokal (province, city, district, vilage)
 * 
 * Pastikan sudah menjalankan:
 *   1. php artisan vendor:publish --provider="Laravolt\Indonesia\ServiceProvider" --tag=migrations
 *   2. php artisan migrate (untuk tabel indonesia_*)
 *   3. php artisan laravolt:indonesia:seed
 * Kemudian jalankan: php artisan db:seed --class=JawaTimurWilayahSeeder
 */
class JawaTimurWilayahSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('=== Memulai Seeder Wilayah Jawa Timur ===');

        // 1. Pastikan Provinsi Jawa Timur sudah ada di tabel lokal
        $provinceLocal = RefProvince::firstOrCreate(
            ['name' => 'Jawa Timur'],
            ['name' => 'Jawa Timur']
        );
        $this->command->info("✓ Provinsi Jawa Timur (lokal): {$provinceLocal->id}");

        // 2. Ambil data provinsi Jawa Timur dari tabel laravolt (relasi via code)
        $provinceSource = DB::table('indonesia_provinces')
            ->where('name', 'LIKE', '%JAWA TIMUR%')
            ->orWhere('name', 'LIKE', '%Jawa Timur%')
            ->first();

        if (!$provinceSource) {
            $this->command->error('Provinsi Jawa Timur tidak ditemukan di tabel indonesia_provinces.');
            $this->command->warn('Pastikan sudah menjalankan: php artisan laravolt:indonesia:seed');
            return;
        }

        $this->command->info("✓ Source Provinsi: {$provinceSource->name} (Code: {$provinceSource->code})");

        // 3. Ambil semua Kota/Kabupaten di Jawa Timur dari laravolt (gunakan province_code)
        $cities = DB::table('indonesia_cities')
            ->where('province_code', $provinceSource->code)
            ->orderBy('name')
            ->get();

        $this->command->info("✓ Ditemukan {$cities->count()} Kota/Kabupaten di Jawa Timur");
        $cityCount = 0;
        $districtCount = 0;
        $villageCount = 0;

        foreach ($cities as $city) {
            // Bersihkan nama kota (laravolt menyimpan dalam UPPERCASE)
            $cityName = $this->formatName($city->name);

            $cityLocal = RefCity::firstOrCreate(
                ['name' => $cityName],
                [
                    'name'        => $cityName,
                    'province_id' => $provinceLocal->id,
                ]
            );
            $cityCount++;

            // 4. Ambil semua Kecamatan di kota ini (gunakan city_code)
            $districts = DB::table('indonesia_districts')
                ->where('city_code', $city->code)
                ->orderBy('name')
                ->get();

            foreach ($districts as $district) {
                $districtName = $this->formatName($district->name);

                $districtLocal = RefDistrict::firstOrCreate(
                    ['name' => $districtName, 'city_id' => $cityLocal->id],
                    [
                        'name'    => $districtName,
                        'city_id' => $cityLocal->id,
                    ]
                );
                $districtCount++;

                // 5. Ambil semua Kelurahan/Desa di kecamatan ini (gunakan district_code)
                $villages = DB::table('indonesia_villages')
                    ->where('district_code', $district->code)
                    ->orderBy('name')
                    ->get();

                foreach ($villages as $village) {
                    $villageName = $this->formatName($village->name);

                    RefVilage::firstOrCreate(
                        ['name' => $villageName, 'district_id' => $districtLocal->id],
                        [
                            'name'        => $villageName,
                            'district_id' => $districtLocal->id,
                        ]
                    );
                    $villageCount++;
                }
            }

            $this->command->line("  → {$cityName}: {$districts->count()} kecamatan");
        }

        $this->command->info('=== Seeder Selesai ===');
        $this->command->table(
            ['Level', 'Jumlah'],
            [
                ['Provinsi',         1],
                ['Kota/Kabupaten',   $cityCount],
                ['Kecamatan',        $districtCount],
                ['Kelurahan/Desa',   $villageCount],
            ]
        );
    }

    /**
     * Format nama dari UPPERCASE (laravolt) ke Title Case proper.
     * Contoh: "KABUPATEN SIDOARJO" → "Kabupaten Sidoarjo"
     */
    private function formatName(string $name): string
    {
        return collect(explode(' ', strtolower($name)))
            ->map(fn($word) => ucfirst($word))
            ->join(' ');
    }
}
