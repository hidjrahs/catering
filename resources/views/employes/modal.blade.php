<div class="modal fade" tabindex="-1" id="modal-master-update">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <form action="{{ $config['action-update'] }}" class="w-100" method="{{$config['method-update']}}" enctype="multipart/form-data" id="kt_update_main" autocomplete="off">
                @csrf
                @method($config['method-update'])
                <input type="hidden" name="id">
            <div class="modal-content">
                <div class="modal-header p-4">
                    <h3 class="modal-title">{!!$config['title-page-icon']!!} Update Karyawan</h3>
                    <div class="btn btn-icon btn-sm btn-default ms-2" data-bs-dismiss="modal" aria-label="Close">x</div>
                </div>
                <div class="modal-body px-4 py-0">
                    <div class="bg-light px-2 py-4 col-md-12 mb-2 skeleton load"></div>
                    <div class="bg-light px-2 py-4 col-md-12 skeleton load"></div>
                    <div class="col-md-12 hidden" id="modal-update">
                        <ul class="nav nav-pills nav-pills-custom row position-relative mx-0 mb-4" role="tablist">
                            <li class="nav-item col-2 mx-0 p-0" role="presentation">
                                <a class="nav-link d-flex justify-content-center w-100 border-0 h-100 active" data-bs-toggle="pill" href="#kt_tab_profile_update" aria-selected="true" role="tab">
                                    <span class="nav-text text-gray-800 fw-bold fs-6 mb-3">Data Pribadi</span>
                                    <span class="bullet-custom position-absolute z-index-2 bottom-0 w-100 h-4px bg-primary rounded"></span>
                                </a>
                            </li>
                            <li class="nav-item col-2 mx-0 px-0" role="presentation">
                                <a class="nav-link d-flex justify-content-center w-100 border-0 h-100" data-bs-toggle="pill" href="#kt_tab_education_update" aria-selected="false" role="tab" tabindex="-1">
                                    <span class="nav-text text-gray-800 fw-bold fs-6 mb-3">Pendidikan</span>
                                    <span class="bullet-custom position-absolute z-index-2 bottom-0 w-100 h-4px bg-primary rounded"></span>
                                </a>
                            </li>
                            <li class="nav-item col-2 mx-0 px-0" role="presentation">
                                <a class="nav-link d-flex justify-content-center w-100 border-0 h-100" data-bs-toggle="pill" href="#kt_tab_families_update" aria-selected="false" role="tab" tabindex="-1">
                                    <span class="nav-text text-gray-800 fw-bold fs-6 mb-3">Keluarga</span>
                                    <span class="bullet-custom position-absolute z-index-2 bottom-0 w-100 h-4px bg-primary rounded"></span>
                                </a>
                            </li>
                            <li class="nav-item col-2 mx-0 px-0" role="presentation">
                                <a class="nav-link d-flex justify-content-center w-100 border-0 h-100" data-bs-toggle="pill" href="#kt_tab_emergencies_update" aria-selected="false" role="tab" tabindex="-1">
                                    <span class="nav-text text-gray-800 fw-bold fs-6 mb-3">Kontak Darurat</span>
                                    <span class="bullet-custom position-absolute z-index-2 bottom-0 w-100 h-4px bg-primary rounded"></span>
                                </a>
                            </li>
                            <li class="nav-item col-2 mx-0 px-0" role="presentation">
                                <a class="nav-link d-flex justify-content-center w-100 border-0 h-100" data-bs-toggle="pill" href="#kt_tab_users_update" aria-selected="false" role="tab" tabindex="-1">
                                    <span class="nav-text text-gray-800 fw-bold fs-6 mb-3">User Akses</span>
                                    <span class="bullet-custom position-absolute z-index-2 bottom-0 w-100 h-4px bg-primary rounded"></span>
                                </a>
                            </li>
                            <span class="position-absolute z-index-1 bottom-0 w-100 h-4px bg-light rounded"></span>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane min-h-400px fade active show" role="tabpanel" id="kt_tab_profile_update">
                                <div class="row">
                                    <div class="col-12 col-sm-4">
                                        <div class="form-floating fv-row mb-4">
                                            <input type="text" name="name" class="form-control form-control-sm form-control-solid" maxlength="60"  placeholder="Nama Karyawan" value="">
                                            <label for="name">Nama Karyawan :...</label>
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-4">
                                        <div class="form-floating fv-row mb-4">
                                            <input type="text" name="division" class="form-control form-control-sm form-control-solid unvalidate" maxlength="60"  placeholder="Jabatan/Divisi :..." value="">
                                            <label for="division">Jabatan/Divisi :...</label>
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-4">
                                        <div class="form-floating fv-row mb-4">
                                            <input type="text" name="national_id" class="form-control form-control-sm form-control-solid unvalidate" maxlength="36"  placeholder="No. KTP/SIM :..." value="">
                                            <label for="national_id">No. KTP/SIM :...</label>
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-4">
                                        <div class="form-floating fv-row mb-4">
                                            <select class="form-select form-select-solid form-select-sm unvalidate" data-placeholder="Pilih Gender" name="gender" placeholder="Pilih Gender">
                                                <option value="L">Laki-Laki</option>
                                                <option value="P">Perempuan</option>
                                            </select>
                                            <label for="gender">Gender :...</label>
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-4">
                                        <div class="form-floating fv-row mb-4">
                                            <input type="text" name="phone" class="form-control form-control-sm form-control-solid unvalidate" maxlength="36"  placeholder="No HP/Telp :..." value="">
                                            <label for="phone">No HP/Telp :...</label>
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-4">
                                        <div class="form-floating fv-row mb-4">
                                            <input type="text" name="work_since" class="form-control form-control-sm form-control-solid unvalidate" maxlength="60"  placeholder="Bekerja Sejak :..." value="">
                                            <label for="work_since">Bekerja Sejak :...</label>
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-4">
                                        <div class="form-floating fv-row mb-4">
                                            <input type="text" name="birth_place_date" class="form-control form-control-sm form-control-solid unvalidate" maxlength="120"  placeholder="Tempat / Tgl Lahir :..." value="">
                                            <label for="birth_place_date">Tempat / Tgl Lahir :...</label>
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-4">
                                        <div class="form-floating fv-row mb-4">
                                            <input type="text" name="height_cm" class="form-control form-control-sm form-control-solid unvalidate" maxlength="120"  placeholder="Tinggi Badan :..." value="">
                                            <label for="height_cm">Tinggi Badan :...</label>
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-4">
                                        <div class="form-floating fv-row mb-4">
                                            <input type="text" name="weight_kg" class="form-control form-control-sm form-control-solid unvalidate" maxlength="120"  placeholder="Berat Badan :..." value="">
                                            <label for="weight_kg">Berat Badan :...</label>
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-4">
                                        <div class="form-floating fv-row mb-4">
                                            <select class="form-select form-select-solid form-select-sm" data-placeholder="Pilih Agama" name="religion" placeholder="Pilih Agama">
                                                <option value="islam">Islam</option>
                                                <option value="katolik">Katolik</option>
                                                <option value="protestan">Protestan</option>
                                                <option value="hindu">Hindu</option>
                                                <option value="budha">Budha</option>
                                                <option value="konghucu">Konghucu</option>
                                                <option value="lain_lain">Lain-lain</option>
                                            </select>
                                            <label for="name">Agama :...</label>
                                        </div>
                                        <div class="form-floating fv-row mb-4">
                                            <select class="form-select form-select-solid form-select-sm" data-placeholder="Pilih Status" name="status" placeholder="Pilih Status">
                                                <option value="lajang">Lajang</option>
                                                <option value="menikah">Menikah</option>
                                                <option value="Janda/Duda">Janda / Duda</option>
                                            </select>
                                            <label for="status">Status :...</label>
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-8">
                                        <div class="form-floating fv-row mb-4">
                                            <textarea name="address" class="form-control form-textarea-hf form-control-sm form-control-solid unvalidate" maxlength="120" placeholder="Alamat :..."></textarea>
                                            <label for="address">Alamat :...</label>
                                        </div>

                                    </div>
                                    <div class="separator separator-dashed mb-2"></div>
                                    <div class="col-12 col-sm-4">
                                        <div class="form-floating fv-row mb-4">
                                            <input type="text" name="contract_end" class="form-control form-control-sm form-control-solid unvalidate" maxlength="120"  placeholder="Akhir Kontrak :..." value="">
                                            <label for="contract_end">Akhir Kontrak :...</label>
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-8">
                                        <div class="form-floating fv-row mb-4">
                                            <textarea name="interview_result" class="form-control form-textarea-hf form-control-sm form-control-solid unvalidate" maxlength="120" placeholder="Hasil Interview :..."></textarea>
                                            <label for="interview_result">Hasil Interview :...</label>
                                        </div>

                                    </div>
                                    <!-- Kontrak Kerja -->
                                </div>
                            </div>
                            <div class="tab-pane min-h-400px fade mh-400px scroll-y pe-1" role="tabpanel" id="kt_tab_education_update">
                                <table class="table align-middle  fs-6 gy-3 gs-3 table-sm table-row-bordered border">
                                    <thead>
                                        <tr class="fw-bold text-muted bg-light">
                                            <th class="w-200px">Pendidikan</th>
                                            <th>Nama Sekolah/Perguruan</th>
                                            <th>Kota/Kabupaten</th>
                                            <th>Jurusan</th>
                                            <th class="text-end w-125px">Tahun Masuk</th>
                                            <th class="text-end pe-3 w-125px">Tahun Lulus</th>
                                        </tr>
                                    </thead>
                                    <tbody class="fw-bold text-gray-600">
                                        <tr data-clone="educations" class="data-item hidden" data-max="5">
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <a href="javascript:;" class="btn btn-sm btn-light-danger btn-active-danger px-2 py-1 text-center me-3 hidden" disabled data-deleted="data-item">
                                                        <i class="fa fa-trash p-0"></i>
                                                    </a>
                                                    <select class="form-select form-select-sm unvalidate" data-placeholder="Pilih Pendidikan" name="educations[1][education_level]" data-input="education_level" placeholder="Pilih Pendidikan">
                                                        <option value="">Pilih Pendidikan</option>
                                                        @foreach($data['education_level'] as $edukey=>$eduval)
                                                            <option value="{{$edukey}}">{{$eduval}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </td>
                                            <td>
                                                <input type="text" name="educations[1][school_name]" data-input="school_name" class="form-control form-control-sm unvalidate" maxlength="70"  placeholder="Nama Sekolah/Perguruan" value="">
                                            </td>
                                            <td>
                                                <input type="text" name="educations[1][city]" data-input="city" class="form-control form-control-sm unvalidate" maxlength="70"  placeholder="Kota" value="">
                                            </td>
                                            <td>
                                                <input type="text" name="educations[1][major]" data-input="major" class="form-control form-control-sm unvalidate" maxlength="70"  placeholder="Jurusan" value="">
                                            </td>
                                            <td>
                                                <input type="text" name="educations[1][year_start]" data-input="year_start" class="form-control form-control-sm text-end unvalidate" maxlength="4"  placeholder="Tahun Masuk" value="">
                                            </td>
                                            <td>
                                                <input type="text" name="educations[1][year_graduated]" data-input="year_graduated" class="form-control form-control-sm text-end unvalidate" maxlength="4"  placeholder="Tahun Lulus" value="">
                                            </td>
                                        </tr>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td colspan="6" class="text-end p-0 bg-light">
                                                <button type="button" class="btn btn-success btn-sm rounded-0" data-add="educations">
                                                    <span class="indicator-label">{!!$config['title-page-icon']!!} Tambah Riwayat Pendidikan</span>
                                                    <span class="indicator-progress">Sudah melebihi batas maksimal pendidikan</span>
                                                </button>
                                            </td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                            <div class="tab-pane min-h-400px fade mh-400px scroll-y pe-1" role="tabpanel" id="kt_tab_families_update">
                                <table class="table align-middle  fs-6 gy-3 gs-3 table-sm table-row-bordered border">
                                    <thead>
                                        <tr class="fw-bold text-muted bg-light">
                                            <th class="w-200px">Nama</th>
                                            <th>Hubungan</th>
                                            <th>Tempat/Tgl Lahir</th>
                                            <th>Gender</th>
                                            <th>Pendidikan</th>
                                        </tr>
                                    </thead>
                                    <tbody class="fw-bold text-gray-600">
                                        <tr data-clone="families" class="data-item hidden" data-max="10">
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <a href="javascript:;" class="btn btn-sm btn-light-danger btn-active-danger px-2 py-1 text-center me-3 hidden" disabled data-deleted="data-item">
                                                        <i class="fa fa-trash p-0"></i>
                                                    </a>
                                                    <input type="text" name="families[1][name]" data-input="name" class="form-control form-control-sm unvalidate" maxlength="70"  placeholder="Nama" value="">
                                                </div>
                                            </td>
                                            <td>
                                                <input type="text" name="families[1][relation]" data-input="relation" class="form-control form-control-sm unvalidate" maxlength="70"  placeholder="Hubungan" value="">
                                            </td>
                                            <td>
                                                <input type="text" name="families[1][birth_place_date]" data-input="birth_place_date" class="form-control form-control-sm unvalidate" maxlength="70"  placeholder="Tempat/Tgl. Lahir" value="">
                                            </td>
                                            <td>
                                                <select class="form-select form-select-sm unvalidate" data-placeholder="Pilih Gender" name="families[1][gender]" data-input="gender" placeholder="Pilih Gender">
                                                    <option value="L">Laki-Laki</option>
                                                    <option value="P">Perempuan</option>
                                                </select>
                                            </td>
                                            <td>
                                                <select class="form-select form-select-sm unvalidate" data-placeholder="Pilih Pendidikan" name="families[1][education]" data-input="education" placeholder="Pilih Pendidikan">
                                                <option value="">Pilih Pendidikan</option>
                                                @foreach($data['education_level'] as $edukey=>$eduval)
                                                    <option value="{{$edukey}}">{{$eduval}}</option>
                                                @endforeach
                                            </select>
                                            </td>
                                        </tr>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td colspan="6" class="text-end p-0 bg-light">
                                                <button type="button" class="btn btn-success btn-sm rounded-0" data-add="families">
                                                    <span class="indicator-label">{!!$config['title-page-icon']!!} Tambah Riwayat Keluarga</span>
                                                    <span class="indicator-progress">Sudah melebihi batas maksimal Keluarga</span>
                                                </button>
                                            </td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                            <div class="tab-pane min-h-400px fade" role="tabpanel" id="kt_tab_emergencies_update">
                                <table class="table align-middle  fs-6 gy-3 gs-3 table-sm table-row-bordered border">
                                    <thead>
                                        <tr class="fw-bold text-muted bg-light">
                                            <th class="w-200px">Nama</th>
                                            <th>Hubungan</th>
                                            <th>Alamat</th>
                                            <th>No. HP</th>
                                        </tr>
                                    </thead>
                                    <tbody class="fw-bold text-gray-600">
                                        <tr data-clone="emergencies" class="data-item hidden" data-max="3">
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <a href="javascript:;" class="btn btn-sm btn-light-danger btn-active-danger px-2 py-1 text-center me-3 hidden" disabled data-deleted="data-item">
                                                        <i class="fa fa-trash p-0"></i>
                                                    </a>
                                                    <input type="text" name="emergencies[1][name]" data-input="name" class="form-control form-control-sm unvalidate" maxlength="70"  placeholder="Nama" value="">
                                                </div>
                                            </td>
                                            <td>
                                                <input type="text" name="emergencies[1][relation]" data-input="relation" class="form-control form-control-sm unvalidate" maxlength="70"  placeholder="Hubungan" value="">
                                            </td>
                                            <td>
                                                <input type="text" name="emergencies[1][address]" data-input="address" class="form-control form-control-sm unvalidate" maxlength="120" placeholder="Alamat...">
                                            </td>
                                            <td>
                                                <input type="text" name="emergencies[1][phone]" data-input="phone" class="form-control form-control-sm unvalidate" maxlength="70"  placeholder="No. Telp" value="">
                                            </td>
                                        </tr>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td colspan="6" class="text-end p-0 bg-light">
                                                <button type="button" class="btn btn-success btn-sm rounded-0" data-add="emergencies">
                                                    <span class="indicator-label">{!!$config['title-page-icon']!!} Tambah Riwayat Keluarga</span>
                                                    <span class="indicator-progress">Sudah melebihi batas maksimal Keluarga</span>
                                                </button>
                                            </td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                            <div class="tab-pane min-h-400px fade" role="tabpanel" id="kt_tab_users_update">
                                <div class="row">
                                    <div class="col-12 col-sm-4">
                                        <div class="form-floating fv-row mb-4">
                                            <input type="hidden" name="user_id">
                                            <input type="text" name="username" class="form-control form-control-sm form-control-solid unvalidate" maxlength="60"  placeholder="User Name" value="">
                                            <label for="username">User name :...</label>
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-4">
                                        <div class="form-floating fv-row mb-4">
                                            <input type="text" name="email" class="form-control form-control-sm form-control-solid unvalidate" maxlength="60"  placeholder="Email" value="">
                                            <label for="email">Email :...</label>
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-4">
                                        <div class="form-floating fv-row mb-4">
                                            <input type="password" name="password" class="form-control form-control-sm form-control-solid unvalidate" placeholder="Password" value="">
                                            <label for="password">Password :...</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer p-4">
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
<div class="modal fade" tabindex="-1" id="modal-master-main">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <form action="{{ $config['action'] }}" class="w-100" method="{{$config['method']}}" enctype="multipart/form-data" id="kt_form_main" autocomplete="off">
                @csrf
                @method($config['method'])
            <div class="modal-content">
                <div class="modal-header p-4">
                    <h3 class="modal-title">{!!$config['title-page-icon']!!} Tambah Karyawan Baru</h3>
                    <div class="btn btn-icon btn-sm btn-default ms-2" data-bs-dismiss="modal" aria-label="Close">x</div>
                </div>
                <div class="modal-body px-4 py-0">
                    <ul class="nav nav-pills nav-pills-custom row position-relative mx-0 mb-4" role="tablist">
                        <li class="nav-item col-2 mx-0 p-0" role="presentation">
                            <a class="nav-link d-flex justify-content-center w-100 border-0 h-100 active" data-bs-toggle="pill" href="#kt_tab_profile" aria-selected="true" role="tab">
                                <span class="nav-text text-gray-800 fw-bold fs-6 mb-3">Data Pribadi</span>
                                <span class="bullet-custom position-absolute z-index-2 bottom-0 w-100 h-4px bg-primary rounded"></span>
                            </a>
                        </li>
                        <li class="nav-item col-2 mx-0 px-0" role="presentation">
                            <a class="nav-link d-flex justify-content-center w-100 border-0 h-100" data-bs-toggle="pill" href="#kt_tab_education" aria-selected="false" role="tab" tabindex="-1">
                                <span class="nav-text text-gray-800 fw-bold fs-6 mb-3">Pendidikan</span>
                                <span class="bullet-custom position-absolute z-index-2 bottom-0 w-100 h-4px bg-primary rounded"></span>
                            </a>
                        </li>
                        <li class="nav-item col-2 mx-0 px-0" role="presentation">
                            <a class="nav-link d-flex justify-content-center w-100 border-0 h-100" data-bs-toggle="pill" href="#kt_tab_families" aria-selected="false" role="tab" tabindex="-1">
                                <span class="nav-text text-gray-800 fw-bold fs-6 mb-3">Keluarga</span>
                                <span class="bullet-custom position-absolute z-index-2 bottom-0 w-100 h-4px bg-primary rounded"></span>
                            </a>
                        </li>
                        <li class="nav-item col-2 mx-0 px-0" role="presentation">
                            <a class="nav-link d-flex justify-content-center w-100 border-0 h-100" data-bs-toggle="pill" href="#kt_tab_emergencies" aria-selected="false" role="tab" tabindex="-1">
                                <span class="nav-text text-gray-800 fw-bold fs-6 mb-3">Kontak Darurat</span>
                                <span class="bullet-custom position-absolute z-index-2 bottom-0 w-100 h-4px bg-primary rounded"></span>
                            </a>
                        </li>
                        <li class="nav-item col-2 mx-0 px-0" role="presentation">
                            <a class="nav-link d-flex justify-content-center w-100 border-0 h-100" data-bs-toggle="pill" href="#kt_tab_users" aria-selected="false" role="tab" tabindex="-1">
                                <span class="nav-text text-gray-800 fw-bold fs-6 mb-3">User Akses</span>
                                <span class="bullet-custom position-absolute z-index-2 bottom-0 w-100 h-4px bg-primary rounded"></span>
                            </a>
                        </li>
                        <span class="position-absolute z-index-1 bottom-0 w-100 h-4px bg-light rounded"></span>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane min-h-400px fade active show" role="tabpanel" id="kt_tab_profile">
                            <div class="row">
                                <div class="col-12 col-sm-4">
                                    <div class="form-floating fv-row mb-4">
                                        <input type="text" name="name" class="form-control form-control-sm form-control-solid" maxlength="60"  placeholder="Nama Karyawan" value="">
                                        <label for="name">Nama Karyawan :...</label>
                                    </div>
                                </div>
                                <div class="col-12 col-sm-4">
                                    <div class="form-floating fv-row mb-4">
                                        <input type="text" name="division" class="form-control form-control-sm form-control-solid unvalidate" maxlength="60"  placeholder="Jabatan/Divisi :..." value="">
                                        <label for="division">Jabatan/Divisi :...</label>
                                    </div>
                                </div>
                                <div class="col-12 col-sm-4">
                                    <div class="form-floating fv-row mb-4">
                                        <input type="text" name="national_id" class="form-control form-control-sm form-control-solid unvalidate" maxlength="36"  placeholder="No. KTP/SIM :..." value="">
                                        <label for="national_id">No. KTP/SIM :...</label>
                                    </div>
                                </div>
                                <div class="col-12 col-sm-4">
                                    <div class="form-floating fv-row mb-4">
                                        <select class="form-select form-select-solid form-select-sm unvalidate" data-placeholder="Pilih Gender" name="gender" placeholder="Pilih Gender">
                                            <option value="L">Laki-Laki</option>
                                            <option value="P">Perempuan</option>
                                        </select>
                                        <label for="gender">Gender :...</label>
                                    </div>
                                </div>
                                <div class="col-12 col-sm-4">
                                    <div class="form-floating fv-row mb-4">
                                        <input type="text" name="phone" class="form-control form-control-sm form-control-solid unvalidate" maxlength="36"  placeholder="No HP/Telp :..." value="">
                                        <label for="phone">No HP/Telp :...</label>
                                    </div>
                                </div>
                                <div class="col-12 col-sm-4">
                                    <div class="form-floating fv-row mb-4">
                                        <input type="text" name="work_since" class="form-control form-control-sm form-control-solid unvalidate" maxlength="60"  placeholder="Bekerja Sejak :..." value="">
                                        <label for="work_since">Bekerja Sejak :...</label>
                                    </div>
                                </div>
                                <div class="col-12 col-sm-4">
                                    <div class="form-floating fv-row mb-4">
                                        <input type="text" name="birth_place_date" class="form-control form-control-sm form-control-solid unvalidate" maxlength="120"  placeholder="Tempat / Tgl Lahir :..." value="">
                                        <label for="birth_place_date">Tempat / Tgl Lahir :...</label>
                                    </div>
                                </div>
                                <div class="col-12 col-sm-4">
                                    <div class="form-floating fv-row mb-4">
                                        <input type="text" name="height_cm" class="form-control form-control-sm form-control-solid unvalidate" maxlength="120"  placeholder="Tinggi Badan :..." value="">
                                        <label for="height_cm">Tinggi Badan :...</label>
                                    </div>
                                </div>
                                <div class="col-12 col-sm-4">
                                    <div class="form-floating fv-row mb-4">
                                        <input type="text" name="weight_kg" class="form-control form-control-sm form-control-solid unvalidate" maxlength="120"  placeholder="Berat Badan :..." value="">
                                        <label for="weight_kg">Berat Badan :...</label>
                                    </div>
                                </div>
                                <div class="col-12 col-sm-4">
                                    <div class="form-floating fv-row mb-4">
                                        <select class="form-select form-select-solid form-select-sm" data-placeholder="Pilih Agama" name="religion" placeholder="Pilih Agama">
                                            <option value="islam">Islam</option>
                                            <option value="katolik">Katolik</option>
                                            <option value="protestan">Protestan</option>
                                            <option value="hindu">Hindu</option>
                                            <option value="budha">Budha</option>
                                            <option value="konghucu">Konghucu</option>
                                            <option value="lain_lain">Lain-lain</option>
                                        </select>
                                        <label for="name">Agama :...</label>
                                    </div>
                                    <div class="form-floating fv-row mb-4">
                                        <select class="form-select form-select-solid form-select-sm" data-placeholder="Pilih Status" name="status" placeholder="Pilih Status">
                                            <option value="lajang">Lajang</option>
                                            <option value="menikah">Menikah</option>
                                            <option value="Janda/Duda">Janda / Duda</option>
                                        </select>
                                        <label for="status">Status :...</label>
                                    </div>
                                </div>
                                <div class="col-12 col-sm-8">
                                    <div class="form-floating fv-row mb-4">
                                        <textarea name="address" class="form-control form-textarea-hf form-control-sm form-control-solid unvalidate" maxlength="120" placeholder="Alamat :..."></textarea>
                                        <label for="address">Alamat :...</label>
                                    </div>

                                </div>
                                <div class="separator separator-dashed mb-2"></div>
                                <div class="col-12 col-sm-4">
                                    <div class="form-floating fv-row mb-4">
                                        <input type="text" name="contract_end" class="form-control form-control-sm form-control-solid unvalidate" maxlength="120"  placeholder="Akhir Kontrak :..." value="">
                                        <label for="contract_end">Akhir Kontrak :...</label>
                                    </div>
                                </div>
                                <div class="col-12 col-sm-8">
                                    <div class="form-floating fv-row mb-4">
                                        <textarea name="interview_result" class="form-control form-textarea-hf form-control-sm form-control-solid unvalidate" maxlength="120" placeholder="Hasil Interview :..."></textarea>
                                        <label for="interview_result">Hasil Interview :...</label>
                                    </div>

                                </div>
                                <!-- Kontrak Kerja -->
                            </div>
                        </div>
                        <div class="tab-pane min-h-400px fade mh-400px scroll-y pe-1" role="tabpanel" id="kt_tab_education">
                            <table class="table align-middle  fs-6 gy-3 gs-3 table-sm table-row-bordered border">
                                <thead>
                                    <tr class="fw-bold text-muted bg-light">
                                        <th class="w-200px">Pendidikan</th>
                                        <th>Nama Sekolah/Perguruan</th>
                                        <th>Kota/Kabupaten</th>
                                        <th>Jurusan</th>
                                        <th class="text-end w-125px">Tahun Masuk</th>
                                        <th class="text-end pe-3 w-125px">Tahun Lulus</th>
                                    </tr>
                                </thead>
                                <tbody class="fw-bold text-gray-600">
                                    <tr data-clone="educations" class="data-item" data-max="5">
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <a href="javascript:;" class="btn btn-sm btn-light-danger btn-active-danger px-2 py-1 text-center me-3 hidden" disabled data-deleted="data-item">
                                                    <i class="fa fa-trash p-0"></i>
                                                </a>
                                                <select class="form-select form-select-sm unvalidate" data-placeholder="Pilih Pendidikan" name="educations[1][education_level]" data-input="education_level" placeholder="Pilih Pendidikan">
                                                    <option value="">Pilih Pendidikan</option>
                                                    @foreach($data['education_level'] as $edukey=>$eduval)
                                                        <option value="{{$edukey}}">{{$eduval}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </td>
                                        <td>
                                            <input type="text" name="educations[1][school_name]" data-input="school_name" class="form-control form-control-sm unvalidate" maxlength="70"  placeholder="Nama Sekolah/Perguruan" value="">
                                        </td>
                                        <td>
                                            <input type="text" name="educations[1][city]" data-input="city" class="form-control form-control-sm unvalidate" maxlength="70"  placeholder="Kota" value="">
                                        </td>
                                        <td>
                                            <input type="text" name="educations[1][major]" data-input="major" class="form-control form-control-sm unvalidate" maxlength="70"  placeholder="Jurusan" value="">
                                        </td>
                                        <td>
                                            <input type="text" name="educations[1][year_start]" data-input="year_start" class="form-control form-control-sm text-end unvalidate" maxlength="4"  placeholder="Tahun Masuk" value="">
                                        </td>
                                        <td>
                                            <input type="text" name="educations[1][year_graduated]" data-input="year_graduated" class="form-control form-control-sm text-end unvalidate" maxlength="4"  placeholder="Tahun Lulus" value="">
                                        </td>
                                    </tr>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="6" class="text-end p-0 bg-light">
                                            <button type="button" class="btn btn-success btn-sm rounded-0" data-add="educations">
                                                <span class="indicator-label">{!!$config['title-page-icon']!!} Tambah Riwayat Pendidikan</span>
                                                <span class="indicator-progress">Sudah melebihi batas maksimal pendidikan</span>
                                            </button>
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                        <div class="tab-pane min-h-400px fade mh-400px scroll-y pe-1" role="tabpanel" id="kt_tab_families">
                            <table class="table align-middle  fs-6 gy-3 gs-3 table-sm table-row-bordered border">
                                <thead>
                                    <tr class="fw-bold text-muted bg-light">
                                        <th class="w-200px">Nama</th>
                                        <th>Hubungan</th>
                                        <th>Tempat/Tgl Lahir</th>
                                        <th>Gender</th>
                                        <th>Pendidikan</th>
                                    </tr>
                                </thead>
                                <tbody class="fw-bold text-gray-600">
                                    <tr data-clone="families" class="data-item" data-max="10">
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <a href="javascript:;" class="btn btn-sm btn-light-danger btn-active-danger px-2 py-1 text-center me-3 hidden" disabled data-deleted="data-item">
                                                    <i class="fa fa-trash p-0"></i>
                                                </a>
                                                <input type="text" name="families[1][name]" data-input="name" class="form-control form-control-sm unvalidate" maxlength="70"  placeholder="Nama" value="">
                                            </div>
                                        </td>
                                        <td>
                                            <input type="text" name="families[1][relation]" data-input="relation" class="form-control form-control-sm unvalidate" maxlength="70"  placeholder="Hubungan" value="">
                                        </td>
                                        <td>
                                            <input type="text" name="families[1][birth_place_date]" data-input="birth_place_date" class="form-control form-control-sm unvalidate" maxlength="70"  placeholder="Tempat/Tgl. Lahir" value="">
                                        </td>
                                        <td>
                                            <select class="form-select form-select-sm unvalidate" data-placeholder="Pilih Gender" name="families[1][gender]" data-input="gender" placeholder="Pilih Gender">
                                                <option value="L">Laki-Laki</option>
                                                <option value="P">Perempuan</option>
                                            </select>
                                        </td>
                                        <td>
                                            <select class="form-select form-select-sm unvalidate" data-placeholder="Pilih Pendidikan" name="families[1][education]" data-input="education" placeholder="Pilih Pendidikan">
                                                <option value="">Pilih Pendidikan</option>
                                                @foreach($data['education_level'] as $edukey=>$eduval)
                                                    <option value="{{$edukey}}">{{$eduval}}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                    </tr>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="6" class="text-end p-0 bg-light">
                                            <button type="button" class="btn btn-success btn-sm rounded-0" data-add="families">
                                                <span class="indicator-label">{!!$config['title-page-icon']!!} Tambah Riwayat Keluarga</span>
                                                <span class="indicator-progress">Sudah melebihi batas maksimal Keluarga</span>
                                            </button>
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                        <div class="tab-pane min-h-400px fade" role="tabpanel" id="kt_tab_emergencies">
                            <table class="table align-middle  fs-6 gy-3 gs-3 table-sm table-row-bordered border">
                                <thead>
                                    <tr class="fw-bold text-muted bg-light">
                                        <th class="w-200px">Nama</th>
                                        <th>Hubungan</th>
                                        <th>Alamat</th>
                                        <th>No. HP</th>
                                    </tr>
                                </thead>
                                <tbody class="fw-bold text-gray-600">
                                    <tr data-clone="emergencies" class="data-item" data-max="3">
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <a href="javascript:;" class="btn btn-sm btn-light-danger btn-active-danger px-2 py-1 text-center me-3 hidden" disabled data-deleted="data-item">
                                                    <i class="fa fa-trash p-0"></i>
                                                </a>
                                                <input type="text" name="emergencies[1][name]" data-input="name" class="form-control form-control-sm unvalidate" maxlength="70"  placeholder="Nama" value="">
                                            </div>
                                        </td>
                                        <td>
                                            <input type="text" name="emergencies[1][relation]" data-input="relation" class="form-control form-control-sm unvalidate" maxlength="70"  placeholder="Hubungan" value="">
                                        </td>
                                        <td>
                                            <input type="text" name="emergencies[1][address]" data-input="address" class="form-control form-control-sm unvalidate" maxlength="120" placeholder="Alamat...">
                                        </td>
                                        <td>
                                            <input type="text" name="emergencies[1][phone]" data-input="phone" class="form-control form-control-sm unvalidate" maxlength="70"  placeholder="No. Telp" value="">
                                        </td>
                                    </tr>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="6" class="text-end p-0 bg-light">
                                            <button type="button" class="btn btn-success btn-sm rounded-0" data-add="emergencies">
                                                <span class="indicator-label">{!!$config['title-page-icon']!!} Tambah Riwayat Keluarga</span>
                                                <span class="indicator-progress">Sudah melebihi batas maksimal Keluarga</span>
                                            </button>
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                        <div class="tab-pane min-h-400px fade" role="tabpanel" id="kt_tab_users">
                             <div class="row">
                                <div class="col-12 col-sm-4">
                                    <div class="form-floating fv-row mb-4">
                                        <input type="text" name="username" class="form-control form-control-sm form-control-solid unvalidate" maxlength="60"  placeholder="User Name" value="">
                                        <label for="username">User name :...</label>
                                    </div>
                                </div>
                                <div class="col-12 col-sm-4">
                                    <div class="form-floating fv-row mb-4">
                                        <input type="text" name="email" class="form-control form-control-sm form-control-solid unvalidate" maxlength="60"  placeholder="Email" value="">
                                        <label for="email">Email :...</label>
                                    </div>
                                </div>
                                <div class="col-12 col-sm-4">
                                    <div class="form-floating fv-row mb-4">
                                        <input type="password" name="password" class="form-control form-control-sm form-control-solid unvalidate" placeholder="Password" value="">
                                        <label for="password">Password :...</label>
                                    </div>
                                </div>
                             </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer p-4">
                    <button type="submit" id="kt_main_submit" class="btn btn-primary w-100">
                        <span class="indicator-label">{{$config['button-submit']}} {!!$config['title-page-icon']!!}</span>
                        <span class="indicator-progress">{{$config['button-update-wait']??'Tunggu sebentar'}}...
                        <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>