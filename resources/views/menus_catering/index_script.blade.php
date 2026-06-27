<script>
    let table,
    form,
    submit,
    symb,
    defaultError= "Proses tidak berhasil.";
    $(function() {
        'use strict';
        $.fn.dataTable.ext.errMode = 'none' ?? 'throw';
        table = $('#main-table').DataTable({
            processing: true,
            serverSide: true,
            orderable: false,
            ajax: {
                url: "{{ route('web.menus_catering.all-paginate') }}?device=web",
                method: 'GET',
                data: function(d) {
                    d.search = $('#search').val();
                    // d.orders=$('#search-orders').val();
                    // d.order_option=$('[name="orders-options"]:checked').val();
                }
            },
            'createdRow': function( row, data, dataIndex ) {
                $(row).addClass('cursor-pointer')
                    .attr('title','Edit Menu : '+data.name+' ?')
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
                        if(row.ref_image.length){
                            symb='<img src="'+row.ref_image.pathfile+'" class="h-75 align-self-end" alt="">';
                        }else{
                            symb=data.trim()
                                .split(/\s+/)
                                .map(word => word.charAt(0).toUpperCase())
                                .join("");
                        }
                        return '<div class="d-flex align-items-center"><div class="symbol symbol-50px me-5">'+
                            '<span class="symbol-label bg-light-success text-success fw-bold">'+
                                symb+
                            '</span>'+
                        '</div>'+
                        '<div class="d-flex justify-content-start flex-column">'+
                            '<span class="text-dark fw-bold text-hover-primary mb-1 fs-6">'+data+'</span>'+
                            '<span class="text-muted fw-semibold text-muted d-block fs-7">Kategori : '+row.category_menu+'</span>'+
                        '</div></div>';
                    }
                }, {
                    data: 'porsi_standard',
                    orderable: false,
                    render: function(data, type, row, meta) {
                        return '<span class="text-dark fw-bold text-hover-primary d-block mb-1 fs-6">'+data+'</span>'+
                            '<span class="text-muted fw-semibold text-muted d-block fs-7">'+(row.selling_price?'Est. Harga: '+row.selling_price:'-')+'</span>';
                    },
                    class:'text-end'
                }
            ],
            aaSorting: [],
            dom: "<'row'" +
                "<'col-sm-6 d-flex align-items-center justify-conten-start'l>" +
                "<'col-sm-6 d-flex align-items-center justify-content-end'f>" +
                ">" +

                "<'table-responsive'tr>" +

                "<'row'" +
                "<'col-sm-12 col-md-12 d-flex align-items-center justify-content-center justify-content-md-start'i>" +
                "<'col-sm-12 col-md-12 d-flex align-items-center justify-content-center justify-content-md-end'p>" +
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
        selectIngredients,
        selectIngredientsUpdate,
        selectedIngredients=[],
        selectedIngredientsUpdate=[];

        const clearModalUpdate=function(){
            let formUpdate=$("#kt_update_main");
            formUpdate.attr("action","{{ $config['action-update']}}");
            formUpdate.find('[name="id"]').val('');
            formUpdate.find('[name="desc"]').val('');
            formUpdate.find('[name="name"]').val('');
            formUpdate.find('[name="porsi_standard"]').val('');
            formUpdate.find('[name="selling_price"]').val('');
            formUpdate.find('[name="category_menus_catering_id"]').val(null).trigger('change'); 
            formUpdate.find('[name="packet_catering_id[]"]').val(null).trigger('change'); 
            formUpdate.find('[name="is_active"]').prop('checked',false);
            clearIngredientsUpdate(formUpdate);
        },filledModalUpdate=function(parameter,url){
            let formUpdate=$("#kt_update_main");
            formUpdate.attr("action",url??"{{ $config['action-update'] }}");
            formUpdate.find('[name="id"]').val(parameter.id);
            formUpdate.find('[name="desc"]').val(parameter.desc);
            formUpdate.find('[name="name"]').val(parameter.name);
            formUpdate.find('[name="porsi_standard"]').val(parameter.porsi_standard);
            formUpdate.find('[name="selling_price"]').val(parameter.selling_price);
            let newOption = new Option(parameter.category_id.name, parameter.category_id.id, true, true);
            formUpdate.find('[name="category_menus_catering_id"]').append(newOption).trigger('change'); 
            // console.log(parameter)
            if(parameter.packet_id){
                parameter.packet_id.forEach(v => {
                    if (formUpdate.find('[name="packet_catering_id[]"]').find('option[value="'+v.id+'"]').length === 0) {
                        formUpdate.find('[name="packet_catering_id[]"]').append(new Option(v.name, v.id, true, true));
                    }
                });
                let listPacket=parameter.packet_id.map(item => item.id);
                formUpdate.find('[name="packet_catering_id[]"]').val(listPacket).trigger("change");
            }
            formUpdate.find('[name="is_active"]').prop('checked',parameter.is_active??false)
            selectedIngredientsUpdate=parameter.menuingredients;
            filledIngredientsUpdate(formUpdate);
        },optionFormatIngredients= (item) => {
            if (!item.id) return item.text;
            let html  = '<div class="d-flex flex-column">';
            html += '<span class="fs-7 fw-bold lh-1">' + item.text + '</span>';
            html += '</div>';

            return $(html);
        },clearIngredients=function(form){
            selectedIngredients=[]
            let target=form.find('[data-list="ingredient_list"');
            target.find('[data-ref]:not(.ingredients_none)').remove();
            target.find('.ingredients_none').removeClass('hidden');
            form.find('[name="category_menus_catering_id"]').val(null).trigger('change');
            countItem(form,selectedIngredients);
        },clearIngredientsUpdate=function(form){
            selectedIngredientsUpdate=[]
            let target=form.find('[data-list="ingredient_list"');
            target.find('[data-ref]:not(.ingredients_none)').remove();
            target.find('.ingredients_none').removeClass('hidden');
            form.find('[name="category_menus_catering_id"]').val(null).trigger('change');
            form.find('[name="ingredient_item"]').val(null).trigger('change'); 
            countItem(form,selectedIngredients);
        },filledIngredientsUpdate=function(form){
            let tagIngredients;
            let target=form.find('[data-list="ingredient_list"');
            $.each(selectedIngredientsUpdate,function(x,y){
                if(!target.find('[data-ref="'+y.id+'"]').length){
                    tagIngredients='<div class="d-flex align-items-center justify-content-between border rounded p-2 mb-2" data-ref="'+y.id+'">'+
                            '<input type="hidden" value="'+(y.refid??y.name)+'" name="item['+y.id+'][ingredient_id]">'+
                            '<input type="hidden" value="'+y.type+'" name="item['+y.id+'][type]">'+
                            '<div class="d-flex align-items-center">'+
                                '<a href="javascript:;" class="fs-6 text-dark text-hover-primary fw-semibold">'+
                                y.name+
                                '</a>'+
                            '</div>'+
                            '<div class="d-flex align-items-center">'+
                                '<label class="me-2 fw-semibold '+(y.type=='label'?'hidden':'')+'">Standard Qty:</label>'+
                                '<input type="text" name="item['+y.id+'][quantity]" class="form-control form-control-sm w-100px me-2 item_request '+(y.type=='label'?'hidden':'')+'" placeholder="Standard Qty" value="'+(y.quantity??0)+'"/>'+
                                '<a href="javascript:;" class="btn btn-sm btn-light-danger btn-active-danger px-2 py-1 text-center" data-delete="'+y.id+'"><i class="fa fa-trash p-0"></i></a>'
                            '</div>'+
                        '</div>';
                    // tagIngredients='<div class="d-flex align-items-center justify-content-between border rounded p-2 mb-2" data-ref="'+y.id+'">'+
                    //     '</div>';
                    target.append(tagIngredients);
                }
            });
            // console.log(selectedIngredients);
            if(selectedIngredientsUpdate.length){
                form.find('.ingredients_none').addClass('hidden');
            }else{
                form.find('.ingredients_none').removeClass('hidden');
            }
            countItem(form,selectedIngredientsUpdate);
        },filledIngredients=function(form){
            let tagIngredients;
            let target=form.find('[data-list="ingredient_list"');
            $.each(selectedIngredients,function(x,y){
                if(!target.find('[data-ref="'+y.id+'"]').length){
                    tagIngredients='<div class="d-flex align-items-center justify-content-between border rounded p-2 mb-2" data-ref="'+y.id+'">'+
                            '<input type="hidden" value="'+(y.refid??y.name)+'" name="item['+y.id+'][ingredient_id]">'+
                            '<input type="hidden" value="'+y.type+'" name="item['+y.id+'][type]">'+
                            '<div class="d-flex align-items-center">'+
                                '<a href="javascript:;" class="fs-6 text-dark text-hover-primary fw-semibold">'+
                                y.name+
                                '</a>'+
                            '</div>'+
                            '<div class="d-flex align-items-center">'+
                                '<label class="me-2 fw-semibold '+(y.type=='label'?'hidden':'')+'">Standard Qty:</label>'+
                                '<input type="text" name="item['+y.id+'][quantity]" class="form-control form-control-sm w-100px me-2 item_request '+(y.type=='label'?'hidden':'')+'" placeholder="Standard Qty" value="'+(y.quantity??0)+'"/>'+
                                '<a href="javascript:;" class="btn btn-sm btn-light-danger btn-active-danger px-2 py-1 text-center" data-delete="'+y.id+'"><i class="fa fa-trash p-0"></i></a>'
                            '</div>'+
                        '</div>';
                    target.append(tagIngredients);
                }
            });
            if(selectedIngredients.length){
                form.find('.ingredients_none').addClass('hidden');
            }else{
                form.find('.ingredients_none').removeClass('hidden');
            }
            countItem(form,selectedIngredients);
        },scrollDown=function(form){
            let height=form[0].scrollHeight;
            form.find('[data-list="ingredient_list"]').animate({ scrollTop: height }, 500);
        },countItem=function(form,selected){
            let totalItem = selected.filter(d => d.type === 'item').length;
            let totalLabel = selected.filter(d => d.type === 'label').length;
            form.find('[data-ref="total_ingredients"]').html(totalItem??0)
            form.find('[data-ref="total_ingredients_label"]').html(totalLabel?' | '+totalLabel+' Label':'')
        };
        $(document).on('click', '#main-table [data-ref]', function(event) {
            if ($(event.target).closest("div").hasClass("form-check")) {
                return;
            }
            let refId=$(this).attr('data-ref');
            if(!refId){
                return Swal.fire({icon: 'warning',text: 'Data Menu tidak ditemukan.'});
            }
            blockUIBody.block();
            clearModalUpdate();
            asyncData("{{ url('web/menus_catering') }}/"+refId,{},"GET").then((response) => {
                if (!response.status) {
                    blockUIBody.release();
                    return Swal.fire({icon: 'error',text: response.message});
                }
                const resultlist = response.data;
                filledModalUpdate(resultlist, "{{ url('web/menus_catering') }}/"+refId);
                blockUIBody.release();
                modalFormUpdate.modal('show');
            }).catch((error) => {
                blockUIBody.release();
                let res = (error.responseJSON) ? error.responseJSON : defaultError;
                Swal.fire({icon: 'error',text: res.message ?? defaultError});
                // console.log(res.message ?? defaultError)
                console.log(error)
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
                text: 'Hapus data Menu?',
                showCancelButton: true,
                confirmButtonText: "<i class='fas text-white fa-save text-primary'></i> Ya",
                cancelButtonText: "<i class='fas text-white fa-times text-danger'></i> Tidak"
            }).then((willsend) => {
                if (willsend.isConfirmed) {
                    blockUIBody.block();
                    asyncData("{{route('web.menus_catering.destroy')}}",values,"POST").then((response) => {
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
                            clearIngredientsUpdate(form);
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
                            clearIngredients(form);
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
        // 
        $('#kt_form_main [name="category_menus_catering_id"]').select2({
            placeholder: "Cari atau tambah produk...",
            ajax: {
                url: "{{route('web.category_menus.search')}}",
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
                                text: item.name
                            }
                        })
                    };
                }
            }
        });
        $('#kt_update_main [name="category_menus_catering_id"]').select2({
            placeholder: "Cari atau tambah produk...",
            dropdownParent: $("#modal-master-update"),
            ajax: {
                url: "{{route('web.category_menus.search')}}",
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
                                text: item.name
                            }
                        })
                    };
                }
            }
        });
        $('#kt_form_main [name="packet_catering_id[]"]').select2({
            placeholder: "Cari Paket Menu...",
            ajax: {
                url: "{{route('web.packet_menus.search')}}",
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
                                text: item.name
                            }
                        })
                    };
                }
            }
        });
        $('#kt_update_main [name="packet_catering_id[]"]').select2({
            placeholder: "Cari Paket Menu...",
            dropdownParent: $("#modal-master-update"),
            ajax: {
                url: "{{route('web.packet_menus.search')}}",
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
                                text: item.name
                            }
                        })
                    };
                }
            }
        });
        $(document).on('change', '#kt_update_main [data-change="type_ingredients"]', function(){
            let e=$(this),
                form=e.closest('form'),
                parent=e.parent(),
                val=e.val();
            selectIngredientsUpdate=null;
            if(val==='label'){
                parent.find('.select2-container').addClass('hidden');
                form.find('[name="ingredient_label"]').removeClass('hidden');
                form.find('[name="ingredient_item"]').addClass('hidden');
                form.find('[name="ingredient_item"]').val(null).trigger("change");
            }else{
                parent.find('.select2-container').removeClass('hidden');
                form.find('[name="ingredient_label"]').addClass('hidden');
                form.find('[name="ingredient_item"]').removeClass('hidden');
            }
        });
        $(document).on('change', '#kt_form_main [data-change="type_ingredients"]', function(){
            let e=$(this),
                form=e.closest('form'),
                parent=e.parent(),
                val=e.val();
            selectIngredients=null;
            if(val==='label'){
                parent.find('.select2-container').addClass('hidden');
                form.find('[name="ingredient_label"]').removeClass('hidden');
                form.find('[name="ingredient_item"]').addClass('hidden');
                form.find('[name="ingredient_item"]').val(null).trigger("change");
            }else{
                parent.find('.select2-container').removeClass('hidden');
                form.find('[name="ingredient_label"]').addClass('hidden');
                form.find('[name="ingredient_item"]').removeClass('hidden');
            }
        });
        $('#kt_form_main [name="ingredient_item"]').select2({
            placeholder: "Barang/Bahan Baku:...",
            ajax: {
                url: "{{route('web.ingredients.search')}}",
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
                                unit: item.unit
                            }
                        })
                    };
                }
            },
            templateSelection: optionFormatIngredients,
            templateResult: optionFormatIngredients
        });
        $('#kt_update_main [name="ingredient_item"]').select2({
            placeholder: "Barang/Bahan Baku:...",
            dropdownParent: $("#modal-master-update"),
            ajax: {
                url: "{{route('web.ingredients.search')}}",
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
                                unit: item.unit
                            }
                        })
                    };
                }
            },
            templateSelection: optionFormatIngredients,
            templateResult: optionFormatIngredients
        });
        $('#kt_form_main [name="ingredient_item"]').on('select2:select', function (e) {
            selectIngredients = e.params.data;
            selectIngredients={id:crypto.randomUUID(),refid:selectIngredients.id,name:selectIngredients.text,type:'item',quantity:selectIngredients.unit};
        });
        $('#kt_update_main [name="ingredient_item"]').on('select2:select', function (e) {
            selectIngredientsUpdate = e.params.data;
            selectIngredientsUpdate={id:crypto.randomUUID(),refid:selectIngredientsUpdate.id,name:selectIngredientsUpdate.text,type:'item',quantity:selectIngredientsUpdate.unit};
        });
        $(document).on('keyup', '#kt_form_main [name="ingredient_label"]', function(){
            let e=$(this);
            selectIngredients={id:crypto.randomUUID(),refid:null,name:e.val(),type:'label',quantity:0};
        });
        $(document).on('keyup', '#kt_update_main [name="ingredient_label"]', function(){
            let e=$(this);
            selectIngredientsUpdate={id:crypto.randomUUID(),refid:null,name:e.val(),type:'label',quantity:0};
        });
        $(document).on('click', '#kt_form_main [data-add="ingredient_list"]', function(){
            let e=$(this),
                form=e.closest('form'),
                type=form.find('[data-change="type_ingredients"]').val();
            if(!selectIngredients){
                return Swal.fire({icon: 'info',text: (type=='label')?'label belum di isi.':'Barang/Bahan baku belum di pilih.'});
            }
            if (selectIngredients && !selectedIngredients.some(el => el.id == selectIngredients.id)) {
                selectedIngredients.push(selectIngredients);
                selectIngredients=null;
                if(type=='label'){
                    form.find('[name="ingredient_label"]').val('');
                }else{
                    form.find('[name="ingredient_item"]').val(null).trigger("change");
                }
                filledIngredients(form);
                scrollDown(form);
            }else{
                return Swal.fire({icon: 'info',text: 'Barang/Bahan baku sudah terdaftar.'});
            }
        });
        $(document).on('click', '#kt_update_main [data-add="ingredient_list"]', function(){
            let e=$(this),
                form=e.closest('form'),
                type=form.find('[data-change="type_ingredients"]').val();
            if(!selectIngredientsUpdate){
                return Swal.fire({icon: 'info',text: (type=='label')?'label belum di isi.':'Barang/Bahan baku belum di pilih.'});
            }
            if (selectIngredientsUpdate && !selectedIngredientsUpdate.some(el => el.id == selectIngredientsUpdate.id)) {
                selectedIngredientsUpdate.push(selectIngredientsUpdate);
                selectIngredientsUpdate=null;
                if(type=='label'){
                    form.find('[name="ingredient_label"]').val('');
                }else{
                    form.find('[name="ingredient_item"]').val(null).trigger("change");
                }
                filledIngredientsUpdate(form);
                scrollDown(form);
            }else{
                return Swal.fire({icon: 'info',text: 'Barang/Bahan baku sudah terdaftar.'});
            }
        });
        $(document).on('click', '#kt_form_main [data-delete]', function() {
            let e=$(this),
                refId=e.attr('data-delete'),
                form=e.closest('form'),
                item=selectedIngredients.find(el => el.id == refId);
            if(item){
                selectedIngredients = selectedIngredients.filter(item => item.id !== refId);
                e.closest('[data-ref]').remove();
                countItem(form,selectedIngredients);
            }
            if(!selectedIngredients.length){
                form.find('.ingredients_none').removeClass('hidden');
            }
        });
        $(document).on('click', '#kt_update_main [data-delete]', function() {
            let e=$(this),
                refId=e.attr('data-delete'),
                form=e.closest('form'),
                item=selectedIngredientsUpdate.find(el => el.id == refId);
            if(item){
                selectedIngredientsUpdate = selectedIngredientsUpdate.filter(item => item.id !== refId);
                e.closest('[data-ref]').remove();
                countItem(form,selectedIngredientsUpdate);
            }
            if(!selectedIngredientsUpdate.length){
                form.find('.ingredients_none').removeClass('hidden');
            }
        });
    });
</script>