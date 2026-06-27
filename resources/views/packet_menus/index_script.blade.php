<script>
    let table,
    form,
    submit,
    symb,
    imageU,
    defaultError= "Proses tidak berhasil.";
    $(function() {
        'use strict';
        $.fn.dataTable.ext.errMode = 'none' ?? 'throw';
        table = $('#main-table').DataTable({
            processing: true,
            serverSide: true,
            orderable: false,
            ajax: {
                url: "{{ route('web.packet_menus.all-paginate') }}?device=web",
                method: 'GET',
                data: function(d) {
                    d.search = $('#search').val();
                    // d.orders=$('#search-orders').val();
                    // d.order_option=$('[name="orders-options"]:checked').val();
                }
            },
            'createdRow': function( row, data, dataIndex ) {
                $(row).addClass('cursor-pointer')
                    .attr('title','Edit Paket Menu : '+data.name+' ?')
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
                        '</div></div>';
                    }
                }, {
                    data: 'is_active',
                    class:'text-end',
                    orderable: false,
                    render: function(data, type, row, meta) {
                        if(data){
                            return '<span class="badge py-3 px-4 fs-7 badge-light-primary">Aktif</span>';
                        }else{
                            return '<span class="badge py-3 px-4 fs-7 badge-light-warning">Non Aktif</span>';
                        }
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
            formUpdate.find('[name="is_active"]').prop('checked',false);
        },filledModalUpdate=function(parameter,url){
            let formUpdate=$("#kt_update_main");
            formUpdate.attr("action",url??"{{ $config['action-update'] }}");
            formUpdate.find('[name="name"]').val(parameter.name);
            formUpdate.find('[name="is_active"]').prop('checked',parameter.is_active??false);
        };
        $(document).on('click', '[data-ref]', function(event) {
            if ($(event.target).closest("div").hasClass("form-check")) {
                return;
            }
            let refId=$(this).attr('data-ref');
            if(!refId){
                return Swal.fire({icon: 'warning',text: 'Data Supplier tidak ditemukan.'});
            }
            blockUIBody.block();
            clearModalUpdate();
            asyncData("{{ url('web/packet_menus') }}/"+refId,{},"GET").then((response) => {
                if (!response.status) {
                    blockUIBody.release();
                    return Swal.fire({icon: 'error',text: response.message});
                }
                const resultlist = response.data;
                filledModalUpdate(resultlist, "{{ url('web/packet_menus') }}/"+refId);
                blockUIBody.release();
                modalFormUpdate.modal('show');
                setTimeout(function(){
                    $('#modal-master-update .skeleton.load').removeClass('load');
                    $("#modal-update").removeClass('hidden');
                    $("#modal-update .hidden:not(.preview-image,.unreaveal)").removeClass('hidden');
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
                text: 'Hapus data Supplier?',
                showCancelButton: true,
                confirmButtonText: "<i class='fas text-white fa-save text-primary'></i> Ya",
                cancelButtonText: "<i class='fas text-white fa-times text-danger'></i> Tidak"
            }).then((willsend) => {
                if (willsend.isConfirmed) {
                    blockUIBody.block();
                    asyncData("{{route('web.packet_menus.destroy')}}",values,"POST").then((response) => {
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
                            form.find('.preview-image>span').html('');
                            form.find('.preview-image').addClass('hidden');
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
        $(document).on('click', ".clear-image", function(e){
            let t=$(event.target),
                parent=t.closest('.wrap-image');
            parent.find('[name="file_berkas"').val("");
            parent.find('.preview-image>span').html("");
            parent.find('.preview-image').addClass('hidden');
            if(parent.find('[data-place="filename"]').length){
                parent.find('[data-place="filename"]').html('No file selected.');
                parent.find('[name="has_image"]').prop("checked", false);
            }
        });
        $(document).on('click', ".wrap-image .label-preview", function(e){
            let wrap=$(this).closest('.wrap-image');
            wrap.find('[name="file_berkas"]').trigger('click');
        });
        $(document).on('change', "[name='file_berkas']", function(e){
            let t=$(event.target),
                parent=t.closest('.wrap-image'),
                image;
            const files = this.files;
            if(files){
                parent.find('.preview-image>span').html('');
                $.each(files, function(index, file) {
                    if (/(\.|\/)(gif|jpe?g|png|webp)$/i.test(file.name)) {
                        const reader = new FileReader();
                        if(parent.find('[data-place="filename"]').length){
                            parent.find('[data-place="filename"]').html(file.name);
                        }
                        reader.onload = function(e) {
                            image="<img src='"+e.target.result+"'></img>";
                            parent.find('.preview-image>span').append(image);
                        };
                        reader.readAsDataURL(file);
                    }
                });
                parent.find('.preview-image').removeClass('hidden');
            }
        });
    });
</script>