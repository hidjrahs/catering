<script>
    let table,
    tableMain,
    form,
    submit,
    timer=10000,
    defaultError= "Proses tidak berhasil.";
    $(function() {
        'use strict';
        $.fn.dataTable.ext.errMode = 'none' ?? 'throw';
        let listIngredients=[];
        tableMain = $('#main-table').DataTable({
            processing: true,
            serverSide: true,
            orderable: false,
            ajax: {
                url: "{{ route('web.menus_catering.all-preview') }}?device=web",
                method: 'GET',
            },
            'createdRow': function( row, data, dataIndex ) {
                $(row).attr('data-ref',data.id);
                if(data.ingredients.length){
                    listIngredients.push({refid:data.id,ingredients:data.ingredients})
                }
            },
            columns: [{
                    data: null,
                    searchable: false,
                    orderable: false,
                    render: function(data, type, row, meta) {
                        // let number=meta.row + meta.settings._iDisplayStart + 1;
                        return '<button type="button" class="btn btn-sm btn-icon btn-light btn-active-light-primary toggle h-25px w-25px" data-toggle="expand">'+
                            '<span class="svg-icon svg-icon-3 m-0 toggle-off">'+
                                '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">'+
                                    '<rect opacity="0.5" x="11" y="18" width="12" height="2" rx="1" transform="rotate(-90 11 18)" fill="currentColor" />'+
                                    '<rect x="6" y="11" width="12" height="2" rx="1" fill="currentColor" />'+
                                '</svg>'+
                            '</span>'+
                            '<span class="svg-icon svg-icon-3 m-0 toggle-on">'+
                                '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">'+
                                    '<rect x="6" y="11" width="12" height="2" rx="1" fill="currentColor" />'+
                                '</svg>'+
                            '</span>'+
                        '</button>';
                    },
                    class: 'text-center'
                }, {
                    data: 'recipe_name',
                    orderable: false,
                    render: function(data, type, row, meta) {
                        return '<span class="text-dark">'+data +'</span>'+
                                '<span class="badge py-1 px-2 badge-light-success">' +row.id_menu + '</span>';
                    }
                }, {
                    data: 'category',
                    orderable: false,
                }, {
                    data: 'paket',
                    orderable: false,
                    class:'text-center'
                }, {
                    data: 'portion_standard',
                    orderable: false,
                    class:'text-end',
                    render: function(data, type, row, meta) {
                        return '<span class="text-dark">'+data +'</span>'+
                                '<span class="text-muted fw-semibold text-muted d-block fs-7">Rp. ' +row.total + '</span>';
                    }
                }
            ],
            aaSorting: [],
            dom: "<'row'" +
                "<'col-sm-6 d-flex align-items-center justify-conten-start'l>" +
                "<'col-sm-6 d-flex align-items-center justify-content-end'f>" +
                ">" +

                "<'table-responsive'tr>" +

                "<'row'" +
                "<'col-sm-12 col-md-6 d-flex align-items-center justify-content-center justify-content-md-start'i>" +
                "<'col-sm-12 col-md-6 d-flex align-items-center justify-content-center justify-content-md-end'p>" +
                ">"
        });
        let activeList;
        table = $('#list-import').DataTable({
            processing: true,
            serverSide: true,
            orderable: false,
            pageLength: 5,
            ajax: {
                url: "{{ route('web.menus_catering.batch-paginate') }}?device=web",
                method: 'GET',
            },
            'createdRow': function( row, data, dataIndex ) {
                $(row).attr('data-ref',data.id);
                if(activeList==data.id){
                    $(row).addClass('active');
                }
            },
            columns: [{
                    data: null,
                    searchable: false,
                    orderable: false,
                    render: function(data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    },
                    class: 'text-center'
                }, {
                    data: 'filename',
                    orderable: false,
                }, {
                    data: 'action',
                    orderable: false,
                    class:'text-center'
                }
            ],
            aaSorting: [],
            dom: "<'row'" +
                "<'col-sm-6 d-flex align-items-center justify-conten-start'l>" +
                "<'col-sm-6 d-flex align-items-center justify-content-end'f>" +
                ">" +

                "<'table-responsive'tr>" +

                "<'row'" +
                "<'col-sm-12 col-md-6 d-flex align-items-center justify-content-center justify-content-md-start'i>" +
                "<'col-sm-12 col-md-6 d-flex align-items-center justify-content-center justify-content-md-end'p>" +
                ">"
        });
        const fieldValidation=function(form){
            let listInput=form.find('input:not([type=hidden])[name][placeholder]:not(.unvalidate), textarea[name][placeholder]:not(.unvalidate), select[name][placeholder]:not(.unvalidate)'),
                listValidation={},
                placeholder;
            $.each(listInput, (x, y) => {
                placeholder = form.find(y).attr('placeholder') || "";
                placeholder = placeholder.replace(/\s*:.*$/, "");
                listValidation[form.find(y).attr('name')]={
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
        formImportMain=$("#kt_import_main"),
        validation=FormValidation.formValidation(formMain[0],fieldValidation(formMain)),
        validationImport=FormValidation.formValidation(formImportMain[0],fieldValidation(formImportMain)),
        blockUIBody = new KTBlockUI($("#kt_app_root")[0], {
            message: '<div class="blockui-message"><span class="spinner-border text-primary"></span> Loading...</div>',
        });
        const Toast = Swal.mixin({
            toast: true,
            position: "bottom-end",
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.onmouseenter = Swal.stopTimer;
                toast.onmouseleave = Swal.resumeTimer;
            }
        }),
        progressImport=function(refId,submit,form,table,tablePreview,Toast){
            setTimeout(function () {
                const process= asyncData("{{url('web/menus_catering/check_batch')}}/"+refId,{}).then((response) => {
                    if(response.status!='200'){
                        Swal.fire({icon: "warning",text: response.message})
                    }
                    if(response.data.is_done){
                        submit.removeAttr("data-kt-indicator"),
                        submit.addClass("hidden"),
                        form.resetForm(),
                        table.draw(),
                        tablePreview.draw(),
                        Toast.fire({icon: "success", title:response.message}),
                        clearTimeout(progressImport);
                        blockUIBody.release()
                        $("#label-preview").html("");
                    }else{
                        progressImport(refId,submit,form,table,tablePreview,Toast);
                    }
                }).catch((error) => {
                    let res = (error.responseJSON) ? error.responseJSON : defaultError;
                    Swal.fire({icon: 'error',text: res.message ?? defaultError});
                    console.log(res.message ?? defaultError)
                });
            },5000)
        };
        $(document).on('submit', "#kt_form_main", function(e){
            e.preventDefault(),
            form=$(this);
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
                            // reload Table berkas
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
        $(document).on('submit', "#kt_import_main", function(e){
            e.preventDefault(),
            form=$(this);
            submit=form.find('#kt_import_submit');
            let refId=form.find("[name='idTemp']");
            validationImport.validate().then(function (valid) {
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
                            progressImport(refId.val(),submit,form,table,tableMain,Toast);
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
        $(document).on('click', '[data-delete]', function() {
            let e =$(this),
                refId=e.attr('data-delete');
            Swal.fire({
                icon: 'warning',
                text: 'Hapus Berkas Excel?',
                showCancelButton: true,
                confirmButtonText: "<i class='fas text-white fa-save text-primary'></i> Ya",
                cancelButtonText: "<i class='fas text-white fa-times text-danger'></i> Tidak"
            }).then((willsend) => {
                if (willsend.isConfirmed) {
                    asyncData("{{url('web/menus_catering/batch')}}/"+refId,{},'DELETE').then((response) => {
                        if(response.status!='200'){
                            Swal.fire({icon: "warning",text: response.message})
                        }
                        table.draw();
                        if(activeList==refId){
                            activeList=null;
                            tableMain.draw();
                            submit=formImportMain.find('#kt_import_submit');
                            submit.removeAttr("data-kt-indicator")
                            submit.removeAttr("disabled")
                            submit.attr("disabled","true")
                            submit.addClass("hidden");
                        }
                    }).catch((error) => {
                        let res = (error.responseJSON) ? error.responseJSON : defaultError;
                        Swal.fire({icon: 'error',text: res.message ?? defaultError});
                        console.log(res.message ?? defaultError)
                    });
                };
            });
            
        });
        $(document).on('click', '[data-toggle="expand"]', function() {
            let e=$(this),
                parent=e.closest('tr'),
                refId=parent.attr('data-ref'),
                listingredient,
                rawTable,
                rawTr,classTr;
            e.toggleClass('active');
            if(!e.hasClass('active')){
                parent.siblings('[data-detail="'+refId+'"]').remove();
                return;
            }
            parent.siblings('[data-detail]').remove();
            if(!parent.siblings('[data-detail="'+refId+'"]').length){
                listingredient = listIngredients.find(item => item.refid === refId).ingredients;
                rawTr="";
                $.each(listingredient,function(i,item){
                    classTr='';
                    if(!item.qty){
                        classTr='bg-success text-white';
                    }
                    rawTr+=`<tr class="${classTr}">
                    <td>${item.ingredient_name}</td>
                    <td>${item.qty??''}</td>
                    <td>${item.satuan??''}</td>
                    <td>${item.unit??''}</td>
                    <td class="text-end">${item.price_per_unit??''}</td>
                    </tr>`;
                });
                rawTable='<table class="table align-middle  fs-8 gy-2 gs-2 table-sm table-row-bordered table-nowrap mb-0 ">'+
                            '<thead>'+
                                '<tr class="fw-bold text-muted bg-light">'+
                                    '<th>Barang/Bahan Baku</th>'+
                                    '<th>Qty</th>'+
                                    '<th>Satuan</th>'+
                                    '<th>Unit</th>'+
                                    '<th class="min-w-100px text-end">Harga per Unit</th>'+
                                '</tr>'+
                            '</thead>'+
                            '<tbody class="fw-bold text-gray-600">'+rawTr+'</tbody>'+
                        '</table>';
                parent.after('<tr data-detail="'+refId+'"><td colspan="5" class="p-0">'+rawTable+'</td></tr>');
            }
        });
        $(document).on('click', '[data-preview]', function() {
            let e =$(this),
                refId=e.attr('data-preview');
            if(!e.closest('tr').hasClass('active')){
                e.closest('tr').siblings('.active').removeClass('active');
                e.closest('tr').addClass('active');
                activeList=refId;
            }
            form=$('#kt_import_main');
            let btnRef=form.find('#kt_import_submit')
            tableMain.ajax.url("{{ route('web.menus_catering.all-preview') }}?device=web&id_temp="+refId).load((response)=>{
                if(response.recordsTotal>0){
                    form.find("[name='idTemp']").val(refId);
                    btnRef.removeAttr("disabled");
                    btnRef.removeClass("hidden");
                }else{
                    form.find("[name='idTemp']").val('');
                    btnRef.attr("disabled","true")
                    btnRef.addClass("hidden");
                    Toast.fire({icon: "warning", title: 'Data Excel Kosong'});
                }
                $("#label-preview").html(": ["+e.attr('data-append-title')+"]");
            });
        });
        let resLoad=()=>{
            table.ajax.reload( null, false ); 
        };
        setInterval( function () {
            resLoad();
        }, timer );
        $(document).on('click', '#kt_run_queue', function() {
            let e=$(this);
            Swal.fire({
                icon: 'question',
                text: 'Jalankan queue import_temp sekarang?',
                showCancelButton: true,
                confirmButtonText: "<i class='fas text-white fa-save text-primary'></i> Ya, Jalankan",
                cancelButtonText: "<i class='fas text-white fa-times text-danger'></i> Batal"
            }).then((willsend) => {
                if (willsend.isConfirmed) {
                    e.attr("disabled", "true");
                    e.html('<span class="spinner-border spinner-border-sm align-middle me-2"></span> Menjalankan...');
                    $.ajax({
                        url: "{{ route('run.queue.import') }}",
                        method: "GET",
                        dataType: "json",
                        success: function (response) {
                            Swal.fire({icon: "success", text: response.message || 'Queue berhasil dijalankan.'});
                        },
                        error: function (xhr) {
                            let res = (xhr.responseJSON) ? xhr.responseJSON : {message: "Gagal menjalankan queue."};
                            Swal.fire({icon: "error", text: res.message || "Gagal menjalankan queue."});
                        },
                        complete: function() {
                            e.removeAttr("disabled");
                            e.html('<i class="fa-solid fa-play"></i> Jalankan Queue Import');
                        }
                    });
                }
            });
        });
    });
</script>