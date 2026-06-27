<!--begin::Modal - Large-->
<div class="modal fade" id="modal-purchasing" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-fullscreen modal-dialog-centered">
        <form id="kt_purchase_main" class="form h-100 modal-content" action="{{$config['action']}}" method="{{$config['method']}}" autocomplete="off">
        @csrf
        @method($config['method'])
            <div class="modal-header p-4">
                <h2 class="fw-bold">{!!$config['title-page-icon']!!} Purchasing : <span data-replace="title_modal"></span></h2>
                <div class="btn btn-icon btn-sm btn-active-light-primary" data-bs-dismiss="modal">
                    <i class="ki-outline ki-cross fs-2"></i>
                </div>
                <div class="modal-toolbar m-0">
                    <ul class="nav nav-tabs nav-line-tabs nav-stretch fs-6 border-0 fw-bold" role="tablist">
                        
                        <li class="nav-item" role="presentation">
                            <a id="purchasing_tab" 
                                class="nav-link justify-content-center text-active-gray-800 active" 
                                data-bs-toggle="tab" 
                                role="tab" 
                                href="#content_purchasing" 
                                aria-selected="true">Detail Purchasing</a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a id="order_tab" 
                                class="nav-link justify-content-center text-active-gray-800" 
                                data-bs-toggle="tab" 
                                role="tab" 
                                href="#content_order" 
                                aria-selected="false" 
                                tabindex="-1">Detail Order</a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="modal-body p-4 py-0">
                <div class="tab-content">
                    <div id="content_purchasing" class="card-body p-0 tab-pane fade active show" role="tabpanel" aria-labelledby="#purchasing_tab">
                        <div class="row g-5 h-100">
                            <div class="col-md-5">
                                <div class="card card-flush h-100">
                                    <div class="card-body p-0">
                                        <div class="hover-scroll-overlay-y pe-4 me-n2 mh-500px pt-4">
                                            <div class="d-flex flex-stack w-100 min-h-40px">
                                                <span class="fw-bold fs-5 text-gray-800">Detail Barang/Bahan baku per Porsi</span>
                                            </div>
                                            <div class="mb-4 mt-4" data-replace="item_desc">
                                                <div class="border border-dashed border-gray-300 rounded p-5 py-10 text-center" id="item_desc_none">{!!$config['title-icon']!!} Menu belum di isi</div>
                                                
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-7">
                                <div class="card card-flush h-100">
                                    <div class="card-body p-0">
                                        <div class="hover-scroll-overlay-y pe-4 me-n2 mh-500px pt-4">
                                            <div class="d-flex flex-stack w-100 min-h-40px">
                                                <div class="fw-bold fs-5 text-gray-800">Purchase Item <span data-purchase="count"></span></div>
                                                <div class="input-group input-group-sm mw-300px fv-row">
                                                     <span class="input-group-text fs-6 fw-semibold text-gray-700">Tgl. Pembelian</span>
                                                    <input id="purchase_date" type="text" name="purchase_date" class="form-control text-gray-800 fw-bold form-control-sm" placeholder="Tgl. pembelian :...">
                                                </div>
                                            </div>
                                            <div class="mb-4 mt-4" data-replace="item_purchase">
                                                <div class="border border-dashed border-gray-300 rounded p-5 py-10 text-center" id="item_purchase_none">{!!$config['title-icon']!!} Daftar Barang/Bahan baku yang harus di beli.</div>
                                                <div class="d-flex flex-column border rounded mb-2 border-gray-300" id="item_purchase_list">
                                                    <input type="hidden" name="id" value="">
                                                    <div class="mb-4">
                                                        <table class="table align-middle fs-7 gy-3 gs-3 table-hover table-sm table-row-bordered m-0">
                                                            <thead>
                                                                <tr class="fw-bold text-muted bg-light">
                                                                    <th colspan="2">Bahan</th>
                                                                    <th class="text-end">Order</th>
                                                                    <th class="text-end">Unit</th>
                                                                    <th class="text-end">Harga</th>
                                                                    <th class="text-end">Total</th>
                                                                    <th>Supplier</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody></tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                        </div>
                    </div>
                    <div id="content_order" class="card-body p-0 tab-pane fade " role="tabpanel" aria-labelledby="#order_tab">
                        <div class="row g-5 h-100">
                            <div class="col-md-4 h-100 border-end border-2 border-light">
                                <div class="card card-flush h-100">
                                    <div class="card-body p-0">
                                        <div class="hover-scroll-overlay-y pe-4 me-n2 mh-500px pt-4">
                                            <div class="row mb-4">
                                                <div class="col-12 mb-4"><span class="fw-bold fs-5 text-gray-800">Detail Pemesan</span> </div>
                                            </div>
                                            <div class="row mb-2">
                                                <label for="name" class="col-lg-4 fw-semibold text-muted">No. Order</label>
                                                <div class="col-lg-8">
                                                    <span class="fw-bold fs-6 text-gray-800" data-replace="order_ticket"></span>
                                                </div>
                                            </div>
                                            <div class="row mb-2">
                                                <label for="name" class="col-lg-4 fw-semibold text-muted">Nama Pemesan</label>
                                                <div class="col-lg-8">
                                                    <span class="fw-bold fs-6 text-gray-800" data-replace="customer_name"></span>
                                                </div>
                                            </div>
                                            <div class="row mb-2">
                                                <label for="name" class="col-lg-4 fw-semibold text-muted">Kontak</label>
                                                <div class="col-lg-8">
                                                    <span class="fw-bold fs-6 text-gray-800" data-replace="customer_phone"></span>
                                                </div>
                                            </div>
                                            <div class="row mb-2">
                                                <label for="name" class="col-lg-4 fw-semibold text-muted">Provinsi</label>
                                                <div class="col-lg-8">
                                                    <span class="fw-bold fs-6 text-gray-800" data-replace="customer_province"></span>
                                                </div>
                                            </div>
                                            <div class="row mb-2">
                                                <label for="name" class="col-lg-4 fw-semibold text-muted">Kota/Kab</label>
                                                <div class="col-lg-8">
                                                    <span class="fw-bold fs-6 text-gray-800" data-replace="customer_city"></span>
                                                </div>
                                            </div>
                                            <div class="row mb-2">
                                                <label for="name" class="col-lg-4 fw-semibold text-muted">Kecamatan</label>
                                                <div class="col-lg-8">
                                                    <span class="fw-bold fs-6 text-gray-800" data-replace="customer_district"></span>
                                                </div>
                                            </div>
                                            <div class="row mb-2">
                                                <label for="name" class="col-lg-4 fw-semibold text-muted">Kelurahan/Desa</label>
                                                <div class="col-lg-8">
                                                    <span class="fw-bold fs-6 text-gray-800" data-replace="customer_vilage"></span>
                                                </div>
                                            </div>
                                            <div class="row mb-2">
                                                <label for="name" class="col-lg-4 fw-semibold text-muted">Alamat</label>
                                                <div class="col-lg-8">
                                                    <span class="fw-bold fs-6 text-gray-800" data-replace="customer_address"></span>
                                                </div>
                                            </div>
                                            <div class="separator separator-dashed mb-2"></div>
                                            <div class="row mb-2">
                                                <label for="name" class="col-lg-4 fw-semibold text-muted">Status</label>
                                                <div class="col-lg-8">
                                                    <span class="fw-bold fs-6 text-gray-800" data-replace="status"></span>
                                                </div>
                                            </div>
                                            <div class="row mb-2">
                                                <label for="name" class="col-lg-4 fw-semibold text-muted">Venue</label>
                                                <div class="col-lg-8">
                                                    <span class="fw-bold fs-6 text-gray-800" data-replace="venue"></span>
                                                </div>
                                            </div>
                                            <div class="row mb-2">
                                                <label for="name" class="col-lg-4 fw-semibold text-muted">Tipe Pesananan</label>
                                                <div class="col-lg-8">
                                                    <span class="fw-bold fs-6 text-gray-800" data-replace="package_type"></span>
                                                </div>
                                            </div>
                                            <div class="row mb-2">
                                                <label for="name" class="col-lg-4 fw-semibold text-muted">Tema Event</label>
                                                <div class="col-lg-8">
                                                    <span class="fw-bold fs-6 text-gray-800" data-replace="event_type"></span>
                                                </div>
                                            </div>
                                            <div class="row mb-2">
                                                <label for="name" class="col-lg-4 fw-semibold text-muted">Total tamu</label>
                                                <div class="col-lg-8">
                                                    <span class="fw-bold fs-6 text-gray-800" data-replace="total_guest"></span>
                                                </div>
                                            </div>
                                            <div class="row mb-2">
                                                <label for="name" class="col-lg-4 fw-semibold text-muted">Tanggal Kirim</label>
                                                <div class="col-lg-8">
                                                    <span class="fw-bold fs-6 text-gray-800" data-replace="delivery_date"></span>
                                                </div>
                                            </div>
                                            <div class="row mb-2">
                                                <label for="name" class="col-lg-4 fw-semibold text-muted">Tanggal Acara</label>
                                                <div class="col-lg-8">
                                                    <span class="fw-bold fs-6 text-gray-800" data-replace="event_date"></span>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                
                                </div>
                            </div>
                            <div class="col-md-8 border-end border-2 border-light">
                                <div class="card card-flush h-100">
                                    <div class="card-body p-0">
                                        <div class="hover-scroll-overlay-y pe-4 me-n2 mh-500px pt-4">
                                            <div class="row mb-4">
                                                <div class="col-12 mb-4"><span class="fw-bold fs-5 text-gray-800">Daftar Pesanan</span></div>
                                            </div>
                                            <div class="mb-4" data-replace="item">
                                                <div class="border border-dashed border-gray-300 rounded p-5 py-10 text-center" id="item_none">{!!$config['title-icon']!!} Menu belum di isi</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer p-4 d-flex flex-column">
                <div class="d-flex flex-stack w-100">
                    <button type="button" class="btn btn-light me-3" data-bs-dismiss="modal">Batal</button>
                    <button type="button" id="kt_cetak_purchasing" class="btn btn-color-muted btn-bg-light btn-active-color-primary btn-sm">
                        <span class="indicator-label"><i class="fonticon-printer fs-3"></i>Cetak Purchasing</span>
                        <span class="indicator-progress">{{$config['button-wait']??'Tunggu sebentar'}}...
                        <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                    </button>
                    <button type="submit" id="kt_purchase_submit" class="btn btn-primary">
                        <span class="indicator-label"><i class="fa-solid fa-check-double"></i>{{$config['button-submit']}}</span>
                        <span class="indicator-progress">{{$config['button-wait']??'Tunggu sebentar'}}...
                            <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                        </span>
                    </button>
                    <button type="button" id="kt_purchase_udpate" class="btn btn-success hidden">
                        <span class="indicator-label"><i class="fa-solid fa-pen-to-square"></i>{{$config['button-update']}}</span>
                        <span class="indicator-progress">{{$config['button-wait']??'Tunggu sebentar'}}...
                            <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                        </span>
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>