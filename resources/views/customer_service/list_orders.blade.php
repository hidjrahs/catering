@extends('layouts.app')
@section('css')
<link rel="stylesheet" href="https://unpkg.com/multiple-select@2.2.0/dist/multiple-select.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/plugins/monthSelect/style.css">
<style>
    #order-container{
        max-height: 590px;
        overflow-y: scroll;
    }
</style>
@endsection
@section('content')
<div id="kt_app_content" class="app-content flex-column-fluid pb-0">
    <!--begin::Content container-->
    <div id="kt_app_content_container" class="container-fluid">
        <!--begin::Row-->
        <div class="d-flex flex-column flex-lg-row">
            <div class="flex-lg-row-fluid me-lg-2 mb-2">
                <div class="card mt-2">
                    <div class="card-header p-4 pb-0 min-h-50px d-flex">
                        <h3 class="card-title align-items-start flex-column card-title-sm m-0 mb-4">
                            <span class="card-label fw-bold text-dark">{!!$config['title-icon']!!} {{$config['title-content']}}</span>
                        </h3>
                        <div class="card-toolbar flex-grow-1 mb-4">
                            <!--begin::Filters-->
                            <div class="d-flex flex-stack flex-wrap flex-sm-row gap-2 w-100 justify-content-end">
                                <!--begin::Search-->
                                <!-- <div class="position-relative my-1 w-100 w-sm-auto"> -->
                                <div class="d-flex align-items-center w-100 mw-900px">
                                    <a href="javascript:;" class="btn btn-sm btn-light me-3 px-2 disabled" id="deleted-check" title="Centang untuk menghapus data.">
                                        <span class="svg-icon svg-icon-2 me-0">
                                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M5 9C5 8.44772 5.44772 8 6 8H18C18.5523 8 19 8.44772 19 9V18C19 19.6569 17.6569 21 16 21H8C6.34315 21 5 19.6569 5 18V9Z" fill="currentColor"/>
                                                <path opacity="0.5" d="M5 5C5 4.44772 5.44772 4 6 4H18C18.5523 4 19 4.44772 19 5V5C19 5.55228 18.5523 6 18 6H6C5.44772 6 5 5.55228 5 5V5Z" fill="currentColor"/>
                                                <path opacity="0.5" d="M9 4C9 3.44772 9.44772 3 10 3H14C14.5523 3 15 3.44772 15 4V4H9V4Z" fill="currentColor"/>
                                            </svg> 
                                        </span>
                                    </a>
                                    <div class="position-relative flex-fill me-3 mw-200px" >
                                        <span class="svg-icon svg-icon-2 position-absolute top-50 translate-middle-y ms-4">
                                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
                                                <path opacity="0.3" d="M5 8H19V19H5V8Z" fill="currentColor"/>
                                                <path d="M7 2V4M17 2V4M3 6H21" stroke="currentColor" stroke-width="2"/>
                                            </svg>
                                        </span>
                                        <input type="text" class="form-control fs-7 ps-12 search-input" id="filter_time" placeholder="Filter....">
                                    </div>
                                    <div class="position-relative flex-fill me-3 " >
                                        <select class="form-select form-select-solid form-select-sm search-input" name="order" id="order">
                                            <option value="order">Order</option>
                                            <option value="event">Event</option>
                                        </select>
                                    </div>
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
                                    <a href="{{route('customer_service')}}" class="btn btn-sm btn-primary ms-2 px-3" title="Riwayat Order.">
                                        {!!$config['title-icon']!!} Event Order baru
                                    </a>
                                </div>
                                <!--end::Search-->
                            </div>
                            <!--begin::Filters-->
                        </div>
                    </div>
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
                                        <th class="w-100px">No. Order</th>
                                        <th class="min-w-200px">Customer/Pelanggan</th>
                                        <th class="min-w-150px">Order</th>
                                        <th class="min-w-50px text-end">Status</th>
                                        <th class="text-end pe-3 min-w-150px">Order/Event</th>
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
                </div>
            </div>
            <div class="flex-column flex-lg-row-auto w-100 w-lg-600px pt-0 mb-2 hidden" id="order-container">
                <form action="{{$config['action']}}" method="post" id="kt_form_update" autocomplete="off">
                @csrf
                @method($config['method'])
                <input type="hidden" name="id">
                <div class="card mt-2">
                    <div class="card-header p-4 min-h-50px">
                        <h3 class="card-title align-items-start flex-column card-title-sm m-0">
                            <span class="card-label fw-bold text-dark">{!!$config['title-icon']!!}Form Edit Order</span>
                            <span data-ref="order-label" class="text-gray-400 mt-1 fw-semibold fs-7"></span>
                        </h3>
                        <div class="card-toolbar w-100 w-sm-auto m-0">
                            <div class="d-flex flex-stack flex-wrap flex-sm-row gap-2 w-100 w-sm-auto">
                                <div class="d-flex align-items-center w-100 w-sm-auto ">
                                    <a href="javascript:;" class="btn btn-sm btn-light me-2 px-3 form-close" title="tutup.">
                                        <i class="fa-solid fa-xmark"></i> Tutup
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body p-4 pb-0">
                        <div class="row">
                            <div class="col-12 col-sm-6 mb-2">
                                <div class="form-floating fv-row">
                                    <select class="form-select form-select-solid form-select-sm" 
                                        id="kt_select2_customer" 
                                        data-placeholder="Customer : ..." 
                                        name="customer_id" 
                                        data-control="select2"
                                        data-allow-clear="true"
                                        placeholder="Pilih Customer">
                                    </select>
                                    <!-- <input id="name" type="text" name="name" class="form-control text-gray-800 fw-bold form-control-sm" placeholder="Nama..."> -->
                                    <label for="name">Nama Customer</label>
                                </div>
                            </div>
                            <div class="col-12 col-sm-6 mb-2">
                                <div class="form-floating fv-row">
                                    <input id="phone" type="text" name="phone" class="form-control text-gray-800 fw-bold form-control-sm" maxlength="16" placeholder="Kontak :...">
                                    <label for="phone">Kontak</label>
                                </div>
                            </div>
                            <div class="col-12 col-sm-6 mb-2">
                                <div class="form-floating fv-row">
                                    <select class="form-select form-select-solid form-select-sm" 
                                        id="kt_select2_city" 
                                        name="city_id" 
                                        placeholder="Pilih Kota/Kab">
                                    </select>
                                    <label for="name">Kota/Kab</label>
                                </div>
                            </div>
                            <div class="col-12 col-sm-6 mb-2">
                                <div class="form-floating fv-row">
                                    <select class="form-select form-select-solid form-select-sm" 
                                        id="kt_select2_vilage" 
                                        name="vilage_id" 
                                        placeholder="Pilih Desa/Kelurahan">
                                    </select>
                                    <label for="name">Desa/Kelurahan</label>
                                </div>
                            </div>
                            <div class="col-12 col-sm-12 mb-2">
                                <textarea id="address" name="address" class="form-control text-gray-800 fw-bold form-control-sm mh-50px unvalidate" placeholder="Alamat :..."></textarea>
                            </div>
                            <div class="separator separator-dashed mb-2"></div>
                            <div class="col-12 col-sm-12 mb-2">
                                 <div class="fv-row">
                                    <label for="name" class="w-100 p-2 mb-2">Jenis Paket Menu</label>
                                    <select id="packet-id" 
                                        multiple="multiple" 
                                        placeholder="Pilih Jenis Paket Menu"
                                         class="w-100">
                                        <option>-</option>
                                        <option>----</option>
                                    </select>
                                </div>
                            </div>
                            <div class="separator separator-dashed mb-2"></div>
                            <div class="col-12 col-sm-12 mb-2">
                                <label class="form-label"> Daftar pilihan menu: <span data-ref="total_menu">0</span> Menu</label>
                                <div class="mh-600px scroll-y" id="list_order">
                                    <div class="border border-dashed border-gray-300 rounded p-5 py-10 text-center" id="item_none">{!!$config['title-icon']!!}Pilihan menu belum di isi</div>
                                </div> 
                            </div>
                            <div class="separator separator-dashed mb-2"></div>
                            <div class="col-12 mb-2">
                                <div class="d-flex align-items-center w-100 w-sm-auto ">
                                    <div class="position-relative flex-fill">
                                        <div class="form-floating fv-row">
                                            <select class="form-select form-select-solid form-select-sm unvalidate" 
                                                id="kt_select2_menu" 
                                                data-placeholder="Tambah Menu : ..." 
                                                name="menus_catering_id" 
                                                data-control="select2"
                                                data-allow-clear="true"
                                                placeholder="Tambah Menu">
                                            </select>
                                            <label for="name">Tambah order menu</label>
                                        </div>
                                    </div>
                                    <span class="btn btn-light-success btn-sm ms-2 px-3" id="add-menu"><i class="fas fa-plus"></i>Tambah</span>
                                </div>
                            </div>
                            <div class="separator separator-dashed mb-2"></div>
                            <div class="col-12 col-sm-6 mb-2">
                                <div class="form-floating fv-row">
                                    <select class="form-select form-select-solid form-select-sm" 
                                        name="package_type[]" 
                                        data-control="select2"
                                        placeholder="Tipe Pesanan" multiple>
                                        @foreach($data['package_type'] as $keyp=>$package)
                                            <option value='{{$keyp}}'>{{$package}}</option>
                                        @endforeach
                                    </select>
                                    <!-- <input id="package_type" type="text" name="package_type" class="form-control text-gray-800 fw-bold form-control-sm" placeholder="Tipe Pesanan :..."> -->
                                    <label for="package_type">Tipe Pesanan</label>
                                </div>
                            </div>
                            <div class="col-12 col-sm-6 mb-2">
                                <div class="form-floating fv-row">
                                    <select class="form-select form-select-solid form-select-sm" 
                                        name="event_type[]" 
                                        data-control="select2"
                                        placeholder="Tema Event" multiple>
                                        @foreach($data['event_type'] as $keye=>$event)
                                            <option value='{{$keye}}'>{{$event}}</option>
                                        @endforeach
                                    </select>
                                    <!-- <input id="event_type" type="text" name="event_type" class="form-control text-gray-800 fw-bold form-control-sm" placeholder="Tema Event :..."> -->
                                    <label for="event_type">Tema Event</label>
                                </div>
                            </div>
                            <div class="col-12 col-sm-4 mb-2">
                                <div class="form-floating fv-row">
                                    <input id="delivery_date" type="text" name="delivery_date" class="form-control text-gray-800 fw-bold form-control-sm" placeholder="Tgl. Pemesanan :...">
                                    <label for="delivery_date">Tgl. Pemesanan</label>
                                </div>
                            </div>
                            <div class="col-12 col-sm-4 mb-2">
                                <div class="form-floating fv-row">
                                    <input id="event_date" type="text" name="event_date" class="form-control text-gray-800 fw-bold form-control-sm" placeholder="Tgl. Acara :...">
                                    <label for="event_date">Tgl. Acara</label>
                                </div>
                            </div>
                            <div class="col-12 col-sm-4 mb-2">
                                <div class="form-floating fv-row">
                                    <input id="event_time" type="text" name="event_time" class="form-control text-gray-800 fw-bold form-control-sm" placeholder="Waktu Ready :...">
                                    <label for="event_time">Waktu Ready</label>
                                </div>
                            </div>
                            <div class="col-12 col-sm-4 mb-2">
                                <div class="form-floating fv-row">
                                    <input id="total_guest" type="text" name="total_guest" class="form-control text-gray-800 fw-bold form-control-sm" placeholder="Porsi Pesanan :...">
                                    <label for="total_guest">Porsi Pesanan</label>
                                </div>
                            </div>
                            <div class="col-12 col-sm-4 mb-2">
                                <div class="form-floating fv-row">
                                    <input id="total_invite" type="text" name="total_invite" class="form-control text-gray-800 fw-bold form-control-sm" placeholder="Jumlah Undangan :...">
                                    <label for="total_invite">Jumlah Undangan</label>
                                </div>
                            </div>
                            <div class="col-12 col-sm-8 mb-2">
                                <div class="form-floating fv-row">
                                    <input id="venue" type="text" name="venue" class="form-control text-gray-800 fw-bold form-control-sm" placeholder="Venue :...">
                                    <label for="venue">Venue</label>
                                </div>
                            </div>
                            <div class="separator separator-dashed mb-2"></div>
                            <div class="col-12 col-sm-12 mb-2 fv-row">
                                <textarea id="desc" name="desc" class="form-control text-gray-800 fw-bold form-control-sm mh-250px min-h-200px" placeholder="Keterangan :..."></textarea>
                            </div>
                            <div class="col-12 col-sm-12 mb-2 fv-row">
                                <textarea id="desc_extra" name="desc_extra" class="form-control text-gray-800 fw-bold form-control-sm mh-150px minh-100px" placeholder="History Pemesanan :..."></textarea>
                            </div>
                            <div class="col-12 col-sm-12 mb-2 fv-row">
                                 <span>&lt;br&gt; = Ganti Baris (gunakan tanda ini untuk ganti baris)</span>
                            </div>
                            <div class="separator separator-dashed mb-2"></div>
                            <div class="col-12 col-sm-12 hidden">
                                <div class="form-floating">
                                    <input type="hidden" name="estimate_price" readonly>
                                    <input id="estimate_price" type="text" name="estimate_price_label" class="form-control text-gray-800 fw-bold form-control-sm bg-light unvalidate" readonly placeholder="Estimasi Biaya :...">
                                    <label for="estimate_price">Estimasi Biaya</label>
                                </div>
                            </div>
                            <div class="col-12 d-flex justify-content-between align-items-center flex-column flex-sm-row gap-2">
                                <span class="card-label fw-bold text-dark"><i class="fa-solid fa-money-bill-wave me-2"></i>Rincian Biaya</span>
                                <div class="d-flex align-items-center">
                                    <div class="d-flex align-items-center w-100 w-sm-auto ">
                                        <a href="javascript:;" id="add-rincian" class="btn btn-sm btn-light px-3" title="Tambah Detail Rincian."><i class="fa-solid fa-square-plus"></i> Tambah Rincian</a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-sm-12 mb-2 border border-dashed border-gray-300 rounded p-2 pt-0">
                                <div class="row text-center bg-light-primary pt-2 pb-2 mb-2">
                                    <div class="col-2 ps-3">Qty</div>
                                    <div class="col-5 ps-3">Rincian</div>
                                    <div class="col-2 pe-3">Harga</div>
                                    <div class="col-2 pe-3">Total</div>
                                    <div class="col-1"></div>
                                </div>
                                <div id="list_rincian"></div>
                            </div>
                            <div class="col-12 col-sm-8">
                                <div class="form-floating">
                                    <input type="hidden" name="estimate_order" readonly>
                                    <input id="estimate_order" type="text" name="estimate_order_label" class="form-control text-gray-800 fw-bold form-control-sm bg-light unvalidate" readonly placeholder="Estimasi Biaya :...">
                                    <label for="estimate_order">Estimasi Total Biaya</label>
                                </div>
                            </div>
                            <div class="col-12 col-sm-4">
                                <div class="form-floating">
                                    <input id="dp" type="text" name="dp" class="form-control text-gray-800 fw-bold form-control-sm bg-light unvalidate input-fixed" placeholder="DP :...">
                                    <label for="dp">DP</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer p-4 d-flex justify-content-between ">
                        <button type="button" id="kt_cetak_order" class="btn btn-color-muted btn-bg-light btn-active-color-primary btn-sm me-3">
                            <span class="indicator-label"><i class="fonticon-printer fs-3"></i>EO</span>
                            <span class="indicator-progress">{{$config['button-submit-wait']??'Tunggu sebentar'}}...
                            <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                        </button>
                        <button type="button" id="kt_cetak_order_internal" class="btn btn-color-muted btn-bg-light btn-active-color-primary btn-sm me-3">
                            <span class="indicator-label"><i class="fonticon-printer fs-3"></i>EO (Internal)</span>
                            <span class="indicator-progress">{{$config['button-submit-wait']??'Tunggu sebentar'}}...
                            <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                        </button>
                        <button type="button" id="kt_cetak_rincian" class="btn btn-color-muted btn-bg-light btn-active-color-primary btn-sm me-3">
                            <span class="indicator-label"><i class="fonticon-printer fs-3"></i>Rincian Biaya</span>
                            <span class="indicator-progress">{{$config['button-submit-wait']??'Tunggu sebentar'}}...
                            <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                        </button>
                        <button type="submit" id="kt_form_submit" class="btn btn-primary font-weight-bold">
                            <span class="indicator-label">{!!$config['title-page-icon']!!}{{$config['button-submit']}}</span>
                            <span class="indicator-progress">{{$config['button-submit-wait']??'Tunggu sebentar'}}...
                            <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                        </button>
                    </div>
                </div>
                </form>
            </div>
        </div>
        <!--end::Row-->
    </div>
    <!--end::Content container-->
    <div class="modal fade" id="pdfModal" tabindex="-1">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
            <div class="modal-body p-0">
                <iframe src="" style="width:100%; height:90vh; border:0;"></iframe>
            </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('script')
<script src="https://unpkg.com/multiple-select@2.2.0/dist/multiple-select.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/plugins/monthSelect/index.js"></script>
@include('customer_service.list_orders_script')
@endsection