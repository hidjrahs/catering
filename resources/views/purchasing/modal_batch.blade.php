<!--begin::Modal - Large-->
<div class="modal fade" id="modal-batch" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <form id="kt_batch_main" class="form h-100 modal-content" action="{{$config['action_batch']}}" method="{{$config['method_batch']}}" autocomplete="off">
        @csrf
        @method($config['method'])
            <div class="modal-header p-4">
                <h2 class="fw-bold"><i class="fonticon-printer fs-3"></i> Cetak Batch Purchasing</h2>
                <div class="btn btn-icon btn-sm btn-active-light-primary" data-bs-dismiss="modal">
                    <i class="ki-outline ki-cross fs-2"></i>
                </div>
            </div>
            <div class="modal-body p-4 py-0">
                <div class="d-flex align-items-center w-100 w-sm-auto mt-2">
                    <div class="position-relative flex-fill me-3 " >
                        <span class="svg-icon svg-icon-2 position-absolute top-50 translate-middle-y ms-4">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
                                <path opacity="0.3" d="M5 8H19V19H5V8Z" fill="currentColor"/>
                                <path d="M7 2V4M17 2V4M3 6H21" stroke="currentColor" stroke-width="2"/>
                            </svg>
                        </span>
                        <input type="text" class="form-control form-control-sm fs-7 ps-12 search-batch unvalidate" name="batch_range" id="batch_range" placeholder="Pilih range tanggal">
                    </div>
                    <div class="position-relative flex-fill mw-200px" >
                        <select class="form-select form-select-solid form-select-sm search-batch" name="batch_order" id="batch_order">
                            <option value="event">Event</option>
                            <option value="order">Order</option>
                        </select>
                    </div>
                </div>
                <div class="w-100 w-sm-auto min-h-300px hover-scroll-overlay-y mt-2 mh-400px">
                    <div class="table-responsive">
                        <table class="table align-middle  fs-6 gy-3 gs-3 table-hover table-sm table-row-bordered " id="batch-table">
                            <thead>
                                <tr class="fw-bold text-muted bg-light">
                                    <th>No. Order</th>
                                    <th>Customer/Pelanggan</th>
                                    <th>Order</th>
                                    <th class="text-end pe-3">Order/Event</th>
                                </tr>
                            </thead>
                            <tbody class="fw-bold text-gray-600"></tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer p-4 d-flex flex-column">
                <div class="d-flex flex-stack w-100">
                    <button type="button" class="btn btn-light me-3" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" id="kt_batch_submit" class="btn btn-primary">
                        <span class="indicator-label"><i class="fonticon-printer fs-3"></i>Proses Cetak Batch</span>
                        <span class="indicator-progress">{{$config['button-wait']??'Tunggu sebentar'}}...
                            <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                        </span>
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>