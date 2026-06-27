@extends('layouts.app')
@section('css')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/plugins/monthSelect/style.css">
<style>
    #order-container{
        max-height: 590px;
        overflow-y: scroll;
    }
    .flatpickr-calendar.rangeMode{
        z-index: 1060 !important;
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
                            <span class="card-label fw-bold text-dark">{!!$config['title-icon']!!} {{$config['title-content']}}</span>
                        </h3>
                        <div class="card-toolbar w-100 w-sm-auto m-0">
                            <!--begin::Filters-->
                            <div class="d-flex flex-stack flex-wrap flex-sm-row gap-2 w-100 w-sm-auto">
                                <!--begin::Search-->
                                <div class="d-flex align-items-center w-100 w-sm-auto ">
                                    <button type="button" id="kt_batch_purchasing" class="btn btn-color-muted btn-bg-light btn-active-color-primary btn-sm me-3">
                                        <span class="indicator-label"><i class="fonticon-printer fs-3"></i>Batch Purchasing</span>
                                        <span class="indicator-progress">{{$config['button-submit-wait']??'Tunggu sebentar'}}...
                                        <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                                    </button>
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
                                    <div class="text-muted fs-7 me-2">Status:</div>
                                    <div class="position-relative flex-fill">
                                        <select class="form-select form-select-solid form-select-sm search-input min-w-150px"
                                            name="order_status" 
                                            id="order_status"
                                            data-control="select2"
                                            data-hide-search="true" 
                                            data-placeholder="Status"
                                            placeholder="">
                                            <option value="-">Semua Status</option>
                                            @foreach($data['order_status'] as $key=>$item)
                                                <option value="{{$key}}">{{$item}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
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
                                        <input type="text" class="form-control form-control-sm fs-7 ps-12 search-input" id="search" placeholder="Pencarian....">
                                    </div>
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
        </div>
        <!--end::Row-->
    </div>
    <!--end::Content container-->
    @include('purchasing.modal')
    @include('purchasing.modal_batch')
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
<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/id.js"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/plugins/monthSelect/index.js"></script>
@include('purchasing.index_script')
@endsection