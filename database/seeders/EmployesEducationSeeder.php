<?php

namespace Database\Seeders;

use App\Models\EmployeeEducations;
use App\Models\EmployeeEmergencies;
use App\Models\EmployeeFamilies;
use App\Models\Employes;
use Illuminate\Database\Seeder;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Excel as Exc;
use Carbon\Carbon;
use PhpOffice\PhpSpreadsheet\IOFactory;

class EmployesEducationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Employes::factory()->count(15)->create();
        $filePath = storage_path('app/public/BIODATA KARYAWAN LILA 2025.xlsx');
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
                // Cari berdasarkan nama (mirip)
                $employee = Employes::where('name', 'like', "%{$name}%")->first();
                if (!$employee) continue; //Log::error($r);
                EmployeeEducations::create([
                    'employee_id'=> $employee->id,
                    'education_level' => $r[13] ?? null,
                    'school_name' => $r[14] ?? null,
                    'city' => $r[15] ?? null,
                    'major' => $r[16] ?? null,
                    'year_start' => is_numeric($r[17] ?? null) ? $r[17] : null,
                    'year_graduated' => is_numeric($r[18] ?? null) ? $r[18] : null,
                ]);
                EmployeeFamilies::create([
                    'employee_id'=> $employee->id,
                    'name' => $r[19] ?? null,
                    'relation' => $r[20] ?? null,
                    'birth_place_date' => $r[21] ?? null,
                    'gender' => strtoupper(trim($r[22] ?? '')) === 'L' ? 'L' : 'P',
                    'education' => $r[23] ?? null,
                ]);
                EmployeeEmergencies::create([
                    'employee_id'=> $employee->id,
                    'name' => $r[24] ?? null,      // Nama kontak darurat
                    'relation' => $r[25] ?? null,  // Hubungan
                    'address' => $r[26] ?? null,   // Alamat
                    'phone' => $r[27] ?? null,     // Nomor telepon
                ]);
            }
        }
    }
}
