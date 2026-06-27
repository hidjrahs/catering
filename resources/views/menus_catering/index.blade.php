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
    .scroll-panel{
        max-height: 130px;
        overflow-y: scroll;
    }
    .minh-100px{
        min-height: 100px;
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
                            <span class="card-label fw-bold text-dark">{!!$config['title-icon']!!}{{$config['title-content']}}</span>
                            <span class="text-gray-400 mt-1 fw-semibold fs-6">Total <span data-label="count-data">0</span> Menu</span>
                        </h3>
                        <!--end::Title-->
                        <!--begin::Actions-->
                        <div class="card-toolbar w-100 w-sm-auto">
                            <!--begin::Filters-->
                            <div class="d-flex flex-stack flex-wrap flex-sm-row gap-2 w-100 w-sm-auto">
                                <!--begin::Search-->
                                <!-- <div class="position-relative my-1 w-100 w-sm-auto"> -->
                                <div class="d-flex align-items-center w-100 w-sm-auto ">
                                    <a href="javascript:;" class="btn btn-sm btn-light me-3 px-2 disabled" id="deleted-check" title="Centang untuk menghapus data.">
                                        <span class="svg-icon svg-icon-2 me-0">
                                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M5 9C5 8.44772 5.44772 8 6 8H18C18.5523 8 19 8.44772 19 9V18C19 19.6569 17.6569 21 16 21H8C6.34315 21 5 19.6569 5 18V9Z" fill="currentColor"/>
                                                <path opacity="0.5" d="M5 5C5 4.44772 5.44772 4 6 4H18C18.5523 4 19 4.44772 19 5V5C19 5.55228 18.5523 6 18 6H6C5.44772 6 5 5.55228 5 5V5Z" fill="currentColor"/>
                                                <path opacity="0.5" d="M9 4C9 3.44772 9.44772 3 10 3H14C14.5523 3 15 3.44772 15 4V4H9V4Z" fill="currentColor"/>
                                            </svg> 
                                        </span>
                                    </a>
                                    <div class="position-relative flex-fill">
                                        <!--begin::Svg Icon | path: icons/duotune/general/gen021.svg-->
                                        <span class="svg-icon svg-icon-2 position-absolute top-50 translate-middle-y ms-4">
                                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <rect opacity="0.5" x="17.0365" y="15.1223" width="8.15546" height="2" rx="1" transform="rotate(45 17.0365 15.1223)" fill="currentColor"></rect>
                                                <path d="M11 19C6.55556 19 3 15.4444 3 11C3 6.55556 6.55556 3 11 3C15.4444 3 19 6.55556 19 11C19 15.4444 15.4444 19 11 19ZM11 5C7.53333 5 5 7.53333 5 11C5 14.4667 7.53333 17 11 17C14.4667 17 17 14.4667 17 11C17 7.53333 14.4667 5 11 5Z" fill="currentColor"></path>
                                            </svg>
                                        </span>
                                        <!--end::Svg Icon-->
                                        <input type="text" class="form-control fs-7 ps-12 search-input" id="search" placeholder="Pencarian....">
                                    </div>
                                </div>
                                <!--end::Search-->
                            </div>
                            <!--begin::Filters-->
                        </div>
                        <!--end::Actions-->
                    </div>
                    <!--end::Card header-->
                    <!--begin::Card body-->
                    <div class="card-body p-4 min-h-300px">
                        <div class="table-responsive">
                            <!--begin::Table-->
                            <table class="table align-middle  fs-6 gy-3 gs-3 table-hover table-sm table-row-bordered " id="main-table">
                                <!--begin::Table head-->
                                <thead>
                                    <!--begin::Table row-->
                                    <tr class="fw-bold text-muted bg-light">
                                        <th class="w-25px">
                                            <div class="form-check form-check-sm form-check-custom form-check-solid">
                                                <input class="form-check-input border border-primary border-active active border-1 cursor-pointer" type="checkbox" name="row-check[]" value="all" data-kt-check="true" data-kt-check-target=".widget-main-check">
                                            </div>
                                        </th>
                                        <th class="min-w-250px">Menu</th>
                                        <th class="min-w-100px text-end">Porsi</th>
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
            <!--begin::Sidebar-->
            <div class="d-none d-lg-flex flex-column flex-lg-row-auto w-100 w-lg-600px pt-2" 
                data-kt-drawer="true" 
                data-kt-drawer-name="inbox-aside" 
                data-kt-drawer-activate="{default: true, lg: false}" 
                data-kt-drawer-overlay="true" 
                data-kt-drawer-width="600px" 
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
                        data-kt-sticky-width="{lg: '600px'}" 
                        data-kt-sticky-right="auto" 
                        data-kt-sticky-top="0px" 
                        data-kt-sticky-animation="false" 
                        data-kt-sticky-zindex="95">
                        <!--begin::Aside content-->
                        <!--begin::Card header-->
                        <div class="card-header p-4 pb-0">
                            <!--begin::Title-->
                            <h3 class="card-title align-items-start flex-column">
                                <span class="card-label fw-bold text-dark">{!!$config['title-icon']!!}Tambah Menu Baru</span>
                            </h3>
                            <!--end::Title-->
                            <!--begin::Actions-->
                            <div class="card-toolbar w-100 w-sm-auto">
                                <a href="{{route('menus_catering.import')}}" class="btn btn-sm btn-primary ms-3 px-4" title="Import Excel Resep Baru?">
                                    <i class="fa-solid fa-book"></i>Import Resep
                                </a>
                            </div>
                        </div>
                        <!--end::Card header-->
                        <div class="card-body p-4">
                            <div class="row">
                                <div class="col-6">
                                    <div class="mb-1 fv-row">
                                        <label for="name" class="required form-label">Kategori: </label>
                                        <select class="form-select form-select-solid form-select-sm" 
                                            data-placeholder="Pilih Kategori" 
                                            name="category_menus_catering_id" 
                                            data-control="select2"
                                            data-allow-clear="true"
                                            placeholder="Pilih Kategori">
                                        </select>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="mb-1 fv-row">
                                        <label for="name" class="required form-label">Nama Menu</label>
                                        <input type="text" name="name" class="form-control form-control-sm form-control-solid" maxlength="60" placeholder="Nama Menu: ....,">
                                    </div>
                                </div>
                                <div class="col-5">
                                    <div class="mb-1 fv-row">
                                        <label for="porsi_standard" class="form-label">Porsi Standard</label>
                                        <input type="text" name="porsi_standard" class="form-control form-control-sm form-control-solid" maxlength="12" placeholder="Porsi Standard: .....">
                                    </div>
                                </div>
                                <div class="col-5">
                                    <div class="mb-1 fv-row">
                                        <label for="selling_price" class="form-label">Estimasi Harga</label>
                                        <input type="text" name="selling_price" class="form-control form-control-sm form-control-solid" maxlength="10" placeholder="Est. Harga: .....">
                                    </div>
                                </div>
                                <div class="col-2">
                                    <div class="mb-1 fv-row">
                                        <label for="porsi_standard" class="form-label">Aktif</label>
                                        <label class="form-check form-switch form-check-custom form-check-solid text-center min-h-20px">
                                            <input class="form-check-input w-100" name="is_active" type="checkbox" value="1" checked="checked" />
                                        </label>
                                    </div>
                                </div>
                                <div class="separator separator-dashed my-3"></div>
                                <div class="col-12 col-sm-12 mb-2">
                                    <label class="form-label"> Daftar Barang/Bahan Baku: <span data-ref="total_ingredients">0</span> Bahan <span data-ref="total_ingredients_label"></span></label>
                                    <div class="mh-600px scroll-y" data-list="ingredient_list">
                                        <div class="border border-dashed border-gray-300 rounded p-5 py-10 text-center ingredients_none">{!!$config['title-icon']!!}Barang/Bahan Baku belum di isi</div>
                                    </div> 
                                </div>
                            </div>
                        </div>
                        <!--end::Aside content-->
                        <div class="card-footer d-grid p-4">
                            <div class="row">
                                <div class="col-12 mb-4">
                                    <div class="d-flex align-items-center w-100 w-sm-auto ">
                                        <select class="form-select form-select-solid form-select-sm w-175px me-2 unvalidate"
                                            name="type_ingredients" 
                                            data-change="type_ingredients"
                                            placeholder="Pilih Jenis">
                                            <option value="barang">Barang/Bahan Baku</option>
                                            <option value="label">Label</option>
                                        </select>
                                        <div class="position-relative flex-fill">
                                            <input type="text" name="ingredient_label" class="form-control form-control-sm form-control-solid unvalidate hidden" maxlength="36" placeholder="Label: .....">
                                            <select class="form-select form-select-solid form-select-sm unvalidate" 
                                                data-placeholder="Barang/Bahan Baku:..." 
                                                name="ingredient_item" 
                                                placeholder="Barang/Bahan Baku">
                                            </select>
                                        </div>
                                        <span class="btn btn-light-success btn-sm ms-2 px-3" data-add="ingredient_list"><i class="fas fa-plus"></i>Tambah</span>
                                    </div>
                                </div>
                                <div class="col-12 col-sm-12 mb-2 fv-row">
                                    <label for="name" class="required form-label">Paket: </label>
                                    <select class="form-select form-select-solid form-select-sm" 
                                        data-placeholder="Pilih Paket" 
                                        name="packet_catering_id[]" 
                                        data-control="select2"
                                        data-allow-clear="true"
                                        placeholder="Pilih Paket"
                                        multiple>
                                    </select>
                                    <!-- <label for="name" class="required form-label">Keterangan: </label>
                                    <textarea id="desc" name="desc" class="form-control text-gray-800 fw-bold form-control-sm mh-150px minh-100px unvalidate" placeholder="Keterangan :...">-</textarea> -->
                                </div>
                            </div>
                            <button type="submit" id="kt_form_submit" class="btn btn-primary font-weight-bold">
                                <span class="indicator-label">{{$config['button-submit']}}{!!$config['title-page-icon']!!}</span>
                                <span class="indicator-progress">{{$config['button-submit-wait']??'Tunggu sebentar'}}...
                                <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                            </button>
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
    @include('menus_catering.modal')
</div>
@endsection
@section('script')
@include('menus_catering.index_script')
@endsection