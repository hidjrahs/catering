<div class="modal fade" tabindex="-1" id="modal-province">
    <div class="modal-dialog modal-dialog-centered">
        <form action="#" data-action="{{ $config['action'] }}" method="{{$config['method']}}" id="kt_province" autocomplete="off">
                @csrf
                @method($config['method'])
                <input type="hidden" name="id">
            <div class="modal-content">
                <div class="modal-header p-4">
                    <h3 class="modal-title">{!!$config['title-page-icon']!!} <span data-replace="form">Tambah</span> Referensi Provinsi</h3>
                    <div class="btn btn-icon btn-sm btn-default ms-2" data-bs-dismiss="modal" aria-label="Close">x</div>
                </div>
                <div class="modal-body p-4">
                    <div class="row">
                        <div class="col-12 ">
                            <div class="w-lg-100 position-relative fv-row form-group">
                                <div class="input-group input-group-sm flex-nowrap">
                                    <span class="input-group-text required">Nama Provinsi: </span>
                                    <div class="flex-grow-1">
                                        <input type="text" name="name" data-type="name" class="form-control form-control-sm form-control-solid" maxlength="120"  placeholder="Nama Provinsi" value="">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer p-4">
                    <button type="submit" id="kt_province_submit" class="btn btn-primary w-100">
                        <span class="indicator-label">Simpan Referensi Provinsi {!!$config['title-page-icon']!!}</span>
                        <span class="indicator-progress">{{$config['button-update-wait']??'Tunggu sebentar'}}...
                        <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
<div class="modal fade" tabindex="-1" id="modal-cities">
    <div class="modal-dialog modal-dialog-centered">
        <form action="#" data-action="{{ $config['action'] }}" method="{{$config['method']}}" id="kt_cities" autocomplete="off">
                @csrf
                @method($config['method'])
                <input type="hidden" name="id">
            <div class="modal-content">
                <div class="modal-header p-4">
                    <h3 class="modal-title">{!!$config['title-page-icon']!!} <span data-replace="form">Tambah</span> Referensi Kota/Kab</h3>
                    <div class="btn btn-icon btn-sm btn-default ms-2" data-bs-dismiss="modal" aria-label="Close">x</div>
                </div>
                <div class="modal-body p-4">
                    <div class="row">
                        <div class="col-12 mb-4">
                            <div class="w-lg-100 position-relative fv-row form-group">
                                <div class="input-group input-group-sm flex-nowrap">
                                    <span class="input-group-text required">Provinsi: </span>
                                    <div class="flex-grow-1">
                                        <select class="form-select form-select-solid form-select-sm province-select"
                                            name="province_id" 
                                            placeholder="Provinsi:...">
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 ">
                            <div class="w-lg-100 position-relative fv-row form-group">
                                <div class="input-group input-group-sm flex-nowrap">
                                    <span class="input-group-text required">Nama Kota/Kab: </span>
                                    <div class="flex-grow-1">
                                        <input type="text" name="name" data-type="name" class="form-control form-control-sm form-control-solid" maxlength="120"  placeholder="Nama Kota/Kab:..." value="">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer p-4">
                    <button type="submit" id="kt_cities_submit" class="btn btn-primary w-100">
                        <span class="indicator-label">Simpan Referensi Kota/Kab {!!$config['title-page-icon']!!}</span>
                        <span class="indicator-progress">{{$config['button-update-wait']??'Tunggu sebentar'}}...
                        <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
