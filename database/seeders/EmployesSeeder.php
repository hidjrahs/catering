<?php

namespace Database\Seeders;

use App\Models\Employes;
use Illuminate\Database\Seeder;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Excel as Exc;
use Carbon\Carbon;
use PhpOffice\PhpSpreadsheet\IOFactory;

class EmployesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $filePath = storage_path('app/public/BIODATA KARYAWAN LILA 2025.xlsx');
        
        if (!file_exists($filePath)) {
            $this->command->warn('File excel BIODATA KARYAWAN LILA 2025.xlsx tidak ditemukan, seeder menggunakan data dummy (15 employes).');
            Employes::factory()->count(15)->create();
            return;
        }

        $sheets = ['OFFICE', 'KITCHEN', 'GUDANG'];
        $spreadsheet = IOFactory::load($filePath);
        $sheetNames = $spreadsheet->getSheetNames();

        foreach ($sheets as $sheetName) {
            // ✅ FIX: baca per sheet langsung
            $rows = Excel::toCollection(null, $filePath, null, Exc::XLSX)->get( array_search($sheetName, $sheetNames) );
            // dd($rows);
            // Cari baris header
            $startIndex = $rows->search(fn ($row) => isset($row[1]) && str_contains(strtoupper($row[1]), 'NAMA'));
            if ($startIndex === false) continue;
            
            $data = $rows->slice($startIndex + 1)->filter(fn ($r) => !empty($r[1]));
            foreach ($data as $r) {
                $name = trim($r[1] ?? '');
                if (empty($name)) continue;
                Employes::create([
                    'name' => $name,
                    'phone' => $r[9] ?? null,
                    'address' => $r[8] ?? null,
                    'location' => strtolower($sheetName),
                    'gender' => strtoupper(trim($r[4] ?? '')) === 'L' ? 'L' : 'P',
                    'national_id' => $r[10] ?? null,          // NO KTP/SIM
                    'status' => $r[11] ?? null,              // STATUS
                    'work_since' => $r[12] ?? null,          // BEKERJA SEJAK
                    'division' => $r[2] ?? null,             // Jabatan/Bagian
                    'birth_place_date' => $r[3] ?? null,     // Tempat/Tgl Lahir
                    'height_cm' => is_numeric($r[5] ?? null) ? (int) $r[5] : null,
                    'weight_kg' => is_numeric($r[6] ?? null) ? (int) $r[6] : null,
                    'religion' => $r[7] ?? null
                ]);
            }
        }
    }
}
