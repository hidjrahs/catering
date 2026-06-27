<div class="modal fade" tabindex="-1" id="modal-master-update">
    <div class="modal-dialog modal-dialog-centered">
        <form action="{{ $config['action-update'] }}" method="{{$config['method-update']}}" enctype="multipart/form-data" id="kt_update_main" autocomplete="off">
                @csrf
                @method($config['method-update'])
                <input type="hidden" name="id">
            <div class="modal-content">
                <div class="modal-header p-4 pb-0">
                    <h3 class="modal-title">{!!$config['title-page-icon']!!} Update Menu <span data-replace="name"></span></h3>
                    <div class="btn btn-icon btn-sm btn-default ms-2" data-bs-dismiss="modal" aria-label="Close">x</div>
                </div>
                <div class="modal-body p-4 pb-2">
                    <div class=" row" id="modal-update">
                        <div class="col-6">
                            <div class="mb-1 fv-row">
                                <label for="category_menus_catering_id" class="required form-label">Kategori: </label>
                                <select class="form-select form-select-solid form-select-sm" 
                                    data-placeholder="Pilih Kategori" 
                                    name="category_menus_catering_id" 
                                    data-control="select2"
                                    data-allow-clear="true"
                                    placeholder="Pilih Kategori">
                                </select>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="mb-1 fv-row">
                                <label for="name" class="required form-label">Nama Menu</label>
                                <input type="text" name="name" class="form-control form-control-sm form-control-solid" maxlength="60" placeholder="Nama Menu: ....,">
                            </div>
                        </div>
                        <div class="col-5">
                            <div class="mb-1 fv-row">
                                <label for="porsi_standard" class="form-label">Porsi Standard</label>
                                <input type="text" name="porsi_standard" class="form-control form-control-sm form-control-solid" maxlength="36" placeholder="Porsi Standard: .....">
                            </div>
                        </div>
                        <div class="col-5">
                            <div class="mb-1 fv-row">
                                <label for="selling_price" class="form-label">Estimasi Harga</label>
                                <input type="text" name="selling_price" class="form-control form-control-sm form-control-solid" maxlength="36" placeholder="Est. Harga: .....">
                            </div>
                        </div>
                        <div class="col-2">
                            <div class="mb-1 fv-row">
                                <label for="porsi_standard" class="form-label">Aktif</label>
                                <label class="form-check form-switch form-check-custom form-check-solid text-center min-h-20px">
                                    <input class="form-check-input w-100" name="is_active" type="checkbox" value="1" checked="checked" />
                                </label>
                            </div>
                        </div>
                        <div class="separator separator-dashed my-3"></div>
                        <div class="col-12 col-sm-12 mb-2">
                            <label class="form-label"> Daftar Barang/Bahan Baku: <span data-ref="total_ingredients">0</span> Bahan <span data-ref="total_ingredients_label"></span></label>
                            <div class="mh-200px scroll-y" data-list="ingredient_list">
                                <div class="border border-dashed border-gray-300 rounded p-5 py-10 text-center ingredients_none">{!!$config['title-icon']!!}Barang/Bahan Baku belum di isi</div>
                            </div> 
                        </div>

                    </div>
                </div>
                <div class="modal-footer p-4 pt-2">
                    <div class="col-12 mb-2">
                        <div class="d-flex align-items-center w-100 w-sm-auto ">
                            <select class="form-select form-select-solid form-select-sm w-175px me-2 unvalidate"
                                name="type_ingredients" 
                                data-change="type_ingredients"
                                placeholder="Pilih Jenis">
                                <option value="barang">Barang/Bahan Baku</option>
                                <option value="label">Label</option>
                            </select>
                            <div class="position-relative flex-fill">
                                <input type="text" name="ingredient_label" class="form-control form-control-sm form-control-solid unvalidate hidden" maxlength="36" placeholder="Label: .....">
                                <select class="form-select form-select-solid form-select-sm unvalidate" 
                                    data-placeholder="Barang/Bahan Baku:..." 
                                    name="ingredient_item" 
                                    placeholder="Barang/Bahan Baku">
                                </select>
                            </div>
                            <span class="btn btn-light-success btn-sm ms-2 px-3" data-add="ingredient_list"><i class="fas fa-plus"></i>Tambah</span>
                        </div>
                    </div>
                    <div class="col-12 col-sm-12 mb-2 fv-row">
                        <label for="name" class="required form-label">Paket: </label>
                        <select class="form-select form-select-solid form-select-sm" 
                            data-placeholder="Pilih Paket" 
                            name="packet_catering_id[]" 
                            data-control="select2"
                            data-allow-clear="true"
                            placeholder="Pilih Paket"
                            multiple>
                        </select>
                        <!-- <textarea id="desc" name="desc" class="form-control text-gray-800 fw-bold form-control-sm mh-150px minh-100px unvalidate" placeholder="Keterangan :..."></textarea> -->
                    </div>
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