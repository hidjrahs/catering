@extends('layouts.app')
@section('css')
<link rel="stylesheet" href="https://unpkg.com/multiple-select@2.2.0/dist/multiple-select.min.css">
<style>
    .card.card-flush.cursor-pointer {
        transition: all 0.3s ease-in-out;
    }
    .minh-100px{
        min-height: 100px !important;
    }
    #list_menus .card.active,
    #list_menus .card:hover {
        border: 3px solid #009ef7 !important; /* border lebih tegas */
        position: relative;
        background-color: #e9f7ff;
        box-shadow: 0 0 10px rgba(0, 158, 247, 0.4);
    }
    /* icon centang pojok kanan atas */
    #list_menus .card.active::after {
        content: "\f00c";
        font-family: "Font Awesome 6 Free";
        font-weight: 900;
        color: #009ef7;
        font-size: 18px;
        position: absolute;
        top: 0px;
        right: 2px;
        --bs-black: ;
    }
    #order-container{
        max-height: 590px;
        overflow-y: scroll;
    }
    .ms-choice{
        height: 35px !important;
        line-height: 35px !important;
        border: 1px solid #e6e9ea;
    }
    .ms-parent .ms-drop{
        padding: 4px;
        margin-top: 6px;
    }
    .ms-parent .ms-drop ul li label span{
        padding-left: 4px;
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
                    <div class="card-header p-4 min-h-50px">
                        <h3 class="card-title align-items-start flex-column card-title-sm m-0">
                            <span class="card-label fw-bold text-dark">{!!$config['title-icon2']!!} Daftar Menu</span>
                        </h3>
                        <div class="card-toolbar w-100 w-sm-auto m-0">
                            <div class="d-flex flex-stack flex-wrap flex-sm-row gap-2 w-100 w-sm-auto">
                                <div class="d-flex align-items-center w-100 w-sm-auto ">
                                    <div class="position-relative flex-fill me-4">
                                        <select id="packet-id" multiple="multiple" placeholder="Pilih Jenis Paket Menu" class="w-200px"></select>
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
                                        <input type="text" class="form-control fs-7 ps-12 search-input form-control-sm" id="search" placeholder="Pencarian...." title="Tekan Enter untuk mencari.">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body p-4">
                        <div class="d-flex flex-column flex-lg-row">
                            <div class="d-lg-flex flex-column flex-lg-row-auto w-lg-240px mh-500px scroll-y pe-1" >
                                <!--begin::Navs-->
                                <ul class="nav nav-pills nav-pills-custom flex-column border-transparent fs-5 fw-bold" id="category_menu">
                                    <!--begin::Nav item-->
                                    <li class="nav-item mb-0 category-all">
                                        <a class="nav-link text-muted text-active-primary ms-0 py-0 ps-4 border-0 active fs-7" href="javascript:;">
                                            <span class="symbol-label bg-light-primary text-primary fw-bold p-1 me-1 text-center">
                                                <i class="fa-solid fa-list"></i>
                                            </span>
                                            Semua Kategori
                                            <span class="badge badge-light-success" data-category="total-all">{{$data['category_total']??''}}</span>
                                            <!--begin::Bullet-->
                                            <input type="radio" name="category_menu" value="all" checked class="hidden"/>
                                            <span class="bullet-custom position-absolute start-0 top-0 w-3px h-100 bg-primary rounded-end"></span>
                                            <!--end::Bullet-->
                                        </a>
                                        <div class="separator separator-dashed my-2"></div>
                                    </li>
                                    <!--end::Nav item-->
                                    @if(in_array('category',array_keys($data)))
                                        @foreach($data['category'] as $category)
                                            <li class="nav-item mb-0">
                                                <input type="radio" name="category_menu" value="{{$category['id']}}" class="hidden"/>
                                                <a class="nav-link text-muted text-active-primary ms-0 py-0 ps-4 border-0 fs-7" href="javascript:;">
                                                    <span class="symbol-label bg-light-primary text-primary fw-bold p-1 me-1 text-center">
                                                        @if($category['icon'])
                                                            <i class="fa-solid {{$category['icon']}}"></i>
                                                        @else
                                                            {{$category['label']}}
                                                        @endif
                                                    </span>
                                                    {{$category['name']}}
                                                    <span class="badge badge-light-success" data-category="{{$category['id']}}">{{$category['active_menus_count']>0?$category['active_menus_count']:''}}</span>
                                                    <span class="bullet-custom position-absolute start-0 top-0 w-3px h-100 bg-primary rounded-end hidden"></span>
                                                    <div class="separator separator-dashed my-2"></div>
                                                </a>
                                            </li>
                                        @endforeach
                                    @endif
                                </ul>
                                <!--begin::Navs-->
                            </div>
                            <div class="flex-lg-row-fluid mb-0 d-flex flex-wrap d-grid gap-4 p-4 mh-500px scroll-y blockui" id="list_menus">
                               
                            </div>
                        </div>
                    
                    </div>
                </div>
            </div>
            <div class="d-lg-flex flex-column flex-lg-row-auto w-100 w-lg-600px pt-0 mb-2 " id="order-container" >
                <form action="{{$config['action']}}" method="post" id="kt_form_main" autocomplete="off">
                @csrf
                @method($config['method'])
                <div class="card mt-2">
                    <div class="card-header p-4 min-h-50px">
                        <h3 class="card-title align-items-start flex-column card-title-sm m-0">
                            <span class="card-label fw-bold text-dark">{!!$config['title-icon']!!}Event Order Menu</span>
                        </h3>
                        <div class="card-toolbar w-100 w-sm-auto m-0">
                            <div class="d-flex flex-stack flex-wrap flex-sm-row gap-2 w-100 w-sm-auto">
                                <div class="d-flex align-items-center w-100 w-sm-auto ">
                                    <a href="{{route('customer_service.order')}}" class="btn btn-sm btn-light me-2 px-3" title="Riwayat Order.">
                                        <i class="fa-solid fa-receipt"></i> Riwayat Event Order
                                    </a>
<button type="button" id="kt_run_queue" data-url="{{route('run.queue.import')}}" class="btn btn-sm btn-success ms-3 px-4" title="Jalankan Queue Import">
                                            <i class="fa-solid fa-play"></i> Jalankan Queue Import
                                        </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body p-4">
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
                                <label class="form-label"> Daftar pilihan menu: <span data-ref="total_menu">0</span> Menu</label>
                                <div class="mh-400px scroll-y" id="list_order">
                                    <div class="border border-dashed border-gray-300 rounded p-5 py-10 text-center" id="item_none">{!!$config['title-icon']!!}Pilihan menu belum di isi</div>
                                </div>
                            </div>
                            <div class="separator separator-dashed mb-2"></div>
                            <div class="col-12 col-sm-6 mb-2">
                                <div class="form-floating fv-row">
                                    <select id="package_type" class="form-select form-select-solid form-select-sm" 
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
                                    <select id="event_type" class="form-select form-select-solid form-select-sm" 
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
                            <div class="col-12 mb-2">
                                <div class="form-floating fv-row">
                                    <input id="venue" type="text" name="venue" class="form-control text-gray-800 fw-bold form-control-sm" placeholder="Venue :...">
                                    <label for="venue">Venue</label>
                                </div>
                            </div>
                            <div class="separator separator-dashed mb-2"></div>
                            <div class="col-12 col-sm-12 mb-2 fv-row">
                                <textarea id="desc" name="desc" class="form-control text-gray-800 fw-bold form-control-sm mh-150px minh-100px" placeholder="Keterangan :...">{{config("option.template_keterangan")}}</textarea>
                            </div>
                            <div class="col-12 col-sm-12 mb-2 fv-row">
                                <textarea id="desc_extra" name="desc_extra" class="form-control text-gray-800 fw-bold form-control-sm mh-150px minh-100px" placeholder="History Pemesanan :...">{{config("option.template_history_pemesanan")}}</textarea>
                            </div>
                            <div class="col-12 col-sm-12 mb-2 fv-row">
                                 <span>&lt;br&gt; = Ganti Baris (gunakan tanda ini untuk ganti baris)</span>
                            </div>
                            <div class="separator separator-dashed mb-2"></div>
                            <div class="col-12 col-sm-12 hidden">
                                <div class="form-floating">
                                    <input type="hidden" name="estimate_price" readonly>
                                    <input id="estimate_price" type="text" name="estimate_price_label" class="form-control text-gray-800 fw-bold form-control-sm bg-light unvalidate" readonly placeholder="Estimasi Biaya :...">
                                    <label for="estimate_price">Estimasi Biaya (Produksi Bahan)</label>
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
                    <div class="card-footer p-4 d-flex justify-content-end ">
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
@include('customer_service.index_script')
@endsection