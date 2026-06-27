<script>
    let table,
    form,
    submit,
    defaultError= "Proses tidak berhasil.";
    $(function() {
        'use strict';
        $.fn.dataTable.ext.errMode = 'none' ?? 'throw';
        table = $('#main-table').DataTable({
            processing: true,
            serverSide: true,
            orderable: false,
            ajax: {
                url: "{{ route('web.customers.all-paginate') }}?device=web",
                method: 'GET',
                data: function(d) {
                    d.search = $('#search').val();
                    // d.orders=$('#search-orders').val();
                    // d.order_option=$('[name="orders-options"]:checked').val();
                }
            },
            'createdRow': function( row, data, dataIndex ) {
                $(row).addClass('cursor-pointer')
                    .attr('title','Edit Customer : '+data.name+' ?')
                    .attr('data-ref',data.id);
            },
            columns: [{
                    data: null,
                    searchable: false,
                    orderable: false,
                    render: function(data, type, row, meta) {
                        return '<div class="form-check form-check-sm form-check-custom form-check-solid">'+
                                    '<input class="form-check-input border border-primary border-active active border-1 cursor-pointer widget-main-check" name="row-check[]" type="checkbox" value="'+row.id+'">'+
                                '</div>';
                    },
                }, {
                    data: 'name',
                    orderable: false,
                    render: function(data, type, row, meta) {
                        return '<div class="d-flex align-items-center"><div class="symbol symbol-50px me-5">'+
                            '<span class="symbol-label bg-light">'+
                                '<img src="'+((row.gender==1)?hostUrl+'/media/svg/avatars/001-boy.svg':hostUrl+'/media/svg/avatars/002-girl.svg')+'" class="h-75 align-self-end" alt="">'+
                            '</span>'+
                        '</div>'+
                        '<div class="d-flex justify-content-start flex-column">'+
                            '<span class="text-dark fw-bold text-hover-primary mb-1 fs-6">'+data+'</span>'+
                            '<span class="text-muted fw-semibold text-muted d-block fs-7">Nomor : '+row.phone+'</span>'+
                        '</div></div>';
                    }
                }, {
                    data: 'ref_wilayah',
                    orderable: false
                }, {
                    data: 'address',
                    orderable: false,
                    render: function(data, type, row, meta) {
                        // '<span class="text-muted fw-semibold text-muted d-block fs-7">Location: '+(row.location??'-')+'</span>'
                        return '<span class="text-dark fw-bold text-hover-primary d-block mb-1 fs-6">'+data+'</span>';
                    }
                }, {
                    data: 'created_at',
                    class: "text-end",
                    orderable: false,
                    render: function(data, type, row, meta) {
                        // '<span class="text-muted fw-semibold text-muted d-block fs-7">CS: ' +row.cs_name + '</span>'
                        return '<span class="text-dark text-hover-primary mb-1 fs-6">'+data +'</span>';
                    }
                },
            ],
            aaSorting: [],
            dom: "<'row'" +
                "<'col-sm-6 d-flex align-items-center justify-conten-start'l>" +
                "<'col-sm-6 d-flex align-items-center justify-content-end'f>" +
                ">" +

                "<'table-responsive'tr>" +

                "<'row'" +
                "<'col-sm-12 col-md-5 d-flex align-items-center justify-content-center justify-content-md-start'i>" +
                "<'col-sm-12 col-md-7 d-flex align-items-center justify-content-center justify-content-md-end'p>" +
                ">"
        });
        // table.on('draw.dt', function() {
        table.on( 'draw', function () {
            let totalAll = table.page.info().recordsTotal;
            $('[data-label="count-data"]').html(totalAll);
            // console.log("Filtered:", totalFiltered, "Total:", totalAll);
        });
        $(document).on('change', '.search-input', function() {
            table.draw();
        });
        $(document).on('keyup', '.search-input', function(event) {
            event.which === 13 && (table.draw())
        });
        let blockUIBody = new KTBlockUI($("body")[0], {
            message: '<div class="blockui-message"><span class="spinner-border text-primary"></span> Loading...</div>',
        }),
        modalFormUpdate=$("#modal-master-update");
        const clearModalUpdate=function(){
            let formUpdate=$("#kt_update_main");
            formUpdate.attr("action","{{ $config['action-update']}}");
            formUpdate.find('[name="name"]').val('');
            formUpdate.find('[name="phone"]').val('');
            formUpdate.find('[name="gender"]').val('');
            formUpdate.find('[name="address"]').val('');
        },filledModalUpdate=function(parameter,url){
            let formUpdate=$("#kt_update_main");
            formUpdate.attr("action",url??"{{ $config['action-update'] }}");
            // formUpdate.find('[name="id"]').val(parameter.id);
            formUpdate.find('[name="name"]').val(parameter.name);
            formUpdate.find('[name="phone"]').val(parameter.phone);
            formUpdate.find('[name="gender"]').val(parameter.gender);
            formUpdate.find('[name="address"]').val(parameter.address);
            if(parameter.city_id){
                let cityRef=parameter.city_id;
                let newOption = new Option(cityRef.name, cityRef.id, true, true);
                $(newOption).attr('data-cat', cityRef.province.name);
                formUpdate.find('[name="city_id"]').append(newOption).trigger('change'); 
            }
            if(parameter.vilage_id){
                let vilageRef=parameter.vilage_id;
                let newOptionB = new Option(vilageRef.name, vilageRef.id, true, true);
                $(newOptionB).attr('data-cat', vilageRef.district_id.name);
                formUpdate.find('[name="vilage_id"]').append(newOptionB).trigger('change'); 
            }
        };
        $(document).on('click', '[data-ref]', function(event) {
            if ($(event.target).closest("div").hasClass("form-check")) {
                return;
            }
            let refId=$(this).attr('data-ref');
            if(!refId){
                return Swal.fire({icon: 'warning',text: 'Data Customer tidak ditemukan.'});
            }
            blockUIBody.block();
            clearModalUpdate();
            asyncData("{{ url('web/customers') }}/"+refId,{},"GET").then((response) => {
                if (!response.status) {
                    blockUIBody.release();
                    return Swal.fire({icon: 'error',text: response.message});
                }
                const resultlist = response.data;
                filledModalUpdate(resultlist, "{{ url('web/customers') }}/"+refId);
                blockUIBody.release();
                modalFormUpdate.modal('show');
                setTimeout(function(){
                    $('#modal-master-update .skeleton.load').removeClass('load');
                    $("#modal-update").removeClass('hidden');
                    $("#modal-update .hidden").removeClass('hidden');
                },5e2)
            }).catch((error) => {
                blockUIBody.release();
                let res = (error.responseJSON) ? error.responseJSON : defaultError;
                Swal.fire({icon: 'error',text: res.message ?? defaultError});
                // console.log(res.message ?? defaultError)
            });
        });
        $(document).on('change', '[name="row-check[]"]', function() {
            let values=$("input[name='row-check[]']:checked:not([value='all'])").map(function(){
                return $(this).val();
            }).get();
            let buttonDelete=$("#deleted-check");
            if(values.length){
                buttonDelete.removeClass('disabled');
                buttonDelete.removeClass('btn-light');
                buttonDelete.addClass('btn-danger');
            }else{
                buttonDelete.addClass('disabled');
                buttonDelete.addClass('btn-light');
                buttonDelete.removeClass('btn-danger');
            }
        });
        $(document).on('click', '#deleted-check:not(.disabled)', function() {
            let values=$("input[name='row-check[]']:checked:not([value='all'])").map(function(){
                return $(this).val();
            }).get();
            Swal.fire({
                icon: 'warning',
                text: 'Hapus data customer?',
                showCancelButton: true,
                confirmButtonText: "<i class='fas text-white fa-save text-primary'></i> Ya",
                cancelButtonText: "<i class='fas text-white fa-times text-danger'></i> Tidak"
            }).then((willsend) => {
                if (willsend.isConfirmed) {
                    blockUIBody.block();
                    asyncData("{{route('web.customers.destroy')}}",values,"POST").then((response) => {
                        // console.log(response)
                        if (!response.status) {
                            blockUIBody.release();
                            return Swal.fire({icon: 'error',text: response.message});
                        }
                        table.draw();
                        blockUIBody.release();
                    }).catch((error) => {
                        // console.log(error)
                        blockUIBody.release();
                        let res = (error.responseJSON) ? error.responseJSON : defaultError;
                        Swal.fire({icon: 'error',text: res.message ?? defaultError});
                        // console.log(res.message ?? defaultError)
                    });
                };
            });
        });
        const fieldValidation=function(form){
            let listInput=form.find('[name][placeholder]:not(.unvalidate)'),
                listValidation={};
            $.each(listInput, (x, y) => {
                listValidation[$(y).attr('name')]={
                    validators: {
                        notEmpty: { message: $(y).attr('placeholder')+" harus di isi." },
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
        formMain=$("#kt_form_main"),
        formUpdateMain=$("#kt_update_main"),
        validation=FormValidation.formValidation(formMain[0],fieldValidation(formMain)),
        validationUpdate=FormValidation.formValidation(formUpdateMain[0],fieldValidation(formUpdateMain));
        $('[maxlength]').maxlength({
			warningClass: "label label-warning label-rounded label-inline",
			limitReachedClass: "label label-success label-rounded label-inline",
            zIndex: 5000,
            appendToParent: true
		});
        $(document).on('submit', "#kt_update_main", function(e){
            e.preventDefault(),
            form=$(this),
            submit=form.find('#kt_update_submit'),
            validationUpdate.validate().then(function (valid) {
                valid=='Valid' && 
                (submit.attr("data-kt-indicator", "on"),
                submit.attr("disabled","true"),
                blockUIBody.release(),
                setTimeout(function () {
                    form.ajaxSubmit({
                        type: "post",
                        url: form.attr("action"),
                        dataType: "json",
                        success: function (response) {
                            if(response.status!='200'){
                                return Swal.fire({icon: "warning",text: response.message})
                            }
                            Swal.fire({icon: "success",text: response.message});
                            submit.removeAttr("data-kt-indicator"),
                            submit.removeAttr("disabled"),
                            form.resetForm();
                            modalFormUpdate.modal('hide');
                            table.draw(false);
                            blockUIBody.release();
                        },
                        error: function (error) {
                            (error = error.responseJSON? error.responseJSON: defaultError),
                            Swal.fire({icon: "error",text: error.message? error.message: defaultError}),
                            submit.removeAttr("data-kt-indicator"),
                            submit.removeAttr("disabled"),
                            blockUIBody.release();
                        },
                    })
                },5e2))
            })
        });
        $(document).on('submit', "#kt_form_main", function(e){
            e.preventDefault(),
            form=$(this),
            submit=form.find('#kt_form_submit'),
            validation.validate().then(function (valid) {
                valid=='Valid' && 
                (submit.attr("data-kt-indicator", "on"),
                submit.attr("disabled","true"),
                blockUIBody.block(),
                setTimeout(function () {
                    form.ajaxSubmit({
                        type: "post",
                        url: form.attr("action"),
                        dataType: "json",
                        success: function (response) {
                            if(response.status!='200'){
                                return Swal.fire({icon: "warning",text: response.message})
                            }
                            Swal.fire({icon: "success",text: response.message});
                            submit.removeAttr("data-kt-indicator"),
                            submit.removeAttr("disabled"),
                            form.resetForm();
                            table.draw();
                            blockUIBody.release();
                        },
                        error: function (error) {
                            (error = error.responseJSON? error.responseJSON: defaultError),
                            Swal.fire({icon: "error",text: error.message? error.message: defaultError}),
                            submit.removeAttr("data-kt-indicator"),
                            submit.removeAttr("disabled"),
                            blockUIBody.release();
                        },
                    })
                },5e2))
            })
        });
        let optionFormat = (item) => {
            if (!item.id) return item.text;
            // console.log(item);
            let html  = '<div class="d-flex flex-column">';
            html += '<span class="fs-5 fw-bold lh-1">' + item.text + '</span>';
            html += '<span class="text-muted fs-6">' + 'Provinsi: ' + (item.cat??$(item.element).attr('data-cat')) + '</span>';
            html += '</div>';

            return $(html);
        },optionFormatB = (item) => {
            if (!item.id) return item.text;
            let html  = '<div class="d-flex flex-column">';
            html += '<span class="fs-5 fw-bold lh-1">' + item.text + '</span>';
            html += '<span class="text-muted fs-6">' + 'Kec: ' + (item.cat??$(item.element).attr('data-cat')) + '</span>';
            html += '</div>';

            return $(html);
        };
        $('#kt_form_main [name="city_id"]').select2({
            placeholder: "Kota/Kabupaten:...",
            ajax: {
                url: "{{route('web.ref_wilayah.search_city')}}",
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
                                cat: item.province
                            }
                        })
                    };
                }
            },
            templateSelection: optionFormat,
            templateResult: optionFormat
        });
        $('#kt_form_main [name="vilage_id"]').select2({
            placeholder: "Kelurahan/Desa:...",
            ajax: {
                url: "{{route('web.ref_wilayah.search_vilage')}}",
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return {
                        q: params.term,
                        refId: $('#kt_form_main [name="city_id"]').val()
                    };
                },
                processResults: function (resp) {
                    return {
                        results: resp.data.map(function(item) {
                            return {
                                id: item.id,
                                text: item.name,
                                cat: item.district
                            }
                        })
                    };
                }
            },
            templateSelection: optionFormatB,
            templateResult: optionFormatB
        });
        $('#kt_update_main [name="city_id"]').select2({
            placeholder: "Kota/Kabupaten:...",
            dropdownParent: $("#modal-master-update"),
            ajax: {
                url: "{{route('web.ref_wilayah.search_city')}}",
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
                                cat: item.province
                            }
                        })
                    };
                }
            },
            templateSelection: optionFormat,
            templateResult: optionFormat
        });
        $('#kt_update_main [name="vilage_id"]').select2({
            placeholder: "Kelurahan/Desa:...",
            dropdownParent: $("#modal-master-update"),
            ajax: {
                url: "{{route('web.ref_wilayah.search_vilage')}}",
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return {
                        q: params.term,
                        refId: $('#kt_update_main [name="city_id"]').val()
                    };
                },
                processResults: function (resp) {
                    return {
                        results: resp.data.map(function(item) {
                            return {
                                id: item.id,
                                text: item.name,
                                cat: item.district
                            }
                        })
                    };
                }
            },
            templateSelection: optionFormatB,
            templateResult: optionFormatB
        });
    });
</script>