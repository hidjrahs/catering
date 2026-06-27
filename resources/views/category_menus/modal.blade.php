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
                        <div class="col-12 hidden mb-1" data-type="name">
                            <div class="w-lg-100 position-relative fv-row form-group mb-2">
                                <div class="input-group input-group-sm flex-nowrap">
                                    <span class="input-group-text">Kategori Menu: </span>
                                    <div class="flex-grow-1">
                                        <input type="text" name="name" data-type="name" class="form-control form-control-sm form-control-solid" maxlength="60"  placeholder="Kategori Menu" value="">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-5 hidden">
                            <div class="mb-1 fv-row">
                                <label for="is_quantity" class="required form-label">Custom Order</label>
                                <label class="form-check form-switch form-check-custom form-check-solid text-center min-h-20px">
                                    <input class="form-check-input w-100" name="is_quantity" type="checkbox" value="1"  />
                                </label>
                            </div>
                        </div>
                        <div class="col-12 hidden"><span>*) Aktifkan jika Kategori bisa custom porsi (CS)</span></div>
                        <div class="col-12 hidden mb-5" data-type="file_berkas">
                            <div class="w-lg-100 position-relative fv-row form-group mb-2 wrap-image">
                                <label for="file_berkas" class="form-label">Gambar:</label>
                                <div class="input-group input-group-sm flex-nowrap">
                                    <div class="flex-grow-1">
                                        <span class="label-preview text-gray-600 fw-semibold" data-place="filename">-</span>
                                        <input type="file" name="file_berkas" class="form-control form-control-sm unvalidate form-control-image form-control-solid" placeholder="Foto Kategori Menu" accept="image/*">
                                    </div>
                                </div>
                                <input type="checkbox" name="has_image" value="1" class="hidden unreaveal">
                                <div class="preview-image hidden mt-4">
                                    <span></span>
                                    <button class="btn btn-sm btn-danger rounded-0 clear-image" type="button">
                                        <span class="svg-icon svg-icon-2 me-0">
                                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M5 9C5 8.44772 5.44772 8 6 8H18C18.5523 8 19 8.44772 19 9V18C19 19.6569 17.6569 21 16 21H8C6.34315 21 5 19.6569 5 18V9Z" fill="currentColor"/>
                                                <path opacity="0.5" d="M5 5C5 4.44772 5.44772 4 6 4H18C18.5523 4 19 4.44772 19 5V5C19 5.55228 18.5523 6 18 6H6C5.44772 6 5 5.55228 5 5V5Z" fill="currentColor"/>
                                                <path opacity="0.5" d="M9 4C9 3.44772 9.44772 3 10 3H14C14.5523 3 15 3.44772 15 4V4H9V4Z" fill="currentColor"/>
                                            </svg> 
                                        </span>    
                                        Hapus
                                    </button>
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