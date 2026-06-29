@extends('layouts.app')
@section('css')
<style>
    .table-nowrap{
        white-space: nowrap;
    }
    .table-active tr.active{
        --kt-bg-rgb-color: var(--kt-info-rgb);
        background-color: var(--kt-info) !important;
        color: var(--kt-info-inverse) !important;
    }
</style>
@endsection
@section('content')
<div id="kt_app_content" class="app-content flex-column-fluid pb-0">
    <!--begin::Content container-->
    <div id="kt_app_content_container" class="container-fluid">
        <!--begin::Row-->
        <div class="d-flex flex-column flex-lg-row">
        <!-- <div class="row gy-5 g-xl-10"> -->
            <!--begin::Col-->
            <div class="flex-lg-row-fluid me-lg-2 me-xl-4 mb-4">
            <!-- <div class="col-xl-8 mb-7"> -->
                <!--begin::Table Widget 5-->
                <div class="card h-xl-100 mt-2">
                    <!--begin::Card header-->
                    <div class="card-header p-4 pb-0">
                        <!--begin::Title-->
                        <h3 class="card-title align-items-start flex-column">
                            <span class="card-label fw-bold text-dark">{!!$config['title-icon']!!}{{$config['title-content']}}<span id="label-preview"></span></span>
                        </h3>
                        <!--end::Title-->
                        <div class="card-toolbar w-100 w-sm-auto">
                            <a href="{{route('menus_catering')}}" class="btn btn-sm btn-primary ms-3 px-4" title="Daftar Riwayat Menu.">
                                <i class="fa-solid fa-arrow-left"></i> Daftar Menu
                            </a>
                            <button type="button" id="kt_run_queue" class="btn btn-sm btn-success ms-3 px-4" title="Jalankan Queue Import">
                                <i class="fa-solid fa-play"></i> Jalankan Queue Import
                            </button>
                        </div>
                    </div>
                    <!--end::Card header-->
                    <!--begin::Card body-->
                    <div class="card-body p-4 py-2 min-h-350px">
                        <div class="table-responsive">
                            <!--begin::Table-->
                            <table class="table align-middle  fs-6 gy-3 gs-3 table-sm table-row-bordered " id="main-table">
                                <!--begin::Table head-->
                                <thead>
                                    <!--begin::Table row-->
                                    <tr class="fw-bold text-muted bg-light">
                                        <th class="w-25px">-</th>
                                        <th class="min-w-100px">Menu</th>
                                        <th class="min-w-100px">Kategori</th>
                                        <th class="min-w-200px text-center">Paket</th>
                                        <th class="w-125px text-end">Porsi Standard</th>
                                    </tr>
                                    <!--end::Table row-->
                                </thead>
                                <!--end::Table head-->
                                <!--begin::Table body-->
                                <tbody class="fw-bold text-gray-600"></tbody>
                                <!--end::Table body-->
                            </table>
                            <!--end::Table-->
                        </div>
                    </div>
                    <!--end::Card body-->
                    <div class="card-footer p-4">
                        <form action="{{$config['action-import']}}" method="post" enctype="multipart/form-data"id="kt_import_main" autocomplete="off">
                            @csrf
                            @method($config['method'])
                            <div class="d-flex justify-content-end flex-wrap">
                                <input type="hidden" name="idTemp">
                                <button type="submit" id="kt_import_submit" class="btn btn-primary font-weight-bold hidden" disabled="disabled">
                                    <span class="indicator-label">{{$config['button-import']}}{!!$config['title-page-icon']!!}</span>
                                    <span class="indicator-progress">{{$config['button-submit-wait']??'Tunggu sebentar'}}...
                                    <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
                <!--end::Table Widget 5-->
            </div>
            <!--end::Col-->
            <!--begin::Sidebar-->
            <div class="d-none d-lg-flex flex-column flex-lg-row-auto w-100 w-lg-400px pt-2 mb-2" 
                data-kt-drawer="true" 
                data-kt-drawer-name="inbox-aside" 
                data-kt-drawer-activate="{default: true, lg: false}" 
                data-kt-drawer-overlay="true" 
                data-kt-drawer-width="400px" 
                data-kt-drawer-direction="start" 
                data-kt-drawer-toggle="#kt_inbox_aside_toggle">
                <form action="{{$config['action']}}" method="post" enctype="multipart/form-data"id="kt_form_main" autocomplete="off">
                    @csrf
                    @method($config['method'])
                    <!--begin::Sticky aside-->
                    <div class="card mb-0" 
                        data-kt-sticky="true" 
                        data-kt-sticky-name="inbox-aside-sticky" 
                        data-kt-sticky-offset="{default: false, xl: '85px'}" 
                        data-kt-sticky-width="{lg: '400px'}" 
                        data-kt-sticky-right="auto" 
                        data-kt-sticky-top="0px" 
                        data-kt-sticky-animation="false" 
                        data-kt-sticky-zindex="95">
                        <!--begin::Aside content-->
                        <!--begin::Card header-->
                        <div class="card-header p-4 pb-0">
                            <!--begin::Title-->
                            <h3 class="card-title align-items-start flex-column">
                                <span class="card-label fw-bold text-dark">{!!$config['title-icon']!!}Upload Excel Resep</span>
                            </h3>
                            <!--end::Title-->
                        </div>
                        <!--end::Card header-->
                        <div class="card-body p-4">
                            <div class="row">
                                <div class="col-12">
                                    <div class="notice bg-light-primary rounded border-primary border border-dashed ps-0 p-4 mb-0">
                                        <ul>
                                            <li class="fs-6 text-gray-700">File harus berformat Excel <b>[xlsx, xls]</b>.</li>
                                            <li class="fs-6 text-gray-700">Pastikan format Excel sesuai.
                                                [ <a href="{{$config['download-template']??'javascript:;'}}"><i>Download Format</i></a> ]
                                            </li>
                                            <li class="fs-6 text-gray-700">Format Excel harus menggunakan format yang sudah sediakan yg bisa di download pada tombol "donwload Format".</li>
                                        </ul>
                                    </div>
                                    <div class="separator my-3"></div>
                                    <div class="mb-1 fv-row">
                                        <label for="name" class="required form-label">Excel Resep: </label>
                                        <div class="input-group input-group-sm">
                                            <input type="file" name="file" class="form-control form-control-sm" placeholder="Excel Resep"  accept=".xlsx, .xls">
                                        </div>
                                    </div>
                                    <div class="mb-1 fv-row">
                                        <div class="d-flex align-items-center">
                                            <label class="form-label me-3 mb-0 ">Preview Excel:</label>
                                            <label class="form-check form-switch form-check-custom form-check-solid text-end min-h-20px">
                                                <input class="form-check-input" name="is_preview" type="checkbox" value="1" checked="checked" />
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 d-grid mt-3">
                                    <button type="submit" id="kt_form_submit" class="btn btn-primary font-weight-bold">
                                        <span class="indicator-label">{{$config['button-submit']}}{!!$config['title-page-icon']!!}</span>
                                        <span class="indicator-progress">{{$config['button-submit-wait']??'Tunggu sebentar'}}...
                                        <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <!--end::Aside content-->
                        <div class="card-footer d-grid p-4">
                            <!-- begin::table -->
                            <div class="table-responsive">
                                <table class="table align-middle gs-0 gy-4 table-hover table-sm table-row-bordered table-active"  id="list-import">
                                    <thead>
                                        <tr class="fw-bold text-light-light bg-light ">
                                            <th class="ps-4 text-center table-number rounded-start w-25px">No</th>
                                            <th>Berkas</th>
                                            <th class="text-center w-75px">-</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>
                            <!-- end::table -->
                        </div>
                    </div>
                    <!--end::Sticky aside-->
                </form>

            </div>
            <!--end::Sidebar-->
        </div>
        <!--end::Row-->
    </div>
    <!--end::Content container-->
</div>
@endsection
@section('script')
@include('menus_catering.import_script')
@endsection