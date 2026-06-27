<div class="modal fade" tabindex="-1" id="modal-master-update">
    <div class="modal-dialog modal-dialog-centered">
        <form action="{{ $config['action-update'] }}" method="{{$config['method-update']}}" enctype="multipart/form-data" id="kt_update_main" autocomplete="off">
                @csrf
                @method($config['method-update'])
                <input type="hidden" name="id">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title">{!!$config['title-page-icon']!!} Update Customer</h3>
                    <div class="btn btn-icon btn-sm btn-default ms-2" data-bs-dismiss="modal" aria-label="Close">x</div>
                </div>
                <div class="modal-body">
                    <div class="bg-light px-2 py-4 col-md-12 mb-2 skeleton load"></div>
                    <div class="bg-light px-2 py-4 col-md-12 skeleton load"></div>
                    <div class="col-md-12 hidden row" id="modal-update">
                        <div class="col-12 hidden mb-5" data-type="name">
                            <div class="w-lg-100 position-relative fv-row form-group mb-2">
                                <div class="input-group input-group-sm flex-nowrap">
                                    <span class="input-group-text">Nama Customer: </span>
                                    <div class="flex-grow-1">
                                        <input type="text" name="name" data-type="name" class="form-control form-control-sm form-control-solid" maxlength="60"  placeholder="Nama Customer" value="">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-6 hidden mb-5" data-type="phone">
                            <div class="w-lg-100 position-relative fv-row form-group mb-2">
                                <div class="input-group input-group-sm flex-nowrap">
                                    <span class="input-group-text">No Telp: </span>
                                    <div class="flex-grow-1">
                                        <input type="text" name="phone" data-type="phone" class="form-control form-control-sm form-control-solid" maxlength="36" placeholder="No. Telp : +62 ********" value="">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-6 hidden mb-5" data-type="gender">
                            <div class="w-lg-100 position-relative fv-row form-group mb-2">
                                <div class="input-group input-group-sm flex-nowrap">
                                    <span class="input-group-text">Gender: </span>
                                    <div class="flex-grow-1">
                                        <select class="form-select form-select-solid form-select-sm" data-placeholder="Pilih Gender" name="gender" data-type="gender" placeholder="Pilih Gender">
                                            <option value="1">Laki-Laki</option>
                                            <option value="2">Perempuan</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 hidden" data-type="city_id">
                            <div class="mb-1 fv-row">
                                <label for="gender" class="form-label">Kota/Kabupaten</label>
                                <select class="form-select form-select-solid form-select-sm" 
                                    name="city_id" 
                                    placeholder="Pilih Kota/Kab:..."></select>
                            </div>
                        </div>
                        <div class="col-12 hidden" data-type="vilage_id">
                            <div class="mb-1 fv-row">
                                <label for="gender" class="form-label">Kelurahan/Desa</label>
                                <select class="form-select form-select-solid form-select-sm" 
                                    name="vilage_id" 
                                    placeholder="Pilih Kelurahan/Desa:..."></select>
                            </div>
                        </div>
                        <div class="col-12 hidden" data-type="address">
                            <div class="w-lg-100 position-relative fv-row form-group mb-2">
                                <div class="input-group input-group-sm flex-nowrap">
                                    <span class="input-group-text">Alamat: </span>
                                    <div class="flex-grow-1">
                                        <textarea name="address" data-type="address" class="form-control form-textarea-hf form-control-sm form-control-solid unvalidate" maxlength="120" placeholder="Alamat..."></textarea>
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