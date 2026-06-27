<div class="modal fade" tabindex="-1" id="modal-master-update">
    <div class="modal-dialog modal-dialog-centered">
        <form action="{{ $config['action-update'] }}" method="{{$config['method-update']}}" enctype="multipart/form-data" id="kt_update_main" autocomplete="off">
                @csrf
                @method($config['method-update'])
                <input type="hidden" name="id">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title">{!!$config['title-page-icon']!!} Update Supplier</h3>
                    <div class="btn btn-icon btn-sm btn-default ms-2" data-bs-dismiss="modal" aria-label="Close">x</div>
                </div>
                <div class="modal-body">
                    <div class="bg-light px-2 py-4 col-md-12 mb-2 skeleton load"></div>
                    <div class="bg-light px-2 py-4 col-md-12 skeleton load"></div>
                    <div class="col-md-12 hidden row" id="modal-update">
                        <div class="col-12 hidden mb-5" data-type="name">
                            <div class="w-lg-100 position-relative fv-row form-group mb-2">
                                <div class="input-group input-group-sm flex-nowrap">
                                    <span class="input-group-text">Nama Supplier: </span>
                                    <div class="flex-grow-1">
                                        <input type="text" name="name" data-type="name" class="form-control form-control-sm form-control-solid" maxlength="60"  placeholder="Nama Supplier" value="">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 hidden mb-5" data-type="phone">
                            <div class="w-lg-100 position-relative fv-row form-group mb-2">
                                <div class="input-group input-group-sm flex-nowrap">
                                    <span class="input-group-text">No Telp: </span>
                                    <div class="flex-grow-1">
                                        <input type="text" name="phone" data-type="phone" class="form-control form-control-sm form-control-solid" maxlength="36" placeholder="No. Telp : +62 ********" value="">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 hidden mb-5" data-type="penanggung_jawab">
                            <div class="w-lg-100 position-relative fv-row form-group mb-2">
                                <div class="input-group input-group-sm flex-nowrap">
                                    <span class="input-group-text">PJ/CP: </span>
                                    <div class="flex-grow-1">
                                        <input type="text" name="penanggung_jawab" data-type="penanggung_jawab" class="form-control form-control-sm form-control-solid" maxlength="120" placeholder="PJ/CP :...." value="">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 hidden" data-type="desc">
                            <div class="w-lg-100 position-relative fv-row form-group mb-2">
                                <div class="input-group input-group-sm flex-nowrap">
                                    <span class="input-group-text">Keterangan: </span>
                                    <div class="flex-grow-1">
                                        <textarea name="desc" data-type="desc" class="form-control form-textarea-hf form-control-sm form-control-solid unvalidate" maxlength="220" placeholder="Keterangan..."></textarea>
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