<div class="modal fade" tabindex="-1" id="modal-districts">
    <div class="modal-dialog modal-dialog-centered">
        <form action="#" data-action="{{ $config['action'] }}" method="{{$config['method']}}" id="kt_districts" autocomplete="off">
                @csrf
                @method($config['method'])
                <input type="hidden" name="id">
            <div class="modal-content">
                <div class="modal-header p-4">
                    <h3 class="modal-title">{!!$config['title-page-icon']!!} <span data-replace="form">Tambah</span> Referensi Kecamatan</h3>
                    <div class="btn btn-icon btn-sm btn-default ms-2" data-bs-dismiss="modal" aria-label="Close">x</div>
                </div>
                <div class="modal-body p-4">
                    <div class="row">
                        <div class="col-12 mb-4">
                            <div class="w-lg-100 position-relative fv-row form-group">
                                <div class="input-group input-group-sm flex-nowrap">
                                    <span class="input-group-text required">Provinsi: </span>
                                    <div class="flex-grow-1">
                                        <select class="form-select form-select-solid form-select-sm province-select"
                                            name="province_id" 
                                            placeholder="Provinsi:...">
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 ">
                            <div class="w-lg-100 position-relative fv-row form-group">
                                <div class="input-group input-group-sm flex-nowrap">
                                    <span class="input-group-text required">Kota/Kab: </span>
                                    <div class="flex-grow-1">
                                        <select class="form-select form-select-solid form-select-sm cities-select"
                                            name="cities_id" 
                                            placeholder="Kota/Kab:...">
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 ">
                            <div class="w-lg-100 position-relative fv-row form-group">
                                <div class="input-group input-group-sm flex-nowrap">
                                    <span class="input-group-text required">Nama Kecamatan: </span>
                                    <div class="flex-grow-1">
                                        <input type="text" name="name" data-type="name" class="form-control form-control-sm form-control-solid" maxlength="120"  placeholder="Nama Kota/Kab:..." value="">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer p-4">
                    <button type="submit" id="kt_districts_submit" class="btn btn-primary w-100">
                        <span class="indicator-label">Simpan Referensi Kota/Kab {!!$config['title-page-icon']!!}</span>
                        <span class="indicator-progress">{{$config['button-update-wait']??'Tunggu sebentar'}}...
                        <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
<div class="modal fade" tabindex="-1" id="modal-vilages">
    <div class="modal-dialog modal-dialog-centered">
        <form action="#" data-action="{{ $config['action'] }}" method="{{$config['method']}}" id="kt_vilages" autocomplete="off">
                @csrf
                @method($config['method'])
                <input type="hidden" name="id">
            <div class="modal-content">
                <div class="modal-header p-4">
                    <h3 class="modal-title">{!!$config['title-page-icon']!!} <span data-replace="form">Tambah</span> Referensi Kelurahan/Desa</h3>
                    <div class="btn btn-icon btn-sm btn-default ms-2" data-bs-dismiss="modal" aria-label="Close">x</div>
                </div>
                <div class="modal-body p-4">
                    <div class="row">
                        <div class="col-12 mb-4">
                            <div class="w-lg-100 position-relative fv-row form-group">
                                <div class="input-group input-group-sm flex-nowrap">
                                    <span class="input-group-text required">Provinsi: </span>
                                    <div class="flex-grow-1">
                                        <select class="form-select form-select-solid form-select-sm province-select"
                                            name="province_id" 
                                            placeholder="Provinsi:...">
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 ">
                            <div class="w-lg-100 position-relative fv-row form-group">
                                <div class="input-group input-group-sm flex-nowrap">
                                    <span class="input-group-text required">Kota/Kab: </span>
                                    <div class="flex-grow-1">
                                        <select class="form-select form-select-solid form-select-sm cities-select"
                                            name="cities_id" 
                                            placeholder="Kota/Kab:...">
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 ">
                            <div class="w-lg-100 position-relative fv-row form-group">
                                <div class="input-group input-group-sm flex-nowrap">
                                    <span class="input-group-text required">Kecamatan: </span>
                                    <div class="flex-grow-1">
                                        <select class="form-select form-select-solid form-select-sm districts-select"
                                            name="districts_id" 
                                            placeholder="Kota/Kab:...">
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 ">
                            <div class="w-lg-100 position-relative fv-row form-group">
                                <div class="input-group input-group-sm flex-nowrap">
                                    <span class="input-group-text required">Nama Kelurahan/Desa: </span>
                                    <div class="flex-grow-1">
                                        <input type="text" name="name" data-type="name" class="form-control form-control-sm form-control-solid" maxlength="120"  placeholder="Nama Kota/Kab:..." value="">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer p-4">
                    <button type="submit" id="kt_vilages_submit" class="btn btn-primary w-100">
                        <span class="indicator-label">Simpan Referensi Kota/Kab {!!$config['title-page-icon']!!}</span>
                        <span class="indicator-progress">{{$config['button-update-wait']??'Tunggu sebentar'}}...
                        <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>