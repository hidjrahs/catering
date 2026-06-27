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
                url: "{{ route('web.ingredients.all-paginate') }}?device=web",
                method: 'GET',
                data: function(d) {
                    d.search = $('#search').val();
                    // d.orders=$('#search-orders').val();
                    // d.order_option=$('[name="orders-options"]:checked').val();
                }
            },
            'createdRow': function( row, data, dataIndex ) {
                $(row).addClass('cursor-pointer')
                    .attr('title','Edit Barang/Bahan Baku : '+data.name+' ?')
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
                        return '<div class="d-flex align-items-center">'+
                        '<div class="d-flex justify-content-start flex-column">'+
                            '<span class="text-dark fw-bold text-hover-primary mb-1 fs-6">'+data+'</span>'+
                            '<span class="text-muted fw-semibold text-muted d-block fs-7">Unit : '+row.unit+' '+row.satuan+'</span>'+
                        '</div></div>';
                    }
                }, {
                    data: 'default_price',
                    orderable: false,
                    render: function(data, type, row, meta) {
                        // '<span class="text-muted fw-semibold text-muted d-block fs-7">Supplier: '+(row.supp_name??'-')+'</span>'
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
            // formUpdate.find('[name="supplier_id"]').val('');
            formUpdate.find('[name="supplier_id[]"]').html('');
            formUpdate.find('[name="supplier_id[]"]').val(null).trigger("change");
            formUpdate.find('[name="name"]').val('');
            formUpdate.find('[name="unit"]').val('');
            formUpdate.find('[name="satuan"]').val('');
            formUpdate.find('[name="default_price"]').val('');
        },filledModalUpdate=function(parameter,url){
            let formUpdate=$("#kt_update_main");
            formUpdate.attr("action",url??"{{ $config['action-update'] }}");
            // formUpdate.find('[name="id"]').val(parameter.id);
            // 
            parameter.ref_supplier.forEach(v => {
                if (formUpdate.find('[name="supplier_id[]"]').find('option[value="'+v+'"]').length === 0) {
                    let newOptionCustomer = new Option(v.name, v.id, true, true);
                    $(newOptionCustomer).attr('data-phone', v.phone??'-');
                    // $(newOptionCustomer).attr('data-pj', v.pj??'-');
                    formUpdate.find('[name="supplier_id[]"]').append(newOptionCustomer); 
                }
            });
            let ref_supplier_id = parameter.ref_supplier.map(item => item.id);
            formUpdate.find('[name="supplier_id[]"]').val(ref_supplier_id).trigger("change");
            
            formUpdate.find('[name="name"]').val(parameter.name);
            formUpdate.find('[name="unit"]').val(parameter.unit);
            formUpdate.find('[name="satuan"]').val(parameter.satuan);
            formUpdate.find('[name="default_price"]').val(parameter.default_price);
        };
        $(document).on('click', '[data-ref]', function(event) {
            if ($(event.target).closest("div").hasClass("form-check")) {
                return;
            }
            let refId=$(this).attr('data-ref');
            if(!refId){
                return Swal.fire({icon: 'warning',text: 'Data Barang/Bahan Baku tidak ditemukan.'});
            }
            blockUIBody.block();
            clearModalUpdate();
            asyncData("{{ url('web/ingredients') }}/"+refId,{},"GET").then((response) => {
                if (!response.status) {
                    blockUIBody.release();
                    return Swal.fire({icon: 'error',text: response.message});
                }
                const resultlist = response.data;
                filledModalUpdate(resultlist, "{{ url('web/ingredients') }}/"+refId);
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
                text: 'Hapus data Barang/Bahan Baku?',
                showCancelButton: true,
                confirmButtonText: "<i class='fas text-white fa-save text-primary'></i> Ya",
                cancelButtonText: "<i class='fas text-white fa-times text-danger'></i> Tidak"
            }).then((willsend) => {
                if (willsend.isConfirmed) {
                    blockUIBody.block();
                    asyncData("{{route('web.ingredients.destroy')}}",values,"POST").then((response) => {
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
                            table.draw();
                            blockUIBody.release();
                            form.find('[name="supplier_id[]"]').val(null).trigger('change');
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
        const optionFormat = (item) => {
            if (!item.id) {
                return item.text;
            }
            var span = document.createElement('span');
            var template = '';
            template += '<div class="d-flex align-items-center">';
            template += '<div class="d-flex flex-column">'
            template += '<span class="fs-5 fw-bold lh-1">Supplier: ' + item.text + '</span>';
            var suptext='';
            if(!item.newTag){
                suptext='Kontak: ' + item.phone;
                if(item.element){
                    if(item.element.getAttribute('data-phone')){
                        suptext='Kontak: ' + (item.element.getAttribute('data-phone')!="null"?item.element.getAttribute('data-phone'):'-');
                    }
                }
            }else{
                suptext='Supplier Baru';
            }
            if(item.pj){
                template += '<span class="text-muted fs-6">PJ/CP:' + item.pj + '</span>';
            }else{
                if(item.element){
                    if(item.element.getAttribute('data-pj')){
                        template += '<span class="text-muted fs-6">PJ/CP:' + (item.element.getAttribute('data-pj')!="null"?item.element.getAttribute('data-pj'):'-') + '</span>';
                    }
                }
            }
            template += '<span class="text-muted fs-6">' + suptext + '</span>';
            template += '</div>';
            template += '</div>';
            span.innerHTML = template;
            return $(span);
        };
        $("#kt_select2_supplier").select2({
            placeholder: "Cari atau tambah Supplier...",
            tags: true, 
            ajax: {
                url: "{{route('web.suppliers.search')}}",
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
                                pj: item.penanggung_jawab,
                                phone: item.phone,
                                newTag:false
                            }
                        })
                    };
                }
            },
            createTag: function (params) {
                var term = $.trim(params.term);
                if (term === '') {
                    return null;
                }
                return {
                    id: term,       
                    text: term,
                    newTag: true    
                };
            },
            templateSelection: optionFormat,
            templateResult: optionFormat
        });
        $("#kt_select2_supplier_update").select2({
            placeholder: "Cari atau tambah Supplier...",
            tags: true, 
            ajax: {
                url: "{{route('web.suppliers.search')}}",
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
                                pj: item.penanggung_jawab,
                                phone: item.phone,
                                newTag:false
                            }
                        })
                    };
                }
            },
            createTag: function (params) {
                var term = $.trim(params.term);
                if (term === '') {
                    return null;
                }
                return {
                    id: term,       
                    text: term,
                    newTag: true    
                };
            },
            templateSelection: optionFormat,
            templateResult: optionFormat
        });
    });
</script>