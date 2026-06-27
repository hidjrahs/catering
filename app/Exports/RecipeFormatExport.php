<?php

namespace App\Exports;

use App\Models\Category;
use App\Models\CategoryMenusCatering;
use App\Models\PacketCatering;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Cell\DataValidation;
use PhpOffice\PhpSpreadsheet\Style\Protection;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Color;

class RecipeFormatExport implements FromArray, WithHeadings, WithEvents
{
    public function array(): array
    {
        $paketl=['Paket Standard','Paket Reguler','Paket Spesial','Paket Premium','Paket Platinum','Paket Emerald'];
        $examples = [
            ['SAYUR ASEM JAWA', 'SUP / SOUP', join(',',$paketl), 250,'Wortel', 3000, 'gram', 1000, 14500],
            ['', '', '', '', 'Kacang Panjang', 1000, 'gram', 1000, 12000],
            ['', '', '', '', 'Kangkung', 1000, 'gram', 1000, 8000],
            ['', '', '', '', 'Taoge Kedele', 1000, 'gram', 1000, 15000],
            ['', '', '', '', 'Kacang Tanah', 500, 'gram', 1000, 28000],
            ['', '', '', '', 'Kerai', 3000, 'gram', 1000, 8000],
            ['', '', '', '', 'Labu Siam', 3000, 'gram', 1000, 5000],
            ['', '', '', '', 'Belimbing wuluh', 500, 'gram', 1000, 50000],
            ['', '', '', '', 'Tomat mentah', 500, 'gram', 1000, 15000],
            ['', '', '', '', 'Daun So', 250, 'gram', 250, 5000],
            ['', '', '', '', 'Jagung Muda', 2000, 'gram', 1000, 7000],
            ['', '', '', '', 'Bawang merah', 250, 'gram', 1000, 38000],
            ['', '', '', '', 'Bawang putih', 150, 'gram', 1000, 40000],
            ['', '', '', '', 'Kemiri', 100, 'gram', 1000, 48000],
            ['', '', '', '', 'Cabe merah', 100, 'gram', 1000, 67000],
            ['', '', '', '', 'Laos', 100, 'gram', 1000, 15000],
            ['', '', '', '', 'Asem jawa', 50, 'gram', 1000, 50000],
            ['', '', '', '', 'Gula', 175, 'gram', 1000, 14500],
            ['', '', '', '', 'Garam', 100, 'gram', 250, 1950],
            ['', '', '', '', 'Sasa', 150, 'gram', 250, 12400],
            ['', '', '', '', 'Royco', 60, 'gram', 10, 475],
            ['', '', '', '', 'Air', 18000, 'ml', 18000, 5000],
            ['', '', '', '', 'Sambal Terasi', '', '', '', ''],
            ['', '', '', '', 'Cabe rawit', 60, 'gram', 1000, 80000],
            ['', '', '', '', 'Cabe merah', 400, 'gram', 1000, 67000],
            ['', '', '', '', 'Terasi', 4, 'bks', 1, 1500],
            ['', '', '', '', 'Tomat', 1200, 'gram', 1000, 12000],
            ['', '', '', '', 'Bawang merah', 400, 'gram', 1000, 38000],
            ['', '', '', '', 'Bawang putih', 100, 'gram', 1000, 40000],
            ['', '', '', '', 'Gula merah', 120, 'gram', 1000, 17500],
            ['', '', '', '', 'Gula putih', 120, 'gram', 1000, 14500],
            ['', '', '', '', 'Garam', 32, 'gram', 250, 1950],
            ['', '', '', '', 'Sasa', 40, 'gram', 250, 12400],
            ['', '', '', '', 'Keterangan', '', '', '', ''],
            ['', '', '', '', 'Daging (kaldu)', 1000, 'gram', 1000, 100000],
            ['', '', '', '', '', '', '', '', ''],
            ['SUP MERAH', 'SUP / SOUP', 'Paket Emerald', 250,'Ayam dada fillet', 2000, 'gram', 1000, 47000],
            ['', '', '', '', 'Sosis Sapi (Charm isi 15)', 750, 'gram', 375, 30000],
            ['', '', '', '', 'Lidah', 1000, 'gram', 1000, 95000],
            ['', '', '', '', 'Jamur Kaleng', 1360, 'gram', 425, 13000],
            ['', '', '', '', 'Wortel', 4000, 'gram', 1000, 14500],
            ['', '', '', '', 'Bombay (tabur)', 100, 'gram', 1000, 22500],
            ['', '', '', '', 'Pre (tabur)', 100, 'gram', 1000, 18000],
            ['', '', '', '', 'Saus tomat delmonte (botol 340 ml)', 2040, 'gram', 340, 16000],
            ['', '', '', '', 'Susu indomilk 370 gr', 370, 'gram', 370, 13500],
            ['', '', '', '', 'Raja rasa 600', 200, 'gram', 600, 31700],
            ['', '', '', '', 'Kecap 150', 100, 'ml', 625, 21500],
            ['', '', '', '', 'Maizena', 150, 'gram', 1000, 16000],
            ['', '', '', '', 'Gula (tambahan)', 875, 'gram', 1000, 14500],
            ['', '', '', '', 'Mentega', 600, 'gram', 5000, 110000],
            ['', '', '', '', 'Roombutter', 90, 'gram', 340, 92500],
            ['', '', '', '', 'Kuah', '', '', '', ''],
            ['', '', '', '', 'Ayam kampung', 1000, 'gram', 1000, 90000],
            ['', '', '', '', 'Bawang merah', 400, 'gram', 1000, 38000],
            ['', '', '', '', 'Bawang pre', 500, 'gram', 1000, 18000],
            ['', '', '', '', 'Bawang bombay', 500, 'gram', 1000, 22500],
            ['', '', '', '', 'Seledri', 50, 'gram', 1000, 25000],
            ['', '', '', '', 'Sasa', 150, 'gram', 250, 12400],
            ['', '', '', '', 'Garam', 120, 'gram', 250, 1950],
            ['', '', '', '', 'Knor ayam', 150, 'gram', 1000, 91700],
            ['', '', '', '', 'Gula', 175, 'gram', 1000, 14500],
            ['', '', '', '', 'Merica', 14, 'gram', 1000, 92000],
            ['', '', '', '', 'Pala', 45, 'gram', 1000, 130000],
            ['', '', '', '', 'Air (3000 air kaldu)', 18000, 'ml', 18000, 5000],
            ['', '', '', '', 'Bakso Printil Sapi', '', '', '', ''],
            ['', '', '', '', 'Daging sapi', 2000, 'gram', 1000, 100000],
            ['', '', '', '', 'Tapioka tani', 400, 'gram', 1000, 27400],
            ['', '', '', '', 'Bawang putih', 20, 'gram', 1000, 40000],
            ['', '', '', '', 'Garam', 17, 'gram', 250, 1950],
            ['', '', '', '', 'Sasa', 18, 'gram', 250, 12400],
            ['', '', '', '', 'Gula', 50, 'gram', 1000, 14500],
            ['', '', '', '', 'Telur', 100, 'gram', 1000, 23500],
            ['', '', '', '', '', '', '', '', ''],
            ['AYAM GORENG KREMES', 'CHICKEN / AYAM', join(',',$paketl), 200,'Ayam paha tempongan', 8000, 'gram', 1000, 33000],
            ['', '', '', '', 'Bawang puith', 100, 'gram', 1000, 40000],
            ['', '', '', '', 'Ketumbar', 20, 'gram', 1000, 48000],
            ['', '', '', '', 'Kunyit', 100, 'gram', 1000, 12000],
            ['', '', '', '', 'Daun salam', 20, 'gram', 1000, 10000],
            ['', '', '', '', 'Garam', 34, 'gram', 250, 1950],
            ['', '', '', '', 'Sasa', 75, 'gram', 250, 12400],
            ['', '', '', '', 'Royco', 20, 'gram', 10, 475],
            ['', '', '', '', 'Adonan Kremes', '', '', '', ''],
            ['', '', '', '', 'Tepung kanji', 200, 'gram', 1000, 27400],
            ['', '', '', '', 'Maizena', 200, 'gram', 1000, 23000],
            ['', '', '', '', 'Santan', 50, 'ml', 65, 4000],
            ['', '', '', '', 'Minyak goreng', 5000, 'gram', 18000, 325000],
            ['', '', '', '', 'Air', 3000, 'ml', 18000, 5000],
            ['', '', '', '', '', '', '', '', ''],
            ['NASI GORENG CURRY NANAS', 'NASI GORENG / FRIED RICE', join(',',$paketl), 100, 'Nasi untuk nasi goreng', 3000, 'gram', 1000, 13200],
            ['', '', '', '', 'Nanas muda (dipotong dadu kecil)', 2, 'biji', 1, 8000],
            ['', '', '', '', 'Bumbu Nasi Goreng', '', '', '', ''],
            ['', '', '', '', 'Bawang merah', 225, 'gram', 1000, 38000],
            ['', '', '', '', 'Bawang putih', 150, 'gram', 1000, 40000],
            ['', '', '', '', 'Lombok kecil', 60, 'gram', 1000, 80000],
            ['', '', '', '', 'Minyak goreng', 100, 'gram', 18000, 325000],
            ['', '', '', '', 'Bahan Pelengkap', '', '', '', ''],
            ['', '', '', '', 'Ayam dada', 1000, 'gram', 1000, 44000],
            ['', '', '', '', 'Pre', 100, 'gram', 1000, 18000],
            ['', '', '', '', 'Madras cury', 80, 'gram', 500, 38000],
            ['', '', '', '', 'Sentir', 100, 'gram', 1000, 25000],
            ['', '', '', '', 'Bawang putih', 100, 'gram', 1000, 40000],
            ['', '', '', '', 'Minyak goreng', 150, 'gram', 18000, 325000],
            ['', '', '', '', 'Garam', 45, 'gram', 250, 1950],
            ['', '', '', '', 'Gula', 120, 'gram', 1000, 14500],
            ['', '', '', '', 'Sasa', 70, 'gram', 250, 12400],
            ['', '', '', '', 'Raja rasa', 300, 'gram', 600, 31700],
            ['', '', '', '', 'Fish saos', 50, 'gram', 600, 33000],
            ['', '', '', '', 'Minyak wijen', 50, 'gram', 620, 37500],
            ['', '', '', '', 'Telur', 625, 'gram', 1000, 23500],
            ['', '', '', '', 'Bawang goreng', 50, 'gram', 600, 88945],
            ['', '', '', '', '', '', '', '', ''],
            ['PUDDING LUMUT', 'ANEKA PUDING','Paket Emerald', 100,'Susu Fresh', 1000, 'liter', 1000, 21000],
            ['', '', '', '', 'Agar-Agar Satelit', 8, 'bks', 1, 4800],
            ['', '', '', '', 'Gula Merah', 1000, 'gram', 1000, 17500],
            ['', '', '', '', 'Santan 2', 1000, 'ml', 1000, 12000],
            ['', '', '', '', 'Air', 5000, 'gram', 18000, 5000],
            ['', '', '', '', 'Gula', 1000, 'gram', 1000, 14500],
            ['', '', '', '', 'Telur', 20, 'butir', 1000, 23500],
            ['', '', '', '', 'Perasa pandan', 1, 'sdm', 60, 10000],
            ['', '', '', '', 'Daun suji', 5, 'lembar', 1, 500],
            ['', '', '', '', 'Pandan', 5, 'lembar', 1, 500],
            ['PUDDING KETAN HITAM', 'ANEKA PUDING','Paket Emerald', 100,'Ketan hitam', 500, 'gram', 1000, 22000],
            ['', '', '', '', 'Agar-Agar Satelit', 5, 'bks', 1, 4800],
            ['', '', '', '', 'Nutrijel plain', 5, 'bks', 1, 4200],
            ['', '', '', '', 'Gula Merah', 1000, 'gram', 1000, 18500],
            ['', '', '', '', 'Santan 3', 1000, 'ml', 1000, 32500],
            ['', '', '', '', 'Air', 5000, 'gram', 18000, 5000],
            ['', '', '', '', 'Gula', 1000, 'gram', 1000, 15000],
            ['', '', '', '', 'Cremer', 450, 'gram', 500, 32500],
            ['', '', '', '', 'Perasa pandan', 1, 'sdm', 50, 28500],
            ['', '', '', '', 'Pandan', 5, 'lembar', 10, 2000],
        ];

        // === Tambahkan baris kosong agar total tetap 50 ===
        $emptyRows = array_fill(0, 40, array_fill(0, 12, ''));
        return array_merge($examples, $emptyRows);
    }
    public function headings(): array
    {
        return [
            'recipe_name',
            'category',         // dropdown 1 pilihan dari tabel
            'paket',            // multi-pilih (pisahkan koma)
            'portion_standard',
            'ingredient_name',
            'qty',
            'satuan', // dropdown 1 pilihan
            'unit',             
            'price_per_unit'
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $rowInit=1000;
                $maxRows = $rowInit+1;
                $cheader='A1:I1';
                $inputRange = "A2:I{$maxRows}";
                /**
                 * === PROTECTION (Lock all, unlock input range) ===
                 */
                $sheet->getProtection()->setSheet(true);
                $sheet->getProtection()->setPassword('@Resep2025_');
                // $sheet->getStyle('A1:I1000')->getProtection()->setLocked(Protection::PROTECTION_PROTECTED);
                $sheet->getStyle($inputRange)->getProtection()->setLocked(Protection::PROTECTION_UNPROTECTED);

                /**
                 * === Dropdown Category (1 pilihan dari DB) ===
                 */
                $categories = CategoryMenusCatering::select(['id','name'])->get()->pluck('name')->toArray();
                if (empty($categories)) {
                    $categories = config('option.categories_default');
                }
                $categoryList = '"' . implode(',', $categories) . '"';

                for ($row = 2; $row <= $maxRows; $row++) {
                    $validation = $sheet->getCell("B{$row}")->getDataValidation();
                    $validation->setType(DataValidation::TYPE_LIST);
                    $validation->setErrorStyle(DataValidation::STYLE_STOP);
                    $validation->setAllowBlank(false);
                    $validation->setShowDropDown(true);
                    $validation->setFormula1($categoryList);
                    $validation->setErrorTitle('Input Salah');
                    $validation->setError('Pilih salah satu kategori dari daftar yang tersedia.');
                }

                /**
                 * === Dropdown Paket (boleh lebih dari satu, pisahkan koma) ===
                 */
                $pakets = PacketCatering::select(['id','name'])->get()->pluck('name')->toArray();
                if (empty($pakets)) {
                    $pakets = config('option.packet_default');
                }
                $paketList = '"' . implode(',', $pakets) . '"';

                for ($row = 2; $row <= $maxRows; $row++) {
                    $validation = $sheet->getCell("C{$row}")->getDataValidation();
                    $validation->setType(DataValidation::TYPE_LIST);
                    $validation->setErrorStyle(DataValidation::STYLE_STOP);
                    $validation->setAllowBlank(true);
                    $validation->setShowDropDown(true);
                    $validation->setFormula1($paketList);
                    $validation->setErrorTitle('Input Paket Salah');
                    $validation->setError('Pilih salah satu atau lebih dari daftar. Jika lebih dari satu, pisahkan dengan koma.');
                }

                /**
                 * === Dropdown Unit (1 pilihan saja) ===
                 */
                $units = config('option.units_default');
                $unitList = '"' . implode(',', $units) . '"';

                for ($row = 2; $row <= $maxRows; $row++) {
                    $validation = $sheet->getCell("G{$row}")->getDataValidation();
                    $validation->setType(DataValidation::TYPE_LIST);
                    $validation->setErrorStyle(DataValidation::STYLE_STOP);
                    $validation->setAllowBlank(false);
                    $validation->setShowDropDown(true);
                    $validation->setFormula1($unitList);
                    $validation->setErrorTitle('Input Salah');
                    $validation->setError('Pilih satuan dari daftar yang tersedia.');
                }

                /**
                 * === Styling Header ===
                 */
                $sheet->getStyle($cheader)->getFont()->setBold(true);
                $sheet->getStyle($cheader)->getAlignment()->setHorizontal('center');
                $sheet->getRowDimension(1)->setRowHeight(24);
                $sheet->freezePane('A2');

                /**
                 * === STYLING Input Area ===
                 * Memberikan border dan latar lembut agar terlihat batas input.
                 */
                $sheet->getStyle($inputRange)->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['argb' => 'FFBBBBBB'],
                        ],
                        'outline' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['argb' => 'FF000000'],
                        ],
                    ],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'color' => ['argb' => 'FFF9F9F9'],
                    ],
                ]);
                /**
                 * === AUTO WIDTH untuk setiap kolom ===
                 */
                $columnWidths = [
                    'A' => 40, // recipe_name
                    'B' => 40, // category
                    'C' => 40, // paket
                    'D' => 18, // portion_standard
                    'E' => 60, // ingredient_name
                    'F' => 10, // qty
                    'G' => 10, // unit
                    'H' => 10, // unit
                    'I' => 15, // price_per_unit
                ];
                foreach ($columnWidths as $col => $width) {
                    $sheet->getColumnDimension($col)->setWidth($width);
                }

                /**
                 * === TEKS PERINGATAN BATAS INPUT ===
                 */
                $warningRow = $rowInit + 2; // misal baris 53
                $sheet->setCellValue("A{$warningRow}", "⚠️ Batas maksimal input adalah {$rowInit} baris (A2–I{$maxRows}). Baris di bawah ini terkunci.");
                $sheet->mergeCells("A{$warningRow}:I{$warningRow}");
                $sheet->getStyle("A{$warningRow}")->getFont()->setItalic(true)->getColor()->setARGB(Color::COLOR_RED);
            },
        ];
    }
}
