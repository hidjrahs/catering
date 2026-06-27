<!--begin::Modal - Large-->
<div class="modal fade" id="modal-cost-controlling" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-fullscreen modal-dialog-centered">
        <form id="kt_cost_main" class="form h-100 w-100" action="{{$config['action']}}" method="{{$config['method']}}" autocomplete="off">
        @csrf
        @method($config['method'])
        <div class="modal-content">
            <!--begin::Modal header-->
            <div class="modal-header p-4">
                <h2 class="fw-bold">{!!$config['title-page-icon']!!} Cost Control : <span data-replace="title_modal"></span></h2>
                <div class="btn btn-icon btn-sm btn-active-light-primary" data-bs-dismiss="modal">
                    <i class="ki-outline ki-cross fs-2"></i>
                </div>
                <button type="button" class="btn btn-color-muted btn-bg-light btn-active-color-primary btn-sm me-3 btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <!--end::Modal header-->
            <!--begin::Modal body-->
            <div class="modal-body p-4 py-0">
                <div class="row g-5 h-100">
                    <div class="col-md-4 h-100 border-end border-2 border-light">
                        <div class="card card-flush h-100">
                            <div class="card-body p-0">
                                <div class="hover-scroll-overlay-y pe-6 me-n3 mh-500px pt-4">
                                    <div class="row mb-4">
                                        <ul class="nav nav-pills nav-pills-custom">
                                            <li class="nav-item">
                                                <a class="nav-link active fw-bold fs-5 text-gray-800" data-bs-toggle="tab" href="#kt_tab_rincian">Rincian Biaya</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link fw-bold fs-5 text-gray-800" data-bs-toggle="tab" href="#kt_tab_detail">Detail Pemesanan</a>
                                            </li>
                                        </ul>
                                        <div class="tab-content pt-5">
                                            <div class="tab-pane fade show active" id="kt_tab_rincian">
                                                <div class="row px-4">
                                                    <div class="col-12 d-flex justify-content-between align-items-center flex-column flex-sm-row gap-2">
                                                        <span class="card-label fw-bold text-dark"><i class="fa-solid fa-money-bill-wave me-2"></i> Daftar Rincian Biaya</span>
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
                                                    <div class="col-12 col-sm-6">
                                                        <div class="form-floating">
                                                            <input type="hidden" name="estimate_order" readonly>
                                                            <input id="estimate_order" type="text" name="estimate_order_label" class="form-control text-gray-800 fw-bold form-control-sm bg-light unvalidate" readonly placeholder="Estimasi Biaya :...">
                                                            <label for="estimate_order">Estimasi Total Biaya</label>
                                                        </div>
                                                    </div>
                                                    <div class="col-12 col-sm-6">
                                                        <div class="form-floating">
                                                            <input id="dp" type="text" name="dp" class="form-control text-gray-800 fw-bold form-control-sm bg-light unvalidate input-fixed" placeholder="DP :...">
                                                            <label for="dp">DP</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="tab-pane fade" id="kt_tab_detail">
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
                                                    <label for="name" class="col-lg-4 fw-semibold text-muted">Porsi Pesanan</label>
                                                    <div class="col-lg-8">
                                                        <span class="fw-bold fs-6 text-gray-800" data-replace="total_guest"></span>
                                                    </div>
                                                </div>
                                                <div class="row mb-2">
                                                    <label for="name" class="col-lg-4 fw-semibold text-muted">Jumlah Undangan</label>
                                                    <div class="col-lg-8">
                                                        <span class="fw-bold fs-6 text-gray-800" data-replace="total_invite"></span>
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
                            </div>
                        
                        </div>
                    </div>
                    <div class="col-md-4 border-end border-2 border-light">
                        <div class="card card-flush h-100">
                            <div class="card-body p-0">
                                <div class="hover-scroll-overlay-y pe-6 me-n3 mh-500px pt-4">
                                    <div class="row mb-4">
                                        <div class="col-12"><span class="fw-bold fs-5 text-gray-800">Detail Pesanan</span></div>
                                    </div>
                                    <div class="mb-4" data-replace="item">
                                        <div class="border border-dashed border-gray-300 rounded p-5 py-10 text-center" id="item_none">{!!$config['title-icon']!!} Menu belum di isi</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card card-flush h-100">
                            <div class="card-body p-0">
                                <div class="hover-scroll-overlay-y pe-6 me-n3 mh-500px pt-4">
                                    <div class="row mb-4">
                                        <div class="col-12 mb-4"><span class="fw-bold fs-5 text-gray-800">Cost Structure</span></div>
                                    </div>
                                    <div class="mb-4" data-replace="item_desc">
                                        <div class="d-flex align-items-center w-100 w-sm-auto mb-2">
                                            <div class="position-relative flex-fill">
                                                <select class="form-select form-select-solid form-select-sm unvalidate"
                                                    name="cost_structure_id" 
                                                    id="kt_cost_structure_id"
                                                    data-control="select2"
                                                    data-hide-search="true"
                                                    data-placeholder="Pilih Template">
                                                    <option value="-">Custom</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="d-flex justify-content-end flex-wrap mb-2" data-event="category">
                                            <div class="d-flex flex-fill">
                                                <input type="text" id="category_text" class="form-control form-control-sm unvalidate w-100 rounded-0" placeholder="Kategori :..."/>
                                            </div>
                                            <button type="button" id="add_category" class="btn btn-primary btn-sm font-weight-bold rounded-0">
                                                <span class="svg-icon svg-icon-3 m-0">
                                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                        <rect opacity="0.5" x="11" y="18" width="12" height="2" rx="1" transform="rotate(-90 11 18)" fill="currentColor" />
                                                        <rect x="6" y="11" width="12" height="2" rx="1" fill="currentColor" />
                                                    </svg>
                                                </span>
                                                <span class="indicator-label">Kategori
                                                <span class="indicator-progress">Proses...
                                                <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                                            </button>
                                        </div>
                                        <div class="d-flex flex-wrap" id="cost-structure-item">

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--end::Modal body-->

            <!--begin::Modal footer-->
            <!-- d-flex justify-content-end  -->
            <div class="modal-footer p-4 d-flex flex-column">
                <div class="row w-100 hidden">
                    <div class="col-md-4">
                        <input type="hidden" name="id" value="">
                        <label for="estimated_cost" class="mb-2 fw-semibold text-muted">Total Actual FC (+Net Cost):</label>
                        <div class="input-group input-group-sm w-100">
                            <span class="input-group-text">Rp.</span>
                            <input type="text" name="estimated_cost" class="form-control form-control-sm bg-light" readonly value="" data-replace="estimated_cost"/>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label for="estimated_margin" class="mb-2 fw-semibold text-muted">Margin:</label>
                        <div class="input-group input-group-sm w-100">
                            <span class="input-group-text">Rp.</span>
                            <input type="text" name="estimated_margin" class="form-control form-control-sm input-fixed" value="0" data-replace="estimated_margin"/>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label for="estimated_selling_price" class="mb-2 fw-semibold text-muted">Est. Jual:</label>
                        <div class="input-group input-group-sm w-100">
                            <span class="input-group-text">Rp.</span>
                            <input type="text" name="estimated_selling_price" class="form-control form-control-sm bg-light" readonly value="" data-replace="estimated_selling_price"/>
                        </div>
                    </div>
                </div>
                <div class="d-flex flex-stack w-100 mt-4">
                    <button type="button" id="kt_cetak_sr" class="btn btn-color-muted btn-bg-light btn-active-color-primary btn-sm me-3">
                        <span class="indicator-label"><i class="fonticon-printer fs-3"></i>Cetak SR</span>
                        <span class="indicator-progress">{{$config['button-submit-wait']??'Tunggu sebentar'}}...
                        <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                    </button>
                    <button type="button" id="kt_cetak_sr_kitchen" class="btn btn-color-muted btn-bg-light btn-active-color-primary btn-sm me-3">
                        <span class="indicator-label"><i class="fonticon-printer fs-3"></i>Cetak SR (Kitchen)</span>
                        <span class="indicator-progress">{{$config['button-submit-wait']??'Tunggu sebentar'}}...
                        <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                    </button>
                    <button type="button" id="kt_cetak_cost_control" class="btn btn-color-muted btn-bg-light btn-active-color-primary btn-sm me-3">
                        <span class="indicator-label"><i class="fonticon-printer fs-3"></i>Cetak Cost Structure</span>
                        <span class="indicator-progress">{{$config['button-submit-wait']??'Tunggu sebentar'}}...
                        <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                    </button>
                    <button type="submit" id="kt_cost_submit" class="btn btn-primary">
                        <span class="indicator-label"><i class="fa-solid fa-check-double"></i>{{$config['button-submit']}}</span>
                        <span class="indicator-progress">{{$config['button-wait']??'Tunggu sebentar'}}...
                            <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                        </span>
                    </button>
                    <button type="button" id="kt_cost_udpate" class="btn btn-success hidden">
                        <span class="indicator-label"><i class="fa-solid fa-pen-to-square"></i>{{$config['button-update']}}</span>
                        <span class="indicator-progress">{{$config['button-wait']??'Tunggu sebentar'}}...
                            <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                        </span>
                    </button>
                </div>
            </div>
            <!--end::Modal footer-->

        </div>
        </form>
    </div>
</div>
<!--end::Modal - Large-->

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