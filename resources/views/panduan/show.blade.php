@extends('layouts.app')

@section('css')
{{-- Markdown parser dari CDN (marked.js) --}}
<script src="https://cdn.jsdelivr.net/npm/marked/marked.min.js"></script>
<style>
    /* Layout dua kolom */
    .panduan-layout {
        display: flex;
        gap: 20px;
        align-items: flex-start;
    }
    /* Sidebar navigasi panduan */
    .panduan-sidebar {
        width: 270px;
        flex-shrink: 0;
        position: sticky;
        top: 80px;
    }
    .panduan-sidebar .nav-folder {
        font-size: 0.72rem; font-weight: 800;
        text-transform: uppercase; letter-spacing: 1.1px;
        color: #a1aab9; padding: 10px 14px 4px;
    }
    .panduan-sidebar .nav-file {
        display: flex; align-items: center; gap-8px;
        padding: 7px 14px; border-radius: 8px;
        font-size: 0.83rem; font-weight: 500;
        color: #3f4254; text-decoration: none;
        transition: all .15s;
        margin-bottom: 2px;
    }
    .panduan-sidebar .nav-file:hover { background: #f5f8ff; color: #009ef7; }
    .panduan-sidebar .nav-file.active { background: #e8f4ff; color: #009ef7; font-weight: 700; }
    .panduan-sidebar .nav-file .dot {
        width: 6px; height: 6px; border-radius: 50%;
        background: currentColor; flex-shrink: 0; margin-right: 8px;
    }
    .panduan-sidebar .nav-separator { border-top: 1px solid #f1f3f7; margin: 6px 14px; }

    /* Konten markdown */
    .panduan-content {
        flex: 1; min-width: 0;
    }
    .markdown-body {
        font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
        font-size: 0.92rem;
        line-height: 1.75;
        color: #3f4254;
    }
    .markdown-body h1 {
        font-size: 1.7rem; font-weight: 800;
        color: #181c32; border-bottom: 2px solid #e9ecef;
        padding-bottom: 12px; margin-bottom: 20px; margin-top: 0;
    }
    .markdown-body h2 {
        font-size: 1.2rem; font-weight: 700;
        color: #181c32; margin-top: 28px; margin-bottom: 12px;
        padding-left: 12px;
        border-left: 4px solid #009ef7;
    }
    .markdown-body h3 {
        font-size: 1rem; font-weight: 700;
        color: #3f4254; margin-top: 20px; margin-bottom: 10px;
    }
    .markdown-body p { margin-bottom: 14px; }
    .markdown-body ul, .markdown-body ol {
        padding-left: 22px; margin-bottom: 14px;
    }
    .markdown-body li { margin-bottom: 5px; }
    .markdown-body code {
        background: #f0f3fa; color: #d63384;
        padding: 2px 6px; border-radius: 5px;
        font-size: 0.84em; font-family: 'Courier New', monospace;
    }
    .markdown-body pre {
        background: #1e2a3a; border-radius: 10px;
        padding: 18px 20px; overflow-x: auto;
        margin-bottom: 18px;
    }
    .markdown-body pre code {
        background: transparent; color: #c9d1d9;
        font-size: 0.85rem; padding: 0;
    }
    .markdown-body table {
        width: 100%; border-collapse: collapse;
        margin-bottom: 18px; font-size: 0.86rem;
    }
    .markdown-body th {
        background: #f5f8ff; color: #181c32;
        font-weight: 700; padding: 9px 14px;
        text-align: left; border: 1px solid #e2e8f0;
    }
    .markdown-body td {
        padding: 8px 14px; border: 1px solid #e2e8f0;
        vertical-align: top;
    }
    .markdown-body tr:nth-child(even) td { background: #fafbfd; }
    .markdown-body blockquote {
        border-left: 4px solid #009ef7;
        background: #f0f9ff; margin: 0 0 16px;
        padding: 12px 18px; border-radius: 0 8px 8px 0;
        color: #1a7fd4;
    }
    .markdown-body blockquote p { margin: 0; }
    .markdown-body hr { border: none; border-top: 1.5px solid #e9ecef; margin: 24px 0; }
    /* Checklist styling */
    .markdown-body input[type="checkbox"] { margin-right: 7px; }
    .markdown-body li input[type="checkbox"] { pointer-events: none; }
    /* Breadcrumb */
    .panduan-breadcrumb { display: flex; align-items: center; gap: 6px; margin-bottom: 18px; flex-wrap: wrap; }
    .panduan-breadcrumb a { color: #009ef7; text-decoration: none; font-size: 0.83rem; }
    .panduan-breadcrumb .sep { color: #b5b5c3; font-size: 0.8rem; }
    .panduan-breadcrumb .current { color: #5e6278; font-size: 0.83rem; font-weight: 600; }
</style>
@endsection

@section('content')
<div id="kt_app_content" class="app-content flex-column-fluid pb-5">
    <div id="kt_app_content_container" class="container-fluid">

        {{-- Breadcrumb --}}
        <div class="panduan-breadcrumb mt-3 mb-3">
            <a href="{{ route('panduan.index') }}">📚 Panduan</a>
            <span class="sep">›</span>
            <span style="color:#009ef7; font-size:.83rem">{{ $data['folderInfo']['icon'] }} {{ $data['folderInfo']['label'] }}</span>
            <span class="sep">›</span>
            <span class="current">{{ $data['fileLabel'] }}</span>
        </div>

        <div class="panduan-layout">

            {{-- Sidebar Navigasi --}}
            <div class="panduan-sidebar">
                <div class="card">
                    <div class="card-body p-3">
                        <a href="{{ route('panduan.index') }}"
                           class="d-flex align-items-center gap-2 mb-3 text-decoration-none"
                           style="color:#3f4254; font-size:.83rem; font-weight:600">
                            ‹ Semua Panduan
                        </a>
                        <div class="nav-separator"></div>

                        @foreach($data['struktur'] as $folderKey => $folder)
                            <div class="nav-folder mt-2">{{ $folder['icon'] }} {{ $folder['label'] }}</div>
                            @foreach($folder['files'] as $fileKey => $fileLabel)
                                @php
                                    $isActive = ($folderKey === $data['folder'] && $fileKey === $data['file']);
                                @endphp
                                <a href="{{ route('panduan.show', ['folder' => $folderKey, 'file' => $fileKey]) }}"
                                   class="nav-file {{ $isActive ? 'active' : '' }}">
                                    <span class="dot"></span>
                                    {{ $fileLabel }}
                                </a>
                            @endforeach
                            @if(!$loop->last)
                                <div class="nav-separator mt-2"></div>
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- Konten Markdown --}}
            <div class="panduan-content">
                <div class="card">
                    <div class="card-body p-7">
                        <div class="markdown-body" id="markdown-output">
                            {{-- Konten dirender oleh JS --}}
                        </div>
                    </div>
                </div>

                {{-- Navigasi bawah --}}
                <div class="d-flex justify-content-between mt-4">
                    <a href="{{ route('panduan.index') }}" class="btn btn-light btn-sm">
                        ‹ Kembali ke Daftar Panduan
                    </a>
                    <a href="{{ route('panduan.index') }}" class="btn btn-primary btn-sm">
                        Lihat Semua Panduan
                    </a>
                </div>
            </div>

        </div>
    </div>
</div>

{{-- Raw markdown diinject ke elemen tersembunyi lalu diparse di sisi klien --}}
<textarea id="raw-markdown" style="display:none">{{ $data['markdown'] }}</textarea>
@endsection

@section('script')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const raw   = document.getElementById('raw-markdown').value;
    const output = document.getElementById('markdown-output');

    // Konfigurasi marked agar mendukung GFM (table, checklist)
    marked.setOptions({
        gfm: true,
        breaks: true,
    });

    // Render markdown → HTML
    output.innerHTML = marked.parse(raw);

    // Aktifkan checkbox visual (readonly)
    output.querySelectorAll('input[type="checkbox"]').forEach(cb => {
        cb.setAttribute('onclick', 'return false');
    });
});
</script>
@endsection
