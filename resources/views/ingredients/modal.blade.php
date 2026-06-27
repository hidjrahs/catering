<div class="modal fade" tabindex="-1" id="modal-master-update">
    <div class="modal-dialog modal-dialog-centered">
        <form action="{{ $config['action-update'] }}" method="{{$config['method-update']}}" enctype="multipart/form-data" id="kt_update_main" autocomplete="off">
                @csrf
                @method($config['method-update'])
                <input type="hidden" name="id">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title">{!!$config['title-page-icon']!!} Update Barang/Bahan Baku</h3>
                    <div class="btn btn-icon btn-sm btn-default ms-2" data-bs-dismiss="modal" aria-label="Close">x</div>
                </div>
                <div class="modal-body">
                    <div class="bg-light px-2 py-4 col-md-12 mb-2 skeleton load"></div>
                    <div class="bg-light px-2 py-4 col-md-12 skeleton load"></div>
                    <div class="col-md-12 hidden row" id="modal-update">
                        <div class="col-12 hidden mb-5" data-type="name">
                            <div class="w-lg-100 position-relative fv-row form-group mb-2">
                                <div class="input-group input-group-sm flex-nowrap">
                                    <span class="input-group-text">Nama Barang/Bahan Baku: </span>
                                    <div class="flex-grow-1">
                                        <input type="text" name="name" data-type="name" class="form-control form-control-sm form-control-solid" maxlength="60"  placeholder="Nama Barang/Bahan Baku" value="">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-6 hidden mb-5" data-type="unit">
                            <div class="w-lg-100 position-relative fv-row form-group mb-2">
                                <div class="input-group input-group-sm flex-nowrap">
                                    <span class="input-group-text">Unit: </span>
                                    <div class="flex-grow-1">
                                        <input type="text" name="unit" data-type="unit" class="form-control form-control-sm form-control-solid" maxlength="36" placeholder="Unit: ....." value="">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-6 hidden mb-5" data-type="satuan">
                            <div class="w-lg-100 position-relative fv-row form-group mb-2">
                                <div class="input-group input-group-sm flex-nowrap">
                                    <span class="input-group-text">Satuan: </span>
                                    <div class="flex-grow-1">
                                        <select class="form-select form-select-solid form-select-sm" data-placeholder="Pilih Satuan" name="satuan" placeholder="Pilih Satuan">
                                            @foreach($data['satuan'] as $satuan)
                                                <option value="{{$satuan}}">{{$satuan}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 hidden" data-type="default_price">
                            <div class="w-lg-100 position-relative fv-row form-group mb-2">
                                <div class="input-group input-group-sm flex-nowrap">
                                    <span class="input-group-text">Estimasi Harga/Unit: </span>
                                    <div class="flex-grow-1">
                                        <input type="text" name="default_price" class="form-control form-control-sm form-control-solid" maxlength="64" placeholder="Harga: .....">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 hidden mb-5" data-type="supplier_id">
                            <div class="w-lg-100 position-relative fv-row form-group mb-2">
                                <div class="input-group input-group-sm flex-nowrap">
                                    <span class="input-group-text">Supplier: </span>
                                    <div class="flex-grow-1">
                                        <select class="form-select form-select-solid form-select-sm" 
                                            id="kt_select2_supplier_update" 
                                            data-placeholder="Cari atau tambah Supplier" 
                                            name="supplier_id[]" 
                                            data-control="select2"
                                            data-dropdown-parent="#modal-master-update" 
                                            data-allow-clear="true"
                                            multiple
                                            placeholder="Pilih Supplier">
                                            
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" id="kt_update_submit" class="btn btn-primary w-100">
                        <span class="indicator-label">{{$config['button-update']}} {!!$config['title-page-icon']!!}</span>
                        <span class="indicator-progress">{{$config['button-update-wait']??'Tunggu sebentar'}}...
                        <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>