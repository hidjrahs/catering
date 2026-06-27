<?php

namespace Database\Seeders;

use App\Models\EmployeeContracts;
use Illuminate\Database\Seeder;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Excel as Exc;
use Carbon\Carbon;
use App\Models\Employes;
use Illuminate\Support\Facades\Log;
use PhpOffice\PhpSpreadsheet\IOFactory;

class EmployesContractSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $filePath = storage_path('app/public/BIODATA KARYAWAN LILA 2025.xlsx');
        // $rows = Excel::toCollection(null, $filePath, null, Exc::XLSX, 'KONTRAK');//->first();\
        $spreadsheet = IOFactory::load($filePath);
        $sheetNames = $spreadsheet->getSheetNames();
        $rows = Excel::toCollection(null, $filePath, null, Exc::XLSX)->get( array_search('KONTRAK', $sheetNames) );
        
        $startIndex = $rows->search(fn ($row) => isset($row[1]) && str_contains(strtoupper($row[1]), 'NAMA LENGKAP'));
        if ($startIndex === false) return;
        $data = $rows->slice($startIndex + 1)->filter(fn ($r) => !empty($r[1]));
        foreach ($data as $r) {
            $name = trim($r[1] ?? '');
            if (empty($name)) continue;
            // Cari berdasarkan nama (mirip)
            $employee = Employes::where('name', 'like', "%{$name}%")->first();
            if (!$employee) continue; //Log::error($r);
            EmployeeContracts::create([
                'employee_id'=> $employee->id,
                'contract_end' => $this->parseDate($r[3] ?? null),
                'interview_result' => $r[4] ?? null
            ]);
        }
    }

    private function parseDate($value)
    {
        if (empty($value)) return null;
        try {
            return Carbon::parse($value)->format('Y-m-d');
        } catch (\Throwable $th) {
            return null;
        }
    }
}
