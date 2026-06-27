@extends('layouts.app')

@section('css')
<style>
    .panduan-hero {
        background: linear-gradient(135deg, #1e3a5f 0%, #0d7a5f 100%);
        border-radius: 16px;
        padding: 40px 40px 48px;
        position: relative;
        overflow: hidden;
        margin-bottom: 0;
    }
    .panduan-hero::before {
        content: '';
        position: absolute;
        top: -40px; right: -40px;
        width: 220px; height: 220px;
        border-radius: 50%;
        background: rgba(255,255,255,0.06);
    }
    .panduan-hero::after {
        content: '';
        position: absolute;
        bottom: -60px; left: 30%;
        width: 300px; height: 300px;
        border-radius: 50%;
        background: rgba(255,255,255,0.04);
    }
    .panduan-hero h1 { color: #fff; font-size: 2rem; font-weight: 800; margin-bottom: 8px; }
    .panduan-hero p  { color: rgba(255,255,255,0.75); font-size: 1rem; margin: 0; }
    .hero-badge {
        display: inline-flex; align-items: center; gap: 6px;
        background: rgba(255,255,255,0.15);
        color: #fff; border-radius: 30px;
        padding: 4px 14px; font-size: 0.78rem; font-weight: 600;
        margin-bottom: 16px;
    }
    .alur-step {
        display: flex; flex-direction: column; align-items: center;
        flex: 1; min-width: 120px;
    }
    .alur-step .step-icon {
        width: 52px; height: 52px; border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.4rem;
        background: #f1f8ff;
        border: 2px solid #e0ecff;
        position: relative;
        z-index: 1;
    }
    .alur-step .step-label { font-size: 0.78rem; font-weight: 600; color: #5e6278; margin-top: 8px; text-align: center; }
    .alur-arrow { color: #a1aab9; font-size: 1.3rem; margin-top: 14px; flex-shrink: 0; }
    .panduan-card {
        border: 1.5px solid #e9ecef;
        border-radius: 14px;
        transition: all .22s ease;
        cursor: pointer;
        height: 100%;
        overflow: hidden;
    }
    .panduan-card:hover {
        border-color: #009ef7;
        box-shadow: 0 8px 30px rgba(0,158,247,.13);
        transform: translateY(-3px);
    }
    .panduan-card .card-header-custom {
        background: linear-gradient(135deg, #f8f9fa, #eef2ff);
        padding: 20px 22px 16px;
        border-bottom: 1px solid #e9ecef;
    }
    .panduan-card .role-badge {
        display: inline-block;
        background: #e8f4ff; color: #1a7fd4;
        border-radius: 20px; padding: 2px 10px;
        font-size: 0.72rem; font-weight: 700;
        letter-spacing: .3px;
    }
    .panduan-card .icon-big { font-size: 2.2rem; line-height: 1; }
    .file-item {
        display: flex; align-items: center;
        padding: 9px 22px;
        border-bottom: 1px solid #f4f5f7;
        transition: background .15s;
        text-decoration: none;
        color: inherit;
    }
    .file-item:last-child { border-bottom: none; }
    .file-item:hover { background: #f5f8ff; }
    .file-item .file-dot {
        width: 7px; height: 7px; border-radius: 50%;
        background: #009ef7; flex-shrink: 0; margin-right: 10px;
    }
    .file-item .file-name { font-size: 0.85rem; font-weight: 500; color: #3f4254; flex: 1; }
    .file-item .file-arrow { color: #b5b5c3; font-size: 0.8rem; }
    .section-label {
        font-size: 0.72rem; font-weight: 700; letter-spacing: 1.2px;
        text-transform: uppercase; color: #a1aab9;
        padding: 0 0 8px; margin-bottom: 4px;
    }
</style>
@endsection

@section('content')
<div id="kt_app_content" class="app-content flex-column-fluid pb-5">
    <div id="kt_app_content_container" class="container-fluid">

        {{-- Hero Banner --}}
        <div class="panduan-hero mt-3 mb-6">
            <div class="hero-badge">
                <span>📚</span> Dokumentasi Pengguna
            </div>
            <h1>Panduan Penggunaan Aplikasi</h1>
            <p>Panduan lengkap penggunaan <strong style="color:#fff">Lila Catering Management System</strong> untuk setiap peran pengguna.</p>
        </div>

        {{-- Alur Bisnis --}}
        <div class="card mb-5">
            <div class="card-body py-5 px-6">
                <div class="section-label">Alur Bisnis Pesanan</div>
                <div class="d-flex align-items-start flex-wrap gap-2">
                    @php
                        $alurSteps = [
                            ['icon' => '📋', 'label' => 'Customer Service', 'sub' => 'Input Pesanan', 'color' => '#eef8ff'],
                            ['icon' => '💰', 'label' => 'Cost Controlling', 'sub' => 'Verifikasi Biaya', 'color' => '#fff8ee'],
                            ['icon' => '🍳', 'label' => 'Kitchen', 'sub' => 'Siapkan Masakan', 'color' => '#eeffee'],
                            ['icon' => '🛒', 'label' => 'Purchasing', 'sub' => 'Beli Bahan Baku', 'color' => '#fff0f0'],
                        ];
                    @endphp
                    @foreach($alurSteps as $i => $step)
                        <div class="alur-step">
                            <div class="step-icon" style="background: {{ $step['color'] }}">{{ $step['icon'] }}</div>
                            <div class="step-label">{{ $step['label'] }}</div>
                            <small class="text-muted" style="font-size:.72rem">{{ $step['sub'] }}</small>
                        </div>
                        @if(!$loop->last)
                            <div class="alur-arrow">›</div>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Kartu Panduan --}}
        <div class="row g-4">
            @foreach($data['struktur'] as $folderKey => $folder)
            <div class="col-12 col-md-6 col-xl-4">
                <div class="panduan-card card h-100">
                    <div class="card-header-custom">
                        <div class="d-flex align-items-center gap-3">
                            <span class="icon-big">{{ $folder['icon'] }}</span>
                            <div>
                                <div class="fw-bold text-dark fs-6 mb-1">{{ $folder['label'] }}</div>
                                <span class="role-badge">{{ $folder['role'] }}</span>
                            </div>
                        </div>
                        <p class="text-muted mt-2 mb-0" style="font-size:.83rem">{{ $folder['desc'] }}</p>
                    </div>
                    <div class="card-body p-0">
                        @foreach($folder['files'] as $fileKey => $fileLabel)
                        <a href="{{ route('panduan.show', ['folder' => $folderKey, 'file' => $fileKey]) }}"
                           class="file-item">
                            <span class="file-dot"></span>
                            <span class="file-name">{{ $fileLabel }}</span>
                            <span class="file-arrow">›</span>
                        </a>
                        @endforeach
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        {{-- Info Teknis --}}
        <div class="card mt-5">
            <div class="card-body py-4 px-6">
                <div class="row align-items-center">
                    <div class="col-auto">
                        <span style="font-size:2rem">ℹ️</span>
                    </div>
                    <div class="col">
                        <div class="fw-bold text-dark mb-1">Informasi Akses</div>
                        <div class="text-muted" style="font-size:.85rem">
                            Akses aplikasi melalui <strong>http://localhost:8008</strong> &nbsp;|&nbsp;
                            Beberapa role memerlukan <strong>koneksi VPN</strong> &nbsp;|&nbsp;
                            Sesi otomatis berakhir setelah <strong>120 menit</strong> tidak aktif
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
