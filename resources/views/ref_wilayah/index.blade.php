@extends('layouts.app')
@section('css')
<style>
    .form-textarea-hf{
        min-height: 100px !important;
        max-height: 100px !important;
    }
    .ml-index{
        z-index: 2 !important;
    }
</style>
@endsection
@section('content')
<div id="kt_app_content" class="app-content flex-column-fluid p-0">
    <!--begin::Content container-->
    <div id="kt_app_content_container" class="container-fluid">
        <!--begin::Row-->
        <div class="d-flex flex-column flex-lg-row gap-2">
            <!--begin::Sidebar-->
            <div class="flex-fill pt-2 mb-2">
                <!--begin::Table Widget 5-->
                <div class="card card-flush mb-2" data-card="province">
                    <!--begin::Card header-->
                    <div class="card-header p-4 pb-2">
                        <!--begin::Title-->
                        <h3 class="card-title align-items-start flex-column">
                            <span class="card-label fw-bold text-dark">{!!$config['title-icon']!!} Referensi Provinsi</span>
                            <span class="text-gray-400 mt-1 fw-semibold fs-6">Total <span data-label="count-data-province">0</span> Provinsi</span>
                        </h3>
                        <!--end::Title-->
                        <!--begin::Actions-->
                        <div class="card-toolbar w-100 w-sm-auto">
                            <!--begin::Filters-->
                            <div class="d-flex flex-stack flex-wrap flex-sm-row gap-2 w-100 w-sm-auto">
                                <!--begin::Search-->
                                <!-- <div class="position-relative my-1 w-100 w-sm-auto"> -->
                                <div class="d-flex align-items-center w-100 w-sm-auto ">
                                    <div class="position-relative flex-fill">
                                        <!--begin::Svg Icon | path: icons/duotune/general/gen021.svg-->
                                        <span class="svg-icon svg-icon-2 position-absolute top-50 translate-middle-y ms-4">
                                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <rect opacity="0.5" x="17.0365" y="15.1223" width="8.15546" height="2" rx="1" transform="rotate(45 17.0365 15.1223)" fill="currentColor"></rect>
                                                <path d="M11 19C6.55556 19 3 15.4444 3 11C3 6.55556 6.55556 3 11 3C15.4444 3 19 6.55556 19 11C19 15.4444 15.4444 19 11 19ZM11 5C7.53333 5 5 7.53333 5 11C5 14.4667 7.53333 17 11 17C14.4667 17 17 14.4667 17 11C17 7.53333 14.4667 5 11 5Z" fill="currentColor"></path>
                                            </svg>
                                        </span>
                                        <!--end::Svg Icon-->
                                        <input type="text" class="form-control fs-7 ps-12 search-province w-150px" id="search-province" placeholder="Pencarian....">
                                    </div>
                                    <a href="javascript:;" class="btn btn-sm btn-primary ms-3 px-2 modal-form" title="Tambah Referensi Provinsi">
                                        <i class="fa fa-plus"></i> Tambah
                                    </a>
                                    <a href="javascript:;" class="btn btn-sm btn-light ms-3 px-2 hidden disabled deleted-check" data-type="province" title="Centang untuk menghapus data.">
                                        <span class="svg-icon svg-icon-2 me-0">
                                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M5 9C5 8.44772 5.44772 8 6 8H18C18.5523 8 19 8.44772 19 9V18C19 19.6569 17.6569 21 16 21H8C6.34315 21 5 19.6569 5 18V9Z" fill="currentColor"/>
                                                <path opacity="0.5" d="M5 5C5 4.44772 5.44772 4 6 4H18C18.5523 4 19 4.44772 19 5V5C19 5.55228 18.5523 6 18 6H6C5.44772 6 5 5.55228 5 5V5Z" fill="currentColor"/>
                                                <path opacity="0.5" d="M9 4C9 3.44772 9.44772 3 10 3H14C14.5523 3 15 3.44772 15 4V4H9V4Z" fill="currentColor"/>
                                            </svg> 
                                        </span>
                                    </a>
                                </div>
                                <!--end::Search-->
                            </div>
                            <!--begin::Filters-->
                        </div>
                        <!--end::Actions-->
                    </div>
                    <!--end::Card header-->
                    <!--begin::Card body-->
                    <div class="card-body pt-0 p-4 ">
                        <div class="table-responsive">
                            <!--begin::Table-->
                            <table class="table align-middle  fs-6 gy-3 gs-3 table-hover table-sm table-row-bordered " id="main-table-province">
                                <!--begin::Table head-->
                                <thead>
                                    <!--begin::Table row-->
                                    <tr class="fw-bold text-muted bg-light">
                                        <th class="w-25px">
                                            <div class="form-check form-check-sm form-check-custom form-check-solid">
                                                <input class="form-check-input border border-primary border-active active border-1 cursor-pointer" type="checkbox" name="row-province[]" value="all" data-kt-check="true" data-kt-check-target=".widget-province-check">
                                            </div>
                                        </th>
                                        <th>Provinsi</th>
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
                </div>
                <!--end::Table Widget 5-->
                <!--begin::Table Widget 5-->
                <div class="card card-flush" data-card="cities">
                    <!--begin::Card header-->
                    <div class="card-header p-4 pb-2">
                        <!--begin::Title-->
                        <h3 class="card-title align-items-start flex-column">
                            <span class="card-label fw-bold text-dark">{!!$config['title-icon']!!} Referensi Kota/Kab</span>
                            <span class="text-gray-400 mt-1 fw-semibold fs-6">Total <span data-label="count-data-cities">0</span> Kota/Kab</span>
                        </h3>
                        <!--end::Title-->
                        <!--begin::Actions-->
                        <div class="card-toolbar w-100 w-sm-auto">
                            <!--begin::Filters-->
                            <div class="d-flex flex-stack flex-wrap flex-sm-row gap-2 w-100 w-sm-auto">
                                <!--begin::Search-->
                                <!-- <div class="position-relative my-1 w-100 w-sm-auto"> -->
                                <div class="d-flex align-items-center w-100 w-sm-auto ">
                                    <div class="position-relative flex-fill">
                                        <!--begin::Svg Icon | path: icons/duotune/general/gen021.svg-->
                                        <span class="svg-icon svg-icon-2 position-absolute top-50 translate-middle-y ms-4">
                                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <rect opacity="0.5" x="17.0365" y="15.1223" width="8.15546" height="2" rx="1" transform="rotate(45 17.0365 15.1223)" fill="currentColor"></rect>
                                                <path d="M11 19C6.55556 19 3 15.4444 3 11C3 6.55556 6.55556 3 11 3C15.4444 3 19 6.55556 19 11C19 15.4444 15.4444 19 11 19ZM11 5C7.53333 5 5 7.53333 5 11C5 14.4667 7.53333 17 11 17C14.4667 17 17 14.4667 17 11C17 7.53333 14.4667 5 11 5Z" fill="currentColor"></path>
                                            </svg>
                                        </span>
                                        <!--end::Svg Icon-->
                                        <input type="text" class="form-control fs-7 ps-12 search-cities w-150px" id="search-cities" placeholder="Pencarian....">
                                    </div>
                                    <a href="javascript:;" class="btn btn-sm btn-primary ms-3 px-2 modal-form" title="Tambah Referensi Kota/Kab">
                                        <i class="fa fa-plus"></i> Tambah
                                    </a>
                                    <a href="javascript:;" class="btn btn-sm btn-light ms-3 px-2 hidden disabled deleted-check" data-type="cities" title="Centang untuk menghapus data.">
                                        <span class="svg-icon svg-icon-2 me-0">
                                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M5 9C5 8.44772 5.44772 8 6 8H18C18.5523 8 19 8.44772 19 9V18C19 19.6569 17.6569 21 16 21H8C6.34315 21 5 19.6569 5 18V9Z" fill="currentColor"/>
                                                <path opacity="0.5" d="M5 5C5 4.44772 5.44772 4 6 4H18C18.5523 4 19 4.44772 19 5V5C19 5.55228 18.5523 6 18 6H6C5.44772 6 5 5.55228 5 5V5Z" fill="currentColor"/>
                                                <path opacity="0.5" d="M9 4C9 3.44772 9.44772 3 10 3H14C14.5523 3 15 3.44772 15 4V4H9V4Z" fill="currentColor"/>
                                            </svg> 
                                        </span>
                                    </a>
                                </div>
                                <!--end::Search-->
                            </div>
                            <!--begin::Filters-->
                        </div>
                        <!--end::Actions-->
                    </div>
                    <!--end::Card header-->
                    <!--begin::Card body-->
                    <div class="card-body pt-0 p-4 ">
                        <div class="table-responsive">
                            <!--begin::Table-->
                            <table class="table align-middle  fs-6 gy-3 gs-3 table-hover table-sm table-row-bordered " id="main-table-cities">
                                <!--begin::Table head-->
                                <thead>
                                    <!--begin::Table row-->
                                    <tr class="fw-bold text-muted bg-light">
                                        <th class="w-25px">
                                            <div class="form-check form-check-sm form-check-custom form-check-solid">
                                                <input class="form-check-input border border-primary border-active active border-1 cursor-pointer" type="checkbox" name="row-cities[]" value="all" data-kt-check="true" data-kt-check-target=".widget-cities-check">
                                            </div>
                                        </th>
                                        <th>Kota/Kab</th>
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
                </div>
                <!--end::Table Widget 5-->
            </div>
            <!--end::Sidebar-->
            <!--begin::Col-->
            <div class="flex-fill mb-2 pt-2 ">
                <!--begin::Table Widget 5-->
                <div class="card card-flush mb-2" data-card="districts">
                    <!--begin::Card header-->
                    <div class="card-header p-4 pb-2">
                        <!--begin::Title-->
                        <h3 class="card-title align-items-start flex-column">
                            <span class="card-label fw-bold text-dark">{!!$config['title-icon']!!} Referensi Kecamatan</span>
                            <span class="text-gray-400 mt-1 fw-semibold fs-6">Total <span data-label="count-data-districts">0</span> Kecamatan</span>
                        </h3>
                        <!--end::Title-->
                        <!--begin::Actions-->
                        <div class="card-toolbar w-100 w-sm-auto">
                            <!--begin::Filters-->
                            <div class="d-flex flex-stack flex-wrap flex-sm-row gap-2 w-100 w-sm-auto">
                                <!--begin::Search-->
                                <!-- <div class="position-relative my-1 w-100 w-sm-auto"> -->
                                <div class="d-flex align-items-center w-100 w-sm-auto ">
                                    <div class="position-relative flex-fill">
                                        <!--begin::Svg Icon | path: icons/duotune/general/gen021.svg-->
                                        <span class="svg-icon svg-icon-2 position-absolute top-50 translate-middle-y ms-4">
                                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <rect opacity="0.5" x="17.0365" y="15.1223" width="8.15546" height="2" rx="1" transform="rotate(45 17.0365 15.1223)" fill="currentColor"></rect>
                                                <path d="M11 19C6.55556 19 3 15.4444 3 11C3 6.55556 6.55556 3 11 3C15.4444 3 19 6.55556 19 11C19 15.4444 15.4444 19 11 19ZM11 5C7.53333 5 5 7.53333 5 11C5 14.4667 7.53333 17 11 17C14.4667 17 17 14.4667 17 11C17 7.53333 14.4667 5 11 5Z" fill="currentColor"></path>
                                            </svg>
                                        </span>
                                        <!--end::Svg Icon-->
                                        <input type="text" class="form-control fs-7 ps-12 search-districts w-150px" id="search-districts" placeholder="Pencarian....">
                                    </div>
                                    <a href="javascript:;" class="btn btn-sm btn-primary ms-3 px-2 modal-form" title="Tambah Referensi Kecamatan">
                                        <i class="fa fa-plus"></i> Tambah
                                    </a>
                                    <a href="javascript:;" class="btn btn-sm btn-light ms-3 px-2 hidden disabled deleted-check" data-type="districts" title="Centang untuk menghapus data.">
                                        <span class="svg-icon svg-icon-2 me-0">
                                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M5 9C5 8.44772 5.44772 8 6 8H18C18.5523 8 19 8.44772 19 9V18C19 19.6569 17.6569 21 16 21H8C6.34315 21 5 19.6569 5 18V9Z" fill="currentColor"/>
                                                <path opacity="0.5" d="M5 5C5 4.44772 5.44772 4 6 4H18C18.5523 4 19 4.44772 19 5V5C19 5.55228 18.5523 6 18 6H6C5.44772 6 5 5.55228 5 5V5Z" fill="currentColor"/>
                                                <path opacity="0.5" d="M9 4C9 3.44772 9.44772 3 10 3H14C14.5523 3 15 3.44772 15 4V4H9V4Z" fill="currentColor"/>
                                            </svg> 
                                        </span>
                                    </a>
                                </div>
                                <!--end::Search-->
                            </div>
                            <!--begin::Filters-->
                        </div>
                        <!--end::Actions-->
                    </div>
                    <!--end::Card header-->
                    <!--begin::Card body-->
                    <div class="card-body pt-0 p-4 ">
                        <div class="table-responsive">
                            <!--begin::Table-->
                            <table class="table align-middle  fs-6 gy-3 gs-3 table-hover table-sm table-row-bordered " id="main-table-districts">
                                <!--begin::Table head-->
                                <thead>
                                    <!--begin::Table row-->
                                    <tr class="fw-bold text-muted bg-light">
                                        <th class="w-25px">
                                            <div class="form-check form-check-sm form-check-custom form-check-solid">
                                                <input class="form-check-input border border-primary border-active active border-1 cursor-pointer" type="checkbox" name="row-districts[]" value="all" data-kt-check="true" data-kt-check-target=".widget-districts-check">
                                            </div>
                                        </th>
                                        <th>Kota/Kab</th>
                                        <th>Kecamatan</th>
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
                </div>
                <!--end::Table Widget 5-->
                <!--begin::Table Widget 5-->
                <div class="card card-flush" data-card="vilages">
                    <!--begin::Card header-->
                    <div class="card-header p-4 pb-2">
                        <!--begin::Title-->
                        <h3 class="card-title align-items-start flex-column">
                            <span class="card-label fw-bold text-dark">{!!$config['title-icon']!!} Referensi Desa/Kelurahan</span>
                            <span class="text-gray-400 mt-1 fw-semibold fs-6">Total <span data-label="count-data-vilages">0</span> Desa/Kelurahan</span>
                        </h3>
                        <!--end::Title-->
                        <!--begin::Actions-->
                        <div class="card-toolbar w-100 w-sm-auto">
                            <!--begin::Filters-->
                            <div class="d-flex flex-stack flex-wrap flex-sm-row gap-2 w-100 w-sm-auto">
                                <!--begin::Search-->
                                <!-- <div class="position-relative my-1 w-100 w-sm-auto"> -->
                                <div class="d-flex align-items-center w-100 w-sm-auto ">
                                    <div class="position-relative flex-fill">
                                        <!--begin::Svg Icon | path: icons/duotune/general/gen021.svg-->
                                        <span class="svg-icon svg-icon-2 position-absolute top-50 translate-middle-y ms-4">
                                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <rect opacity="0.5" x="17.0365" y="15.1223" width="8.15546" height="2" rx="1" transform="rotate(45 17.0365 15.1223)" fill="currentColor"></rect>
                                                <path d="M11 19C6.55556 19 3 15.4444 3 11C3 6.55556 6.55556 3 11 3C15.4444 3 19 6.55556 19 11C19 15.4444 15.4444 19 11 19ZM11 5C7.53333 5 5 7.53333 5 11C5 14.4667 7.53333 17 11 17C14.4667 17 17 14.4667 17 11C17 7.53333 14.4667 5 11 5Z" fill="currentColor"></path>
                                            </svg>
                                        </span>
                                        <!--end::Svg Icon-->
                                        <input type="text" class="form-control fs-7 ps-12 search-vilages w-150px" id="search-vilages" placeholder="Pencarian....">
                                    </div>
                                    <a href="javascript:;" class="btn btn-sm btn-primary ms-3 px-2 modal-form" title="Tambah Referensi Desa/Kelurahan">
                                        <i class="fa fa-plus"></i> Tambah
                                    </a>
                                    <a href="javascript:;" class="btn btn-sm btn-light ms-3 px-2 hidden disabled deleted-check" data-type="vilages" title="Centang untuk menghapus data.">
                                        <span class="svg-icon svg-icon-2 me-0">
                                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M5 9C5 8.44772 5.44772 8 6 8H18C18.5523 8 19 8.44772 19 9V18C19 19.6569 17.6569 21 16 21H8C6.34315 21 5 19.6569 5 18V9Z" fill="currentColor"/>
                                                <path opacity="0.5" d="M5 5C5 4.44772 5.44772 4 6 4H18C18.5523 4 19 4.44772 19 5V5C19 5.55228 18.5523 6 18 6H6C5.44772 6 5 5.55228 5 5V5Z" fill="currentColor"/>
                                                <path opacity="0.5" d="M9 4C9 3.44772 9.44772 3 10 3H14C14.5523 3 15 3.44772 15 4V4H9V4Z" fill="currentColor"/>
                                            </svg> 
                                        </span>
                                    </a>
                                </div>
                                <!--end::Search-->
                            </div>
                            <!--begin::Filters-->
                        </div>
                        <!--end::Actions-->
                    </div>
                    <!--end::Card header-->
                    <!--begin::Card body-->
                    <div class="card-body pt-0 p-4 ">
                        <div class="table-responsive">
                            <!--begin::Table-->
                            <table class="table align-middle  fs-6 gy-3 gs-3 table-hover table-sm table-row-bordered " id="main-table-vilages">
                                <!--begin::Table head-->
                                <thead>
                                    <!--begin::Table row-->
                                    <tr class="fw-bold text-muted bg-light">
                                        <th class="w-25px">
                                            <div class="form-check form-check-sm form-check-custom form-check-solid">
                                                <input class="form-check-input border border-primary border-active active border-1 cursor-pointer" type="checkbox" name="row-vilages[]" value="all" data-kt-check="true" data-kt-check-target=".widget-vilages-check">
                                            </div>
                                        </th>
                                        <th>Kota/Kab</th>
                                        <th>Desa/Kelurahan</th>
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
                </div>
                <!--end::Table Widget 5-->
            </div>
            <!--end::Col-->
        </div>
        <!--end::Row-->
    </div>
    <!--end::Content container-->
    @include('ref_wilayah.modal')
</div>
@endsection
@section('script')
@include('ref_wilayah.index_script')
@endsection