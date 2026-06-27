<?php

namespace App\Http\Controllers;

use App\Repository\MenuRepository;
use App\Traits\IconComponent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class PanduanController extends Controller
{
    use IconComponent;

    /**
     * Struktur panduan: folder => [label, icon, file => label]
     */
    private function getStrukturPanduan(): array
    {
        return [
            '00_login_dan_umum' => [
                'label'  => 'Login & Umum',
                'icon'   => '🔐',
                'role'   => 'Semua Pengguna',
                'desc'   => 'Cara login, navigasi, dan penggunaan umum aplikasi.',
                'files'  => [
                    'panduan_login' => 'Panduan Login & Penggunaan Umum',
                ],
            ],
            '01_customer_service' => [
                'label'  => 'Customer Service',
                'icon'   => '📋',
                'role'   => 'customer_service',
                'desc'   => 'Panduan menerima dan menginput pesanan pelanggan ke sistem.',
                'files'  => [
                    'panduan_cs'           => 'Panduan Lengkap Customer Service',
                    'checklist_pesanan_baru' => 'Checklist Membuat Pesanan Baru',
                ],
            ],
            '02_cost_controlling' => [
                'label'  => 'Cost Controlling',
                'icon'   => '💰',
                'role'   => 'cost_control',
                'desc'   => 'Panduan verifikasi dan persetujuan estimasi biaya pesanan.',
                'files'  => [
                    'panduan_cc'         => 'Panduan Lengkap Cost Controlling',
                    'checklist_verifikasi' => 'Checklist Verifikasi Pesanan',
                ],
            ],
            '03_kitchen' => [
                'label'  => 'Kitchen (Dapur)',
                'icon'   => '🍳',
                'role'   => 'kitchen',
                'desc'   => 'Panduan melihat tugas memasak dan mencetak resep dapur.',
                'files'  => [
                    'panduan_kitchen' => 'Panduan Tim Dapur',
                ],
            ],
            '04_purchasing' => [
                'label'  => 'Purchasing',
                'icon'   => '🛒',
                'role'   => 'purchasing',
                'desc'   => 'Panduan pembelian bahan baku dan pembuatan Purchase Order.',
                'files'  => [
                    'panduan_purchasing' => 'Panduan Lengkap Purchasing',
                    'checklist_po'       => 'Checklist Purchase Order',
                ],
            ],
            '05_admin_super_admin' => [
                'label'  => 'Admin & Super Admin',
                'icon'   => '⚙️',
                'role'   => 'admin / super_admin',
                'desc'   => 'Panduan pengelolaan pengguna, master data, dan pengaturan sistem.',
                'files'  => [
                    'panduan_admin' => 'Panduan Admin & Super Admin',
                ],
            ],
        ];
    }

    /**
     * Halaman daftar panduan
     */
    public function index(Request $request)
    {
        $config = [
            'title'         => 'Panduan Pengguna',
            'title-content' => 'Panduan Penggunaan Aplikasi',
            'title-icon'    => self::MenuList('data-master', 'me-2'),
            'menu-sidebar'  => MenuRepository::getAllSideBar($request),
        ];

        $data = [
            'struktur' => $this->getStrukturPanduan(),
        ];

        return view('panduan.index', compact('config', 'data'));
    }

    /**
     * Menampilkan konten file markdown panduan
     */
    public function show(Request $request, string $folder, string $file)
    {
        $struktur = $this->getStrukturPanduan();

        // Validasi folder dan file yang diizinkan
        abort_unless(isset($struktur[$folder]), 404);
        abort_unless(isset($struktur[$folder]['files'][$file]), 404);

        $basePath  = base_path("panduan/{$folder}/{$file}.md");
        abort_unless(File::exists($basePath), 404);

        $markdown   = File::get($basePath);
        $folderInfo = $struktur[$folder];
        $fileLabel  = $folderInfo['files'][$file];

        $config = [
            'title'         => $fileLabel,
            'title-content' => $fileLabel,
            'title-icon'    => self::MenuList('data-master', 'me-2'),
            'menu-sidebar'  => MenuRepository::getAllSideBar($request),
        ];

        $data = [
            'markdown'    => $markdown,
            'fileLabel'   => $fileLabel,
            'folderInfo'  => $folderInfo,
            'folder'      => $folder,
            'file'        => $file,
            'struktur'    => $struktur,
        ];

        return view('panduan.show', compact('config', 'data'));
    }
}
