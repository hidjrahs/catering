<script>
    let tableProvince,
    tableCities,
    tableDistricts,
    tableVilages,
    form,
    submit,
    defaultError= "Proses tidak berhasil.";
    $(function() {
        'use strict';
        $.fn.dataTable.ext.errMode = 'none' ?? 'throw';
        tableProvince = $('#main-table-province').DataTable({
            processing: true,
            serverSide: true,
            orderable: false,
            ajax: {
                url: "{{ route('web.ref_wilayah.all-paginate') }}?device=web&type=provinces",
                method: 'GET',
                data: function(d) {
                    d.search = $('#search-province').val();
                }
            },
            'createdRow': function( row, data, dataIndex ) {
                $(row).addClass('cursor-pointer')
                    .attr('title','Edit Provinsi : '+data.name+' ?')
                    .attr('data-ref',data.id);
            },
            columns: [{
                    data: null,
                    searchable: false,
                    orderable: false,
                    render: function(data, type, row, meta) {
                        return '<div class="form-check form-check-sm form-check-custom form-check-solid">'+
                                    '<input class="form-check-input border border-primary border-active active border-1 cursor-pointer widget-province-check" name="row-province[]" type="checkbox" value="'+row.id+'">'+
                                '</div>';
                    },
                }, {
                    data: 'name',
                    orderable: false,
                    render: function(data, type, row, meta) {
                        return '<div class="d-flex align-items-center">'+
                        '<div class="d-flex justify-content-start flex-column">'+
                            '<span class="text-dark fw-bold text-hover-primary mb-1 fs-6">'+data+'</span>'+
                        '</div></div>';
                    }
                },
            ],
            aaSorting: [],
            pageLength: 7, 
            dom: "<'row'" +
                "<'col-sm-6 d-flex align-items-center justify-conten-start'l>" +
                "<'col-sm-6 d-flex align-items-center justify-content-end'f>" +
                ">" +

                "<'table-responsive'tr>" +

                "<'row'" +
                "<'col-sm-12 d-flex align-items-center justify-content-center justify-content-md-end'p>" +
                ">"
        });
        tableProvince.on( 'draw', function () {
            let totalAll = tableProvince.page.info().recordsTotal;
            $('[data-label="count-data-province"]').html(totalAll);
        });
        $(document).on('change', '.search-province', function() {
            tableProvince.draw();
        });
        $(document).on('keyup', '.search-province', function(event) {
            event.which === 13 && (tableProvince.draw())
        });
        tableCities = $('#main-table-cities').DataTable({
            processing: true,
            serverSide: true,
            orderable: false,
            ajax: {
                url: "{{ route('web.ref_wilayah.all-paginate') }}?device=web&type=cities",
                method: 'GET',
                data: function(d) {
                    d.search = $('#search-cities').val();
                }
            },
            'createdRow': function( row, data, dataIndex ) {
                $(row).addClass('cursor-pointer')
                    .attr('title','Edit Kota/Kabupaten : '+data.name+' ?')
                    .attr('data-ref',data.id);
            },
            columns: [{
                    data: null,
                    searchable: false,
                    orderable: false,
                    render: function(data, type, row, meta) {
                        return '<div class="form-check form-check-sm form-check-custom form-check-solid">'+
                                    '<input class="form-check-input border border-primary border-active active border-1 cursor-pointer widget-cities-check" name="row-cities[]" type="checkbox" value="'+row.id+'">'+
                                '</div>';
                    },
                }, {
                    data: 'name',
                    orderable: false,
                    render: function(data, type, row, meta) {
                        return '<div class="d-flex align-items-center">'+
                        '<div class="d-flex justify-content-start flex-column">'+
                            '<span class="text-dark fw-bold text-hover-primary mb-1 fs-6">'+data+'</span>'+
                            '<span class="text-muted fw-semibold text-muted d-block fs-7">Provinsi : '+row.provinci_name+'</span>'+
                        '</div></div>';
                    }
                },
            ],
            aaSorting: [],
            pageLength: 5, 
            dom: "<'row'" +
                "<'col-sm-6 d-flex align-items-center justify-conten-start'l>" +
                "<'col-sm-6 d-flex align-items-center justify-content-end'f>" +
                ">" +

                "<'table-responsive'tr>" +

                "<'row'" +
                "<'col-sm-12 d-flex align-items-center justify-content-center justify-content-md-end'p>" +
                ">"
        });
        tableCities.on( 'draw', function () {
            let totalAll = tableCities.page.info().recordsTotal;
            $('[data-label="count-data-cities"]').html(totalAll);
        });
        $(document).on('change', '.search-cities', function() {
            tableCities.draw();
        });
        $(document).on('keyup', '.search-cities', function(event) {
            event.which === 13 && (tableCities.draw())
        });
        tableDistricts = $('#main-table-districts').DataTable({
            processing: true,
            serverSide: true,
            orderable: false,
            ajax: {
                url: "{{ route('web.ref_wilayah.all-paginate') }}?device=web&type=districts",
                method: 'GET',
                data: function(d) {
                    d.search = $('#search-districts').val();
                }
            },
            'createdRow': function( row, data, dataIndex ) {
                $(row).addClass('cursor-pointer')
                    .attr('title','Edit Kecamatan : '+data.name+' ?')
                    .attr('data-ref',data.id);
            },
            columns: [{
                    data: null,
                    searchable: false,
                    orderable: false,
                    render: function(data, type, row, meta) {
                        return '<div class="form-check form-check-sm form-check-custom form-check-solid">'+
                                    '<input class="form-check-input border border-primary border-active active border-1 cursor-pointer widget-districts-check" name="row-districts[]" type="checkbox" value="'+row.id+'">'+
                                '</div>';
                    },
                }, {
                    data: 'city_name',
                    orderable: false,
                    render: function(data, type, row, meta) {
                        return '<div class="d-flex align-items-center">'+
                        '<div class="d-flex justify-content-start flex-column">'+
                            '<span class="text-dark fw-bold text-hover-primary mb-1 fs-6">'+data+'</span>'+
                            '<span class="text-muted fw-semibold text-muted d-block fs-7">'+row.provinci_name+'</span>'+
                        '</div></div>';
                    }
                }, {
                    data: 'name',
                    orderable: false,
                    render: function(data, type, row, meta) {
                        return '<div class="d-flex align-items-center">'+
                        '<div class="d-flex justify-content-start flex-column">'+
                            '<span class="text-dark fw-bold text-hover-primary mb-1 fs-6">'+data+'</span>'+
                        '</div></div>';
                    }
                },
            ],
            aaSorting: [],
            pageLength: 5, 
            dom: "<'row'" +
                "<'col-sm-6 d-flex align-items-center justify-conten-start'l>" +
                "<'col-sm-6 d-flex align-items-center justify-content-end'f>" +
                ">" +

                "<'table-responsive'tr>" +

                "<'row'" +
                "<'col-sm-12 d-flex align-items-center justify-content-center justify-content-md-end'p>" +
                ">"
        });
        tableDistricts.on( 'draw', function () {
            let totalAll = tableDistricts.page.info().recordsTotal;
            $('[data-label="count-data-districts"]').html(totalAll);
        });
        $(document).on('change', '.search-districts', function() {
            tableDistricts.draw();
        });
        $(document).on('keyup', '.search-districts', function(event) {
            event.which === 13 && (tableDistricts.draw())
        });
        tableVilages = $('#main-table-vilages').DataTable({
            processing: true,
            serverSide: true,
            orderable: false,
            ajax: {
                url: "{{ route('web.ref_wilayah.all-paginate') }}?device=web&type=vilages",
                method: 'GET',
                data: function(d) {
                    d.search = $('#search-vilages').val();
                }
            },
            'createdRow': function( row, data, dataIndex ) {
                $(row).addClass('cursor-pointer')
                    .attr('title','Edit Kelurahan/Desa : '+data.name+' ?')
                    .attr('data-ref',data.id);
            },
            columns: [{
                    data: null,
                    searchable: false,
                    orderable: false,
                    render: function(data, type, row, meta) {
                        return '<div class="form-check form-check-sm form-check-custom form-check-solid">'+
                                    '<input class="form-check-input border border-primary border-active active border-1 cursor-pointer widget-vilages-check" name="row-vilages[]" type="checkbox" value="'+row.id+'">'+
                                '</div>';
                    },
                }, {
                    data: 'city_name',
                    orderable: false,
                    render: function(data, type, row, meta) {
                        return '<div class="d-flex align-items-center">'+
                        '<div class="d-flex justify-content-start flex-column">'+
                            '<span class="text-dark fw-bold text-hover-primary mb-1 fs-6">'+data+'</span>'+
                            '<span class="text-muted fw-semibold text-muted d-block fs-7">'+row.provinci_name+'</span>'+
                        '</div></div>';
                    }
                }, {
                    data: 'name',
                    orderable: false,
                    render: function(data, type, row, meta) {
                        return '<div class="d-flex align-items-center">'+
                        '<div class="d-flex justify-content-start flex-column">'+
                            '<span class="text-dark fw-bold text-hover-primary mb-1 fs-6">'+data+'</span>'+
                            '<span class="text-muted fw-semibold text-muted d-block fs-7">Kec : '+row.district_name+'</span>'+
                        '</div></div>';
                    }
                },
            ],
            aaSorting: [],
            pageLength: 5, 
            dom: "<'row'" +
                "<'col-sm-6 d-flex align-items-center justify-conten-start'l>" +
                "<'col-sm-6 d-flex align-items-center justify-content-end'f>" +
                ">" +

                "<'table-responsive'tr>" +

                "<'row'" +
                "<'col-sm-12 d-flex align-items-center justify-content-center justify-content-md-end'p>" +
                ">"
        });
        tableVilages.on( 'draw', function () {
            let totalAll = tableVilages.page.info().recordsTotal;
            $('[data-label="count-data-vilages"]').html(totalAll);
        });
        $(document).on('change', '.search-vilages', function() {
            tableVilages.draw();
        });
        $(document).on('keyup', '.search-vilages', function(event) {
            event.which === 13 && (tableVilages.draw())
        });
        let blockUIBody = new KTBlockUI($("body")[0], {
            message: '<div class="blockui-message"><span class="spinner-border text-primary"></span> Loading...</div>',
        }),
        blockUIProvince = new KTBlockUI($('[data-card="province"]')[0], {
            message: '<div class="blockui-message"><span class="spinner-border text-primary"></span> Loading...</div>',
        }),
        blockUICities = new KTBlockUI($('[data-card="cities"]')[0], {
            message: '<div class="blockui-message"><span class="spinner-border text-primary"></span> Loading...</div>',
        }),
        blockUIDistricts = new KTBlockUI($('[data-card="districts"]')[0], {
            message: '<div class="blockui-message"><span class="spinner-border text-primary"></span> Loading...</div>',
        }),
        blockUIVilages = new KTBlockUI($('[data-card="vilages"]')[0], {
            message: '<div class="blockui-message"><span class="spinner-border text-primary"></span> Loading...</div>',
        });
        const fieldValidation=function(form){
            let listInput=form.find('[name][placeholder]:not(.unvalidate)'),
                listValidation={};
            $.each(listInput, (x, y) => {
                listValidation[$(y).attr('name')]={
                    validators: {
                        notEmpty: { message: $(y).attr('placeholder').replace(/:...$/, "")+" harus di isi." },
                    }
                }
            })
            return {
                fields: listValidation,
                plugins: {
                    trigger: new FormValidation.plugins.Trigger(),
                    bootstrap: new FormValidation.plugins.Bootstrap5({
                        rowSelector: ".fv-row",
                        eleInvalidClass: "",
                        eleValidClass: "",
                    }),
                }
            }
        },
        formProvince=$('form#kt_province'),
        validationProvince=FormValidation.formValidation(formProvince[0],fieldValidation(formProvince)),
        formCities=$('form#kt_cities'),
        validationCities=FormValidation.formValidation(formCities[0],fieldValidation(formCities)),
        formDistricts=$('form#kt_districts'),
        validationDistricts=FormValidation.formValidation(formDistricts[0],fieldValidation(formDistricts)),
        formVilages=$('form#kt_vilages'),
        validationVilages=FormValidation.formValidation(formVilages[0],fieldValidation(formVilages));
        $('[maxlength]').maxlength({
			warningClass: "label label-warning label-rounded label-inline",
			limitReachedClass: "label label-success label-rounded label-inline",
            zIndex: 5000,
            appendToParent: true
		});
        $('.province-select').each(function(){
            let $this=$(this);
            $this.select2({
                placeholder: "Provinsi:...",
                dropdownParent: $this.closest('.modal'),
                ajax: {
                    url: "{{ route('web.ref_wilayah.search') }}?type=province",
                    dataType: 'json',
                    delay: 250,
                    data: function (params) {
                        return {
                            q: params.term
                        };
                    },
                    processResults: function (resp) {
                        return {
                            results: resp.data.map(function(item) {
                                return {
                                    id: item.id,
                                    text: item.name,
                                };
                            })
                        };
                    }
                }
            });
        });
        $('.cities-select').each(function(){
            let $this=$(this);
            $this.select2({
                placeholder: "Kota/Kab:...",
                dropdownParent: $this.closest('.modal'),
                ajax: {
                    url: "{{ route('web.ref_wilayah.search') }}?type=cities",
                    dataType: 'json',
                    delay: 250,
                    data: function (params) {
                        return {
                            q: params.term,
                            refId: $this.closest('.modal').find('[name="province_id"]').val()
                        };
                    },
                    processResults: function (resp) {
                        return {
                            results: resp.data.map(function(item) {
                                return {
                                    id: item.id,
                                    text: item.name,
                                };
                            })
                        };
                    }
                }
            });
        });
        $('.districts-select').each(function(){
            let $this=$(this);
            $this.select2({
                placeholder: "Kecamatan:...",
                dropdownParent: $this.closest('.modal'),
                ajax: {
                    url: "{{ route('web.ref_wilayah.search') }}?type=districts",
                    dataType: 'json',
                    delay: 250,
                    data: function (params) {
                        return {
                            q: params.term,
                            refId: $this.closest('.modal').find('[name="cities_id"]').val()
                        };
                    },
                    processResults: function (resp) {
                        return {
                            results: resp.data.map(function(item) {
                                return {
                                    id: item.id,
                                    text: item.name,
                                };
                            })
                        };
                    }
                }
            });
        });
        $('.laravolt-province-select').each(function(){
            let $this=$(this);
            $this.select2({
                placeholder: "Cari Provinsi...",
                dropdownParent: $this.closest('.modal'),
                ajax: {
                    url: "{{ route('web.ref_wilayah.search') }}?type=laravolt_province",
                    dataType: 'json',
                    delay: 250,
                    data: function (params) {
                        return {
                            q: params.term
                        };
                    },
                    processResults: function (resp) {
                        return {
                            results: resp.data.map(function(item) {
                                return {
                                    id: item.name,
                                    text: item.name,
                                };
                            })
                        };
                    }
                }
            });
        });
        $('.laravolt-cities-select').each(function(){
            let $this=$(this);
            $this.select2({
                placeholder: "Cari Kota/Kab...",
                dropdownParent: $this.closest('.modal'),
                ajax: {
                    url: "{{ route('web.ref_wilayah.search') }}?type=laravolt_cities",
                    dataType: 'json',
                    delay: 250,
                    data: function (params) {
                        return {
                            q: params.term,
                            refId: $this.closest('.modal').find('[name="province_id"]').val()
                        };
                    },
                    processResults: function (resp) {
                        return {
                            results: resp.data.map(function(item) {
                                return {
                                    id: item.name,
                                    text: item.name,
                                };
                            })
                        };
                    }
                }
            });
        });
        $('.laravolt-districts-select').each(function(){
            let $this=$(this);
            $this.select2({
                placeholder: "Cari Kecamatan...",
                dropdownParent: $this.closest('.modal'),
                ajax: {
                    url: "{{ route('web.ref_wilayah.search') }}?type=laravolt_districts",
                    dataType: 'json',
                    delay: 250,
                    data: function (params) {
                        return {
                            q: params.term,
                            refId: $this.closest('.modal').find('[name="cities_id"]').val()
                        };
                    },
                    processResults: function (resp) {
                        return {
                            results: resp.data.map(function(item) {
                                return {
                                    id: item.name,
                                    text: item.name,
                                };
                            })
                        };
                    }
                }
            });
        });
        $('.laravolt-vilages-select').each(function(){
            let $this=$(this);
            $this.select2({
                placeholder: "Cari Kelurahan/Desa...",
                dropdownParent: $this.closest('.modal'),
                ajax: {
                    url: "{{ route('web.ref_wilayah.search') }}?type=laravolt_vilages",
                    dataType: 'json',
                    delay: 250,
                    data: function (params) {
                        return {
                            q: params.term,
                            refId: $this.closest('.modal').find('[name="districts_id"]').val()
                        };
                    },
                    processResults: function (resp) {
                        return {
                            results: resp.data.map(function(item) {
                                return {
                                    id: item.name,
                                    text: item.name,
                                };
                            })
                        };
                    }
                }
            });
        });
        $(document).on('submit', 'form#kt_province', function(e) {
            e.preventDefault(),
            form=$(this),
            submit=form.find('#kt_province_submit'),
            validationProvince.validate().then(function (valid) {
                valid=='Valid' && 
                (submit.attr("data-kt-indicator", "on"),
                submit.attr("disabled","true"),
                blockUIProvince.block(),
                setTimeout(function () {
                    form.ajaxSubmit({
                        type: "post",
                        url: form.attr("data-action")+'?type=province',
                        dataType: "json",
                        success: function (response) {
                            if(response.status!='200'){
                                return Swal.fire({icon: "warning",text: response.message})
                            }
                            form.resetForm();
                            tableProvince.draw(),
                            submit.removeAttr("data-kt-indicator"),
                            submit.removeAttr("disabled"),
                            $('#modal-province').modal('hide'),
                            blockUIProvince.release();
                        },
                        error: function (error) {
                            (error = error.responseJSON? error.responseJSON: defaultError),
                            Swal.fire({icon: "error",text: error.message? error.message: defaultError}),
                            submit.removeAttr("data-kt-indicator"),
                            submit.removeAttr("disabled"),
                            blockUIProvince.release();
                        },
                    })
                },1e2))
            });
        });
        $(document).on('submit', 'form#kt_cities', function(e) {
            e.preventDefault(),
            form=$(this),
            submit=form.find('#kt_cities_submit'),
            validationCities.validate().then(function (valid) {
                valid=='Valid' && 
                (submit.attr("data-kt-indicator", "on"),
                submit.attr("disabled","true"),
                blockUICities.block(),
                setTimeout(function () {
                    form.ajaxSubmit({
                        type: "post",
                        url: form.attr("data-action")+'?type=cities',
                        dataType: "json",
                        success: function (response) {
                            if(response.status!='200'){
                                return Swal.fire({icon: "warning",text: response.message})
                            }
                            form.resetForm();
                            tableCities.draw(),
                            submit.removeAttr("data-kt-indicator"),
                            submit.removeAttr("disabled"),
                            $('#modal-cities').modal('hide'),
                            blockUICities.release();
                        },
                        error: function (error) {
                            (error = error.responseJSON? error.responseJSON: defaultError),
                            Swal.fire({icon: "error",text: error.message? error.message: defaultError}),
                            submit.removeAttr("data-kt-indicator"),
                            submit.removeAttr("disabled"),
                            blockUICities.release();
                        },
                    })
                },1e2))
            });
        });
        $(document).on('submit', 'form#kt_districts', function(e) {
            e.preventDefault(),
            form=$(this),
            submit=form.find('#kt_cities_submit'),
            validationDistricts.validate().then(function (valid) {
                valid=='Valid' && 
                (submit.attr("data-kt-indicator", "on"),
                submit.attr("disabled","true"),
                blockUIDistricts.block(),
                setTimeout(function () {
                    form.ajaxSubmit({
                        type: "post",
                        url: form.attr("data-action")+'?type=districts',
                        dataType: "json",
                        success: function (response) {
                            if(response.status!='200'){
                                return Swal.fire({icon: "warning",text: response.message})
                            }
                            form.resetForm();
                            tableDistricts.draw(),
                            submit.removeAttr("data-kt-indicator"),
                            submit.removeAttr("disabled"),
                            $('#modal-districts').modal('hide'),
                            blockUIDistricts.release();
                        },
                        error: function (error) {
                            (error = error.responseJSON? error.responseJSON: defaultError),
                            Swal.fire({icon: "error",text: error.message? error.message: defaultError}),
                            submit.removeAttr("data-kt-indicator"),
                            submit.removeAttr("disabled"),
                            blockUIDistricts.release();
                        },
                    })
                },1e2))
            });
        });
        $(document).on('submit', 'form#kt_vilages', function(e) {
            e.preventDefault(),
            form=$(this),
            submit=form.find('#kt_cities_submit'),
            validationVilages.validate().then(function (valid) {
                valid=='Valid' && 
                (submit.attr("data-kt-indicator", "on"),
                submit.attr("disabled","true"),
                blockUIVilages.block(),
                setTimeout(function () {
                    form.ajaxSubmit({
                        type: "post",
                        url: form.attr("data-action")+'?type=vilages',
                        dataType: "json",
                        success: function (response) {
                            if(response.status!='200'){
                                return Swal.fire({icon: "warning",text: response.message})
                            }
                            form.resetForm();
                            tableVilages.draw(),
                            submit.removeAttr("data-kt-indicator"),
                            submit.removeAttr("disabled"),
                            $('#modal-vilages').modal('hide'),
                            blockUIVilages.release();
                        },
                        error: function (error) {
                            (error = error.responseJSON? error.responseJSON: defaultError),
                            Swal.fire({icon: "error",text: error.message? error.message: defaultError}),
                            submit.removeAttr("data-kt-indicator"),
                            submit.removeAttr("disabled"),
                            blockUIVilages.release();
                        },
                    })
                },1e2))
            });
        });

        $(document).on('click', '.modal-form', function() {
            let e=$(this),
                parent=e.closest('[data-card]'),
                type=parent.attr('data-card');
            $('#modal-'+type).find('[data-replace="form"]').html('Tambah');
            $('#modal-'+type).find('form').attr('data-action',"{{ $config['action'] }}");
            $('#modal-'+type).modal('show');
            clearModal($('#modal-'+type));
        });
        $(document).on('change', '[name="row-province[]"]', function() {
            let e=$(this),
            parent=e.closest('[data-card]'),
            values=$("input[name='row-province[]']:checked:not([value='all'])").map(function(){
                return $(this).val();
            }).get();
            let buttonDelete=parent.find(".deleted-check");
            if(values.length){
                buttonDelete.removeClass('disabled');
                buttonDelete.removeClass('btn-light');
                buttonDelete.addClass('btn-danger');
                buttonDelete.removeClass('hidden');
            }else{
                buttonDelete.addClass('disabled');
                buttonDelete.addClass('btn-light');
                buttonDelete.removeClass('btn-danger');
                buttonDelete.addClass('hidden');
            }
        });
        $(document).on('change', '[name="row-cities[]"]', function() {
            let e=$(this),
            parent=e.closest('[data-card]'),
            values=$("input[name='row-cities[]']:checked:not([value='all'])").map(function(){
                return $(this).val();
            }).get();
            let buttonDelete=parent.find(".deleted-check");
            if(values.length){
                buttonDelete.removeClass('disabled');
                buttonDelete.removeClass('btn-light');
                buttonDelete.addClass('btn-danger');
                buttonDelete.removeClass('hidden');
            }else{
                buttonDelete.addClass('disabled');
                buttonDelete.addClass('btn-light');
                buttonDelete.removeClass('btn-danger');
                buttonDelete.addClass('hidden');
            }
        });
        $(document).on('change', '[name="row-districts[]"]', function() {
            let e=$(this),
            parent=e.closest('[data-card]'),
            values=$("input[name='row-districts[]']:checked:not([value='all'])").map(function(){
                return $(this).val();
            }).get();
            let buttonDelete=parent.find(".deleted-check");
            if(values.length){
                buttonDelete.removeClass('disabled');
                buttonDelete.removeClass('btn-light');
                buttonDelete.addClass('btn-danger');
                buttonDelete.removeClass('hidden');
            }else{
                buttonDelete.addClass('disabled');
                buttonDelete.addClass('btn-light');
                buttonDelete.removeClass('btn-danger');
                buttonDelete.addClass('hidden');
            }
        });
        $(document).on('change', '[name="row-vilages[]"]', function() {
            let e=$(this),
            parent=e.closest('[data-card]'),
            values=$("input[name='row-vilages[]']:checked:not([value='all'])").map(function(){
                return $(this).val();
            }).get();
            let buttonDelete=parent.find(".deleted-check");
            if(values.length){
                buttonDelete.removeClass('disabled');
                buttonDelete.removeClass('btn-light');
                buttonDelete.addClass('btn-danger');
                buttonDelete.removeClass('hidden');
            }else{
                buttonDelete.addClass('disabled');
                buttonDelete.addClass('btn-light');
                buttonDelete.removeClass('btn-danger');
                buttonDelete.addClass('hidden');
            }
        });
        $(document).on('click', '.deleted-check:not(.disabled)', function() {
            let e=$(this),
                type=e.attr('data-type'),
                parentCard=e.closest('[data-card]'),
                values,label,blockUiActive,tableActive;
            if(type=='province'){
                values=$("input[name='row-province[]']:checked:not([value='all'])").map(function(){
                    return $(this).val();
                }).get();
                label='Provinsi';
                blockUiActive=blockUIProvince;
                tableActive=tableProvince;
            }
            if(type=='cities'){
                values=$("input[name='row-cities[]']:checked:not([value='all'])").map(function(){
                    return $(this).val();
                }).get();
                label='Kota/Kab';
                blockUiActive=blockUICities;
                tableActive=tableCities;
            }
            if(type=='districts'){
                values=$("input[name='row-districts[]']:checked:not([value='all'])").map(function(){
                    return $(this).val();
                }).get();
                label='Kecamatan';
                blockUiActive=blockUIDistricts;
                tableActive=tableDistricts;
            }
            if(type=='vilages'){
                values=$("input[name='row-vilages[]']:checked:not([value='all'])").map(function(){
                    return $(this).val();
                }).get();
                label='Desa/Kelurahan';
                blockUiActive=blockUIVilages;
                tableActive=tableVilages;
            }
            if(!values){
                return Swal.fire({icon: "success",text: 'Referensi yang di hapus tidak valid.'});
            }
            Swal.fire({
                icon: 'warning',
                text: 'Hapus Referensi '+label+'?',
                showCancelButton: true,
                confirmButtonText: "<i class='fas text-white fa-save text-primary'></i> Ya",
                cancelButtonText: "<i class='fas text-white fa-times text-danger'></i> Tidak"
            }).then((willsend) => {
                if (willsend.isConfirmed) {
                    blockUiActive.block();
                    asyncData("{{route('web.ref_wilayah.destroy')}}?type="+type,values,"POST").then((response) => {
                        if (!response.status) {
                            blockUiActive.release();
                            return Swal.fire({icon: 'error',text: response.message});
                        }
                        tableActive.draw();
                        blockUiActive.release();
                        parentCard.find('.form-check [type="checkbox"]').prop('checked',false);
                        parentCard.find('.form-check [type="checkbox"]').trigger('change');
                    }).catch((error) => {
                        let res = (error.responseJSON) ? error.responseJSON : defaultError;
                        Swal.fire({icon: 'error',text: res.message ?? defaultError});
                        blockUiActive.release();
                    });
                };
            });
        });
        let clearModal=function(modalActive){
            modalActive.find('form').resetForm();
            if(modalActive.find('.laravolt-province-select').length) {
                modalActive.find('.laravolt-province-select').val(null).trigger('change');
            }
            if(modalActive.find('.laravolt-cities-select').length) {
                modalActive.find('.laravolt-cities-select').val(null).trigger('change');
            }
            if(modalActive.find('.laravolt-districts-select').length) {
                modalActive.find('.laravolt-districts-select').val(null).trigger('change');
            }
            if(modalActive.find('.laravolt-vilages-select').length) {
                modalActive.find('.laravolt-vilages-select').val(null).trigger('change');
            }
        },filledModalUpdate=function(response,modalActive){
            let setForm=modalActive.find('form'),
                newOption,arr=['province_id','cities_id','districts_id'];
            $.each(response, (x, y) => {
                if(arr.includes(x)){
                    newOption = new Option(y.name, y.id, true, true);
                    setForm.find('[name="'+x+'"]').append(newOption).trigger('change');
                } else if(x === 'name' && setForm.find('.laravolt-province-select').length) {
                    newOption = new Option(y, y, true, true);
                    setForm.find('.laravolt-province-select').append(newOption).trigger('change');
                } else if(x === 'name' && setForm.find('.laravolt-cities-select').length) {
                    newOption = new Option(y, y, true, true);
                    setForm.find('.laravolt-cities-select').append(newOption).trigger('change');
                } else if(x === 'name' && setForm.find('.laravolt-districts-select').length) {
                    newOption = new Option(y, y, true, true);
                    setForm.find('.laravolt-districts-select').append(newOption).trigger('change');
                } else if(x === 'name' && setForm.find('.laravolt-vilages-select').length) {
                    newOption = new Option(y, y, true, true);
                    setForm.find('.laravolt-vilages-select').append(newOption).trigger('change');
                } else {
                    setForm.find('[name="'+x+'"]').val(y);
                }
            });
        };
        $(document).on('click', '[data-ref]', function() {
            if ($(event.target).closest("div").hasClass("form-check")) {
                return;
            }
            let e=$(this),
                refId=e.attr('data-ref'),
                parent=e.closest('[data-card]'),
                type=parent.attr('data-card');
            if(!refId){
                return Swal.fire({icon: 'warning',text: 'Data Referensi tidak ditemukan.'});
            }
            let modalUpdateActive=$('#modal-'+type),
                blockUiActive,tableActive;
            if(type=='province'){
                blockUiActive=blockUIProvince;
                tableActive=tableProvince;
            }
            if(type=='cities'){
                blockUiActive=blockUICities;
                tableActive=tableCities;
            }
            if(type=='districts'){
                blockUiActive=blockUIDistricts;
                tableActive=tableDistricts;
            }
            if(type=='vilages'){
                blockUiActive=blockUIVilages;
                tableActive=tableVilages;
            }
            modalUpdateActive.find('form').attr('data-action',"{{ $config['action'] }}/"+refId);
            modalUpdateActive.find('[data-replace]').html('Update');
            blockUiActive.block();
            clearModal(modalUpdateActive);
            asyncData("{{ $config['action'] }}/"+refId+'?type='+type,{},"GET").then((response) => {
                if (!response.status) {
                    blockUiActive.release();
                    return Swal.fire({icon: 'error',text: response.message});
                }
                const resultlist = response.data;
                filledModalUpdate(resultlist,modalUpdateActive);
                blockUiActive.release();
                modalUpdateActive.modal('show');
            }).catch((error) => {
                
                let res = (error.responseJSON) ? error.responseJSON : defaultError;
                Swal.fire({icon: 'error',text: res.message ?? defaultError});
                blockUiActive.release();
            });
            
        });
    });
</script>