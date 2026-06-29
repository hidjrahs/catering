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
                url: "{{ route('web.employes.all-paginate') }}?device=web",
                method: 'GET',
                data: function(d) {
                    d.search = $('#search').val();
                    // d.orders=$('#search-orders').val();
                    // d.order_option=$('[name="orders-options"]:checked').val();
                }
            },
            'createdRow': function( row, data, dataIndex ) {
                $(row).addClass('cursor-pointer')
                    .attr('title','Edit Karyawan : '+data.name+' ?')
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
                                '<img src="'+((row.gender=='L')?hostUrl+'/media/svg/avatars/001-boy.svg':hostUrl+'/media/svg/avatars/002-girl.svg')+'" class="h-75 align-self-end" alt="">'+
                            '</span>'+
                        '</div>'+
                        '<div class="d-flex justify-content-start flex-column">'+
                            '<span class="text-dark fw-bold text-hover-primary mb-1 fs-6">'+data+'</span>'+
                            '<span class="text-muted fw-semibold text-muted d-block fs-7">No KTP/SIM : '+row.national_id+'</span>'+
                        '</div></div>';
                    }
                }, {
                    data: 'division',
                    orderable: false,
                    render: function(data, type, row, meta) {
                        return '<div class="d-flex align-items-center"><div class="symbol symbol-50px me-5">'+
                        '<div class="d-flex justify-content-start flex-column">'+
                            '<span class="text-dark fw-bold text-hover-primary mb-1 fs-6">'+data+'</span>'+
                            '<span class="text-muted fw-semibold text-muted d-block fs-7">No. HP : '+row.phone+'</span>'+
                        '</div></div>';
                    }
                }, {
                    data: 'address',
                    orderable: false,
                    render: function(data, type, row, meta) {
                        return '<span class="text-dark fw-bold text-hover-primary d-block mb-1 fs-6">'+data+'</span>'+
                        '<span class="text-muted fw-semibold text-muted d-block fs-7">TTL: '+(row.birth_place_date??'-')+'</span>';
                    }
                }, {
                    data: 'religion',
                    orderable: false,
                    render: function(data, type, row, meta) {
                        return '<span class="text-dark fw-bold text-hover-primary d-block mb-1 fs-6">'+data+'</span>'+
                        '<span class="text-muted fw-semibold text-muted d-block fs-7">Status: '+(row.status??'-')+'</span>';
                    }

                }, {
                    data: 'work_since',
                    class: "text-end",
                    orderable: false,
                    render: function(data, type, row, meta) {
                        // 
                        return '<span class="text-dark fw-bold text-hover-primary d-block mb-1 fs-7">'+data +'</span>'+
                        '<span class="text-muted fw-semibold text-muted d-block fs-7">Created: ' +row.created_at + '</span>';
                    }
                },
            ],
            aaSorting: [],
            columnDefs: [{ "width": "250px", "targets": [1,3] }],
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
        modalFormUpdate=$("#modal-master-update"),
        modalFormMain=$("#modal-master-main");
        const clearModalUpdate=function(){
            let formUpdate=$("#kt_update_main");
            formUpdate.attr("action","{{ $config['action-update']}}");
            formUpdate.find('[name]:not([name="_method"],[name="_token"])').val('');
        },filledModalUpdate=function(parameter,url){
            let formUpdate=$("#kt_update_main");
            formUpdate.attr("action",url??"{{ $config['action-update'] }}");
            $.each(parameter,function(x,y){
                if (y === null) {
                    return true;
                }else if(['educations', 'families', 'emergencies'].includes(x)){
                    let cloneTarget=formUpdate.find('[data-clone="'+x+'"]'),
                        parent=cloneTarget.parent(),firstItem=true;
                    parent.find('.data-item:not([data-clone])').remove();
                    $.each(y,function(xIn,yval){
                        let cloning=cloneTarget.clone(),
                            replaceName=cloning.find('[data-input]'),
                            uidTemp=Date.now().toString(36)+'-'+Math.random().toString(36).substr(2),
                            name;
                        cloning.removeClass('hidden');
                        cloning.removeAttr('data-clone');
                        cloning.removeAttr('data-max');
                        cloning.find('input').val('');
                        cloning.find('select').val('');
                        cloning.find('textarea').val('');
                        if(firstItem){
                            firstItem=false;
                        }else{
                            cloning.find('[data-deleted]').removeClass('hidden');
                            cloning.find('[data-deleted]').removeAttr('disabled');
                        }
                        $.each(yval,function(key,val){
                            if(cloning.find('[data-input="'+key+'"]').hasClass('form-select')){
                                cloning.find('[data-input="'+key+'"]').attr('name',x+'['+yval.id+']['+key+']').val(key=='gender'?val:val.toLowerCase());
                            }else{
                                cloning.find('[data-input="'+key+'"]').attr('name',x+'['+yval.id+']['+key+']').val(val);
                            }
                        });
                        parent.append(cloning)
                    });
                }else if(x=='users'){
                    formUpdate.find('[name="username"]').val(y.name??'');
                    formUpdate.find('[name="email"]').val(y.email??'');
                    formUpdate.find('[name="password"]').val('{{config("option.pass_default")}}');
                }else{
                    if(formUpdate.find('[name="'+x+'"]').hasClass('form-select')){
                        formUpdate.find('[name="'+x+'"]').val(x=='gender'?y:y.toLowerCase());
                    }else{
                        formUpdate.find('[name="'+x+'"]').val(y);
                    }
                }
            })
        };
        $(document).on('click', '[data-ref]', function(event) {
            if ($(event.target).closest("div").hasClass("form-check")) {
                return;
            }
            let refId=$(this).attr('data-ref');
            if(!refId){
                return Swal.fire({icon: 'warning',text: 'Data Karyawan tidak ditemukan.'});
            }
            blockUIBody.block();
            clearModalUpdate();
            asyncData("{{ url('web/employes') }}/"+refId,{},"GET").then((response) => {
                if (!response.status) {
                    blockUIBody.release();
                    return Swal.fire({icon: 'error',text: response.message});
                }
                const resultlist = response.data;
                filledModalUpdate(resultlist, "{{ url('web/employes') }}/"+refId);
                blockUIBody.release();
                modalFormUpdate.modal('show');
                setTimeout(function(){
                    $('#modal-master-update .skeleton.load').removeClass('load');
                    $("#modal-update").removeClass('hidden');
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
                text: 'Hapus data karyawan?',
                showCancelButton: true,
                confirmButtonText: "<i class='fas text-white fa-save text-primary'></i> Ya",
                cancelButtonText: "<i class='fas text-white fa-times text-danger'></i> Tidak"
            }).then((willsend) => {
                if (willsend.isConfirmed) {
                    blockUIBody.block();
                    asyncData("{{route('web.employes.destroy')}}",values,"POST").then((response) => {
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
                listValidation={},
                placeholder;
            $.each(listInput, (x, y) => {
                placeholder = $(y).attr('placeholder') || "";
                placeholder = placeholder.replace(/\s*:.*$/, "");
                listValidation[$(y).attr('name')]={
                    validators: {
                        notEmpty: { message: placeholder + " harus di isi." },
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
                            form.find('tr.data-item:not([data-clone])').remove();
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
        $(document).on('click', "#modal-form", function(e){
            modalFormMain.modal('show');
        });
        // let errorList = [];
        // validation.on('core.field.invalid', function(e) {
        //     errorList.push(e);
        // });
        $(document).on('submit', "#kt_form_main", function(e){
            e.preventDefault(),
            form=$(this),
            submit=form.find('#kt_main_submit'),
            validation.validate().then(function (status) {
                status=='Valid' && 
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
                            modalFormMain.modal('hide');
                            form.find('tr.data-item:not([data-clone])').remove();
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
            });
        });
        // $(document).on('submit', "#kt_form_main", function(e){
        //     e.preventDefault(),
        //     form=$(this),
        //     submit=form.find('#kt_form_submit'),
        //     validation.validate().then(function (valid) {
        //         valid=='Valid' && 
        //         (submit.attr("data-kt-indicator", "on"),
        //         submit.attr("disabled","true"),
        //         blockUIBody.release(),
        //         setTimeout(function () {
        //             form.ajaxSubmit({
        //                 type: "post",
        //                 url: form.attr("action"),
        //                 dataType: "json",
        //                 success: function (response) {
        //                     if(response.status!='200'){
        //                         return Swal.fire({icon: "warning",text: response.message})
        //                     }
        //                     Swal.fire({icon: "success",text: response.message});
        //                     submit.removeAttr("data-kt-indicator"),
        //                     submit.removeAttr("disabled"),
        //                     form.resetForm();
        //                     table.draw();
        //                     blockUIBody.release();
        //                 },
        //                 error: function (error) {
        //                     (error = error.responseJSON? error.responseJSON: defaultError),
        //                     Swal.fire({icon: "error",text: error.message? error.message: defaultError}),
        //                     submit.removeAttr("data-kt-indicator"),
        //                     submit.removeAttr("disabled"),
        //                     blockUIBody.release();
        //                 },
        //             })
        //         },5e2))
        //     })
        // });
        $(document).on('click', "[data-deleted]:not(disabled,.hidden)", function(){
            let e=$(this),
                tabpane=e.closest('.tab-pane'),
                ref=e.attr('data-deleted'),
                listDataItem=tabpane.find('.data-item:not(disabled,.hidden)');
            if(listDataItem.length>1){
                e.closest('.'+ref).remove();
            }
        });
        $(document).on('click', "[data-add]", function(){
            let e=$(this),
                tabpane=e.closest('.tab-pane'),
                type=e.attr('data-add'),
                cloneTarget=tabpane.find('[data-clone="'+type+'"]'),
                parent=cloneTarget.parent(),
                max=cloneTarget.attr('data-max'),
                item=parent.find('.data-item:not(disabled,.hidden)');
            if(max<item.length+1) {
                e.attr("data-kt-indicator", "on"),
                e.attr("disabled","true");
                return;
            };
            if(cloneTarget.length){
                let cloning=cloneTarget.clone(),
                    replaceName=cloning.find('[data-input]'),
                    uidTemp=Date.now().toString(36)+'-'+Math.random().toString(36).substr(2),
                    name;
                cloning.removeAttr('data-clone');
                cloning.removeClass('hidden');
                cloning.removeAttr('data-max');
                cloning.find('input').val('');
                cloning.find('select').val('').trigger('change');
                cloning.find('textarea').val('');
                cloning.find('[data-deleted]').removeClass('hidden');
                cloning.find('[data-deleted]').removeAttr('disabled');
                $.each(replaceName,function(x,y){
                    name=$(y).attr('data-input');
                    $(y).attr('name',type+'['+uidTemp+']['+name+']');
                });
                parent.append(cloning)
            }
        });
    });
</script>