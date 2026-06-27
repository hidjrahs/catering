<!--begin::Sidebar-->
            <!-- d-none d-lg-flex flex-column flex-lg-row-auto w-100 w-lg-350px pt-7 -->
            <div class="hidden" 
                data-kt-drawer="true" 
                data-kt-drawer-name="inbox-aside" 
                data-kt-drawer-activate="{default: true, lg: false}" 
                data-kt-drawer-overlay="true" 
                data-kt-drawer-width="225px" 
                data-kt-drawer-direction="start" 
                data-kt-drawer-toggle="#kt_inbox_aside_toggle">
                <form action="{{$config['action']}}" method="post" enctype="multipart/form-data"id="kt_form_main" autocomplete="off">
                    @csrf
                    @method($config['method'])
                    <!--begin::Sticky aside-->
                    <div class="card card-flush mb-0" 
                        data-kt-sticky="true" 
                        data-kt-sticky-name="inbox-aside-sticky" 
                        data-kt-sticky-offset="{default: false, xl: '85px'}" 
                        data-kt-sticky-width="{lg: '350px'}" 
                        data-kt-sticky-right="auto" 
                        data-kt-sticky-top="0px" 
                        data-kt-sticky-animation="false" 
                        data-kt-sticky-zindex="95">
                        <!--begin::Aside content-->
                        <!--begin::Card header-->
                        <div class="card-header pt-7">
                            <!--begin::Title-->
                            <h3 class="card-title align-items-start flex-column">
                                <span class="card-label fw-bold text-dark">{!!$config['title-icon']!!}Tambah Data Karywan</span>
                            </h3>
                            <!--end::Title-->
                        </div>
                        <!--end::Card header-->
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12">
                                    <div class="mb-1 fv-row">
                                        <label for="name" class="required form-label">Nama Karyawan</label>
                                        <input type="text" name="name" class="form-control form-control-sm form-control-solid" maxlength="60" placeholder="Nama Karyawan">
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="mb-1 fv-row">
                                        <label for="phone" class="form-label">Ho Hp</label>
                                        <input type="text" name="phone" class="form-control form-control-sm form-control-solid" maxlength="36" placeholder="No. HP: .....">
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="mb-1 fv-row">
                                        <label for="gender" class="form-label">Gender</label>
                                        <select class="form-select form-select-solid form-select-sm" data-placeholder="Pilih Gender" name="gender" placeholder="Pilih Gender">
                                            <option value="L">Laki-Laki</option>
                                            <option value="P">Perempuan</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="mb-1 fv-row">
                                        <label for="address" class="form-label">Alamat</label>
                                        <textarea name="address" class="form-control form-control-sm form-control-solid form-textarea-hf unvalidate" maxlength="120" placeholder="Alamat"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--end::Aside content-->
                        <div class="card-footer d-grid">
                            <button type="submit" id="kt_form_submit" class="btn btn-primary font-weight-bold">
                                <span class="indicator-label">{{$config['button-submit']}}{!!$config['title-page-icon']!!}</span>
                                <span class="indicator-progress">{{$config['button-submit-wait']??'Tunggu sebentar'}}...
                                <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                            </button>
                        </div>
                    </div>
                    <!--end::Sticky aside-->
                </form>
            </div>
            <!--end::Sidebar-->