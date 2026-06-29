<script>
    let table,
        listActivemenu=[],
        totalKeseluruhan,
        menuSelected,
        form,submit,listPacket=[],
        rincianItem,
        defaultError= "Proses tidak berhasil.";
    $(function() {
        'use strict';
        $.fn.dataTable.ext.errMode = 'none' ?? 'throw';
        table = $('#main-table').DataTable({
            processing: true,
            serverSide: true,
            orderable: false,
            ajax: {
                url: "{{ route('web.customer_service.all-paginate') }}?device=web",
                method: 'GET',
                data: function(d) {
                    d.search = $('#search').val();
                    d.date = $('#filter_time').val();
                    d.orders = $('#order').val();
                }
            },
            'createdRow': function( row, data, dataIndex ) {
                $(row).addClass('cursor-pointer')
                    .attr('title','Edit data orders : '+data.name+' ?')
                    .attr('data-ref',data.id);
            },
            columns: [{
                    data: null,
                    searchable: false,
                    orderable: false,
                    render: function(data, type, row, meta) {
                        return '<div class="form-check form-check-sm form-check-custom form-check-solid">'+
                                    (row.status.toLowerCase()=='pending'?'<input class="form-check-input border border-primary border-active active border-1 cursor-pointer widget-main-check" name="row-check[]" type="checkbox" value="'+row.id+'">':'')+
                                '</div>';
                    },
                }, {
                    data: 'order_ticket',
                    orderable: false,
                    render: function(data, type, row, meta) {
                        return '<span class="badge py-2 px-3 badge-light-primary fs-7"><i class="fa-solid fa-barcode me-2"></i> '+(data??='-')+'</span>'+
                        '<span class="text-muted fw-semibold text-muted d-block fs-8 py-1 px-3">'+row.created_at+'</span>';
                    }
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
                            '<span class="text-muted fw-semibold text-muted d-block fs-8">Nomor : '+row.phone+'</span>'+
                        '</div></div>';
                    }
                }, {
                    data: 'est_price',
                    orderable: false,
                    render: function(data, type, row, meta) {
                        // return '<span class="text-dark fw-bold text-hover-primary d-block mb-1 fs-6"><i>(~)</i> Rp. '+data.toLocaleString("id-ID")+'</span>'+
                        return '<span class="text-dark fw-bold text-hover-primary d-block mb-1 fs-6">Event: '+row.event_type+'</span>'+
                        '<span class="text-muted fw-semibold text-muted d-block fs-8">Total: '+row.total_items+' menu</span>';
                    }
                }, {
                    data: 'status',
                    orderable: false,
                    render: function(data, type, row, meta) {
                        return '<span class="badge py-2 px-3 badge-light-primary fs-6"> '+(data??='-')+'</span>'+
                        '<span class="text-muted fw-semibold text-muted d-block fs-8">Tamu: '+(row.total_guest??'-')+' | Undangan : '+(row.total_invite??'-')+'</span>';
                    },
                    class:"text-end"
                }, {
                    data: 'delivery_date',
                    orderable: false,
                    render: function(data, type, row, meta) {
                        return '<span class="text-dark fw-bold text-hover-primary d-block mb-1 fs-8">'+(data??='-')+'<i class="fa-solid fa-truck text-primary ms-2"></i><span>'+
                        '<span class="text-muted fw-semibold text-muted d-block fs-8">'+(row.event_date??'-')+'<i class="fa-solid fa-calendar-day text-success ms-2"></i></span>';
                    },
                    class:"text-end "
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
        table.on( 'draw', function () {
            let totalAll = table.page.info().recordsTotal;
            $('[data-label="count-data"]').html(totalAll);
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
        contentorder=$("#list_order");
        $(document).on('click', '.form-close', function(event) {
            let parent=formUpdate.parent();
            parent.addClass('hidden');
            parent.removeClass('d-lg-flex');
        });
        const fieldValidation=function(form){
            let listInput=form.find('input:not([type=hidden])[name][placeholder]:not(.unvalidate), textarea[name][placeholder]:not(.unvalidate), select[name][placeholder]:not(.unvalidate)'),
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
        formUpdate=$("#kt_form_update"),
        validation=FormValidation.formValidation(formUpdate[0],fieldValidation(formUpdate));
        const clearModalUpdate=function(){
            let formUpdate=$("#kt_form_update");
            formUpdate.attr("action","{{ $config['action']}}");
            formUpdate.find('[name="id"]').val('');
            formUpdate.find('[data-ref="order-label"]').html('');
            formUpdate.find('[name="address"]').val('');
            formUpdate.find('[name="phone"]').val('');

            formUpdate.find('[name="customer_id"]').html('');
            formUpdate.find('[name="customer_id"]').trigger('change');
            formUpdate.find('[name="city_id"]').html('');
            formUpdate.find('[name="city_id"]').trigger('change');
            formUpdate.find('[name="vilage_id"]').html('');
            formUpdate.find('[name="vilage_id"]').trigger('change');

            formUpdate.find('[data-ref="total_menu"]').html('0');
            formUpdate.find('[name="estimate_price"]').val('');
            formUpdate.find('[name="estimate_price_label"]').val('Rp. 0');
            formUpdate.find('[name="delivery_date"]').val('');
            formUpdate.find('[name="event_date"]').val('');
            formUpdate.find('[name="event_time"]').val('');
            formUpdate.find('[name="total_guest"]').val('');
            formUpdate.find('[name="total_invite"]').val('');
            formUpdate.find('[name="desc"]').val('');
            formUpdate.find('[name="desc_extra"]').val('');
            formUpdate.find('[name="event_type[]"]').val(null).trigger("change");
            formUpdate.find('[name="package_type[]"]').val(null).trigger("change");
            formUpdate.find('[name="venue"]').val('');
            formUpdate.find('#list_order>#item_none').removeClass('hidden');
            formUpdate.find('#list_order>div:not(#item_none)').remove();
            $('#packet-id').multipleSelect('setSelects', []);
        },filledModalUpdate=function(parameter,url){
            let formUpdate=$("#kt_form_update");
            formUpdate.attr("action",url??"{{ $config['action'] }}");
            formUpdate.find('[name="id"]').val(parameter.id);
            formUpdate.find('[data-ref="order-label"]').html('No. Order: '+parameter.order_ticket);
            formUpdate.find('[name="address"]').val(parameter.customer.address);
            formUpdate.find('[name="phone"]').val(parameter.customer.phone);
            let packets=parameter.package_type.map(t => t.toLowerCase());
            let packetId = listPacket.filter(item => packets.includes(item.name.toLowerCase())).map(item => item.id);
            $('#packet-id').multipleSelect('setSelects', packetId);
            let cityRef=parameter.customer.city_id;
            let newOption = new Option(cityRef.name, cityRef.id, true, true);
            $(newOption).attr('data-cat', cityRef.province_id.name);
            formUpdate.find('[name="city_id"]').append(newOption).trigger('change'); 
            let vilageRef=parameter.customer.vilage_id;
            let newOptionVilage = new Option(vilageRef.name, vilageRef.id, true, true);
            $(newOptionVilage).attr('data-cat', vilageRef.district_id.name);
            formUpdate.find('[name="vilage_id"]').append(newOptionVilage).trigger('change');

            let newOptionCustomer = new Option(parameter.customer.name, parameter.customer.id, true, true);
            $(newOptionCustomer).attr('data-phone', parameter.customer.phone);
            formUpdate.find('[name="customer_id"]').append(newOptionCustomer).trigger('change'); 
            formUpdate.find('[name="delivery_date"]').val(parameter.delivery_date);
            formUpdate.find('[name="event_date"]').val(parameter.event_date);
            formUpdate.find('[name="event_time"]').val(parameter.event_time);
            formUpdate.find('[name="total_guest"]').val(parameter.total_guest);
            formUpdate.find('[name="total_invite"]').val(parameter.total_invite);
            formUpdate.find('[name="desc"]').val(parameter.desc);
            formUpdate.find('[name="desc_extra"]').val(parameter.desc_extra);
            formUpdate.find('[name="dp"]').val(formatIndonesia(parameter.dp));

            parameter.event_type.forEach(v => {
                if (formUpdate.find('[name="event_type[]"]').find('option[value="'+v+'"]').length === 0) {
                    formUpdate.find('[name="event_type[]"]').append(new Option(v, v, true, true));
                }
            });
            parameter.package_type.forEach(v => {
                if (formUpdate.find('[name="package_type[]"]').find('option[value="'+v+'"]').length === 0) {
                    formUpdate.find('[name="package_type[]"]').append(new Option(v, v, true, true)); 
                }
            });
            formUpdate.find('[name="event_type[]"]').val(parameter.event_type).trigger("change");
            formUpdate.find('[name="package_type[]"]').val(parameter.package_type).trigger("change");
            
            formUpdate.find('[name="venue"]').val(parameter.venue);
            //Add Item Menu
            listActivemenu=parameter.items;
            filledOrder();
            if(parameter.rincianbiaya.length){
                filledRincian(parameter.rincianbiaya);
            }else{
                initRincian();
            }
            
        },formatIndonesia=function(numberString){
            let num = parseFloat(numberString);
            return num
                .toLocaleString("id-ID", {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                });
        },ribuanDecString=function(num){
            return Number(num).toLocaleString("id-ID",{
                    minimumFractionDigits: 0,
                    maximumFractionDigits: 2
                });
        },ribuanDecimal=function(num){
            return num.toLocaleString('id-ID', {
                minimumFractionDigits: 0,
                maximumFractionDigits: 2
            });
        },filledRincian=function(rincianbiaya){
            let listRincian=$("#list_rincian"),tag="";
            rincianItem=[];
            $.each(rincianbiaya,function(x,y){
                tag+='<div class="row ps-2 pe-2 item-rincian mb-2" ref-id="'+y.id+'">'+
                    '<div class="col-2 pe-1 ps-1">'+
                        '<input type="text" name="rincian['+y.id+'][qty]" class="form-control form-control-sm unvalidate text-end ref-qty input-fixed" placeholder="Qty" value="'+(y.quantity?ribuanDecString(y.quantity):'')+'">'+
                    '</div>'+
                    '<div class="col-5 pe-1 ps-1">'+
                        '<input type="text" name="rincian['+y.id+'][name]" class="form-control form-control-sm unvalidate" placeholder="Rincian" value="'+(y.name??'')+'">'+
                    '</div>'+
                    '<div class="col-2 pe-1 ps-1">'+
                        '<input type="text" name="rincian['+y.id+'][price]" class="form-control form-control-sm unvalidate text-end ref-price input-fixed" placeholder="Harga" value="'+(y.price?ribuanDecString(y.price):0)+'">'+
                    '</div>'+
                    '<div class="col-2 pe-1 ps-1">'+
                        '<span data-ref="rincian_total" class="form-control form-control-sm text-end"></span>'+
                    '</div>'+
                    '<div class="col-1 pe-1 ps-2 pt-1">'+
                        '<a href="javascript:;" class="btn btn-sm btn-light-danger btn-active-danger px-2 py-1 text-center" data-delete="rincian"><i class="fa fa-trash p-0"></i></a>'+
                    '</div>'+
                '</div>';
                rincianItem.push({id:y.id,qty:y.quantity,price:y.price,price_total:null});
            });
            listRincian.html(tag);
            // console.log(rincianItem)
            calcRincian();
        },optionFormat = (item) => {
            if (!item.id) return item.text;

            let html  = '<div class="d-flex flex-column">';
            html += '<span class="fs-5 fw-bold lh-1">' + item.text + '</span>';
            html += '<span class="text-muted fs-6">' + (item.newTag ? 'Customer Baru' : 'Kontak: ' + (item.phone??$(item.element).attr('data-phone'))) + '</span>';
            html += '</div>';

            return $(html);
        },selectionFormat = (item) => {
            if (item.newTag) {
                return item.text + " (Baru)";
            }
            return item.text || item.id;
        },hitungHarga=(menu)=>{
            const sprice = parseFloat(menu.selling_price.replace(/\./g, "")),
                pstandard = parseFloat(menu.porsi_standard.replace(/\./g, "")),
                prequest = parseFloat(menu.porsi_request.replace(/\./g, "")),
                harga = (sprice / pstandard) * prequest;
            return Math.round(harga);
        };
        let filledOrder=function(){
            let tagOrder;
            contentorder.find("#item_none").addClass('hidden');
            $.each(listActivemenu,function(x,y){
                if(!contentorder.find('[data-ref="'+y.id+'"]').length){
                    tagOrder='<div class="d-flex align-items-center justify-content-between border rounded p-2 mb-2" data-ref="'+y.id+'">'+
                            '<input type="hidden" value="'+y.id+'" name="item['+y.id+'][menus_catering_id]">'+
                            '<input type="hidden" value="'+(y.porsi_standard??0)+'" name="item['+y.id+'][porsi_standard]">'+
                            '<input type="hidden" value="'+(y.is_quantity)+'" name="item['+y.id+'][is_request]">'+
                            '<div class="d-flex align-items-center">'+
                                '<span class="symbol-label fw-bold px-2 py-1 text-center rounded"><i class="fa-solid '+y.icon+'"></i></span>'+
                                '<a href="javascript:;" class="fs-8 text-dark text-hover-primary fw-semibold">'+
                                y.name+
                                '</a>'+
                            '</div>'+
                            '<div class="d-flex align-items-center">'+
                                '<label class="me-2 fw-semibold hidden">Est Harga:</label>'+
                                '<input type="hidden" name="item['+y.id+'][price]" class="form-control form-control-sm w-100px me-2 bg-light unvalidate" readonly value="'+(y.selling_price??0)+'"/>'+
                                '<label class="me-2 fw-semibold '+(y.is_quantity=='1'?'':'hidden')+'">Porsi:</label>'+
                                '<input type="'+(y.is_quantity=='1'?'text':'hidden')+'" name="item['+y.id+'][quantity]" class="form-control form-control-sm w-100px me-2 item_request" placeholder="Jumlah" value="'+(y.porsi_request??0)+'"/>'+
                                '<a href="javascript:;" class="btn btn-sm btn-light-danger btn-active-danger px-2 py-1 text-center" data-delete="'+y.id+'"><i class="fa fa-trash p-0"></i></a>'
                            '</div>'+
                        '</div>';
                    contentorder.append(tagOrder);
                }
            });
            countItem();
            totalHarga();
        },
        countItem=function(){
            $('[data-ref="total_menu"]').html(listActivemenu.length??0)
        },
        totalHarga=function(){
            totalKeseluruhan = listActivemenu.reduce((acc, menu) => {
                return acc + hitungHarga(menu);
            }, 0);
            formUpdate.find('[name="estimate_price"]').val(totalKeseluruhan);
            formUpdate.find('[name="estimate_price_label"]').val('Rp. '+totalKeseluruhan.toLocaleString("id-ID"));
        },scrollDown=function(){
            let e=$("#list_order"),
                height=e[0].scrollHeight;
            e.find('[data-list="ingredient_list"]').animate({ scrollTop: height }, 500);
        };
        $(document).on('click', '#main-table [data-ref]', function(event) {
            if ($(event.target).closest("div").hasClass("form-check")) {
                return;
            }
            let refId=$(this).attr('data-ref');
            if(!refId){
                return Swal.fire({icon: 'warning',text: 'Data Customer tidak ditemukan.'});
            }
            let parent=formUpdate.parent();
            blockUIBody.block();
            clearModalUpdate();
            asyncData("{{ url('web/customer_service') }}/"+refId,{},"GET").then((response) => {
                if (!response.status) {
                    blockUIBody.release();
                    return Swal.fire({icon: 'error',text: response.message});
                }
                parent.removeClass('hidden');
                parent.addClass('d-lg-flex');
                filledModalUpdate(response.data, "{{ url('web/customer_service') }}/"+refId);
                blockUIBody.release();
            }).catch((error) => {
                blockUIBody.release();
                let res = (error.responseJSON) ? error.responseJSON : defaultError;
                Swal.fire({icon: 'error',text: res.message ?? defaultError});
                console.log(error ?? defaultError)
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
                text: 'Hapus riwayat order customer/pelanggan?',
                showCancelButton: true,
                confirmButtonText: "<i class='fas text-white fa-save text-primary'></i> Ya",
                cancelButtonText: "<i class='fas text-white fa-times text-danger'></i> Tidak"
            }).then((willsend) => {
                if (willsend.isConfirmed) {
                    blockUIBody.block();
                    asyncData("{{route('web.customer_service.destroy')}}",values,"POST").then((response) => {
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
        const optionFormatCity = (item) => {
            if (!item.id) return item.text;

            let html  = '<div class="d-flex flex-column">';
            html += '<span class="fs-7 fw-bold lh-1">' + item.text + '</span>';
            html += '<span class="text-muted fs-7">' + 'Provinsi: ' + (item.cat??$(item.element).attr('data-cat')) + '</span>';
            html += '</div>';

            return $(html);
        },optionFormatVilage= (item) => {
            if (!item.id) return item.text;

            let html  = '<div class="d-flex flex-column">';
            html += '<span class="fs-7 fw-bold lh-1">' + item.text + '</span>';
            html += '<span class="text-muted fs-7">' + 'Kec: ' +  (item.cat??$(item.element).attr('data-cat'))  + '</span>';
            html += '</div>';

            return $(html);
        },optionFormatMenu= (item) => {
            if (!item.id) return item.text;

            let html  = '<div class="d-flex flex-column">';
            html += '<span class="fs-7 fw-bold lh-1"><i class="fa-solid me-2 '+(item.icon??$(item.element).attr('data-icon'))+'"></i>' + item.text + '</span>';
            html += '<span class="text-muted fs-7">' + 'Kategori: ' +  (item.cat??$(item.element).attr('data-cat'))  + '</span>';
            html += '</div>';

            return $(html);
        };
        $("#delivery_date, #event_date").flatpickr({
            enableTime: true,
            dateFormat: "Y-m-d H:i",
            time_24hr: true
        });
        $("#filter_time").flatpickr({
            dateFormat: "Y-m",
            altInput: true,
            altFormat: "F Y",
            plugins: [
                new monthSelectPlugin({
                    shorthand: true,
                    dateFormat: "Y-m",
                    altFormat: "F Y"
                })
            ],
            // inline: true,
            defaultDate: new Date()
        });
        $("#event_time").flatpickr({
            enableTime: true,
            noCalendar: true,
            dateFormat: "H:i",
            time_24hr: true,
            defaultDate: new Date()
        });
        $("#kt_select2_customer").select2({
            placeholder: "Cari atau tambah produk...",
            tags: true, 
            ajax: {
                url: "{{route('web.customers.search')}}",
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
                                phone: item.phone,
                                newTag:false,
                                vilage_id:item.vilage_id??false,
                                city_id:item.city_id??false,
                                address:item.address??false
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
            templateSelection: selectionFormat,
            templateResult: optionFormat
        });
        $('#kt_select2_customer').on('select2:select', function (e) {
            const datacustomer = e.params.data;
            let t=$(this),
                parent=t.closest('form');
            if (!datacustomer.newTag) {
                parent.find('[name="phone"]').val(datacustomer.phone || '');
                if(datacustomer.city_id){
                    let cityRef=datacustomer.city_id;
                    let newOption = new Option(cityRef.name, cityRef.id, true, true);
                    $(newOption).attr('data-cat', cityRef.province_id.name);
                    parent.find('[name="city_id"]').append(newOption).trigger('change'); 
                }
                if(datacustomer.vilage_id){
                    let vilageRef=datacustomer.vilage_id;
                    let newOptionB = new Option(vilageRef.name, vilageRef.id, true, true);
                    $(newOptionB).attr('data-cat', vilageRef.district_id.name);
                    parent.find('[name="vilage_id"]').append(newOptionB).trigger('change'); 
                }
                parent.find('[name="address"]').val(datacustomer.address || '');
            } else {
                parent.find('[name="phone"]').val('');
            }
        });
        $('#kt_form_update [name="city_id"]').select2({
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
            templateSelection: optionFormatCity,
            templateResult: optionFormatCity
        });
        $('#kt_form_update [name="vilage_id"]').select2({
            placeholder: "Kelurahan/Desa:...",
            ajax: {
                url: "{{route('web.ref_wilayah.search_vilage')}}",
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return {
                        q: params.term,
                        refId: $('#kt_form_update [name="city_id"]').val()
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
            templateSelection: optionFormatVilage,
            templateResult: optionFormatVilage
        });
        $(document).on('keyup', '.item_request', function() {
            let e=$(this),
                parent=e.closest('[data-ref]'),
                parentId=parent.attr('data-ref');
            listActivemenu=listActivemenu.map(menu =>
                menu.id === parentId ? { ...menu, porsi_request: e.val() } : menu
            );
            totalHarga();
        });
        $(document).on('click', '[data-delete]', function() {
            let e=$(this),
                refId=e.attr('data-delete'),
                item=listActivemenu.find(el => el.id == refId);
            if(item){
                listActivemenu = listActivemenu.filter(item => item.id !== refId);
                e.closest('[data-ref]').remove();
                countItem();
                totalHarga();
            }
            if(!listActivemenu.length){
                contentorder.find("#item_none").removeClass('hidden');
            }
        });
        $(document).on('submit', "#kt_form_update", function(e){
            e.preventDefault(),
            form=$(this),
            submit=form.find('#kt_form_submit'),
            validation.validate().then(function (valid) {
                valid=='Valid' && 
                (submit.attr("data-kt-indicator", "on"),
                submit.attr("disabled","true"),
                blockUIBody.block(),
                setTimeout(function () {
                    if(!listActivemenu.length){
                        return Swal.fire({icon: "warning",text: 'belum melakukan order menu.'}) 
                    }else{
                        form.ajaxSubmit({
                            type: "put",
                            url: form.attr("action"),
                            dataType: "json",
                            success: function (response) {
                                if(response.status!='200'){
                                    return Swal.fire({icon: "warning",text: response.message})
                                }
                                Swal.fire({icon: "success",text: response.message});
                                submit.removeAttr("data-kt-indicator"),
                                submit.removeAttr("disabled"),
                                clearModalUpdate();
                                let parentU=formUpdate.parent();
                                parentU.addClass('hidden');
                                parentU.removeClass('d-lg-flex');
                                blockUIBody.release();
                                table.draw(false);
                            },
                            error: function (error) {
                                (error = error.responseJSON? error.responseJSON: defaultError),
                                Swal.fire({icon: "error",text: error.message? error.message: defaultError}),
                                submit.removeAttr("data-kt-indicator"),
                                submit.removeAttr("disabled"),
                                blockUIBody.release();
                            },
                        }) 
                    }
                    console.log('Proses Input')
                },5e2))
            })
        });
        $('#kt_form_update [name="menus_catering_id"]').select2({
            placeholder: "Tambah Menu: ...",
            ajax: {
                url: "{{route('web.menus_catering.select')}}",
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return {
                        q: params.term,
                        packetid: $('#packet-id').val().join(',')
                    };
                },
                processResults: function (resp) {
                    return {
                        results: resp.data.map(function(item) {
                            return {
                                id: item.id,
                                text: item.name,
                                cat: item.category_name??null,
                                icon: item.icon??null,
                                price: item.selling_price??null,
                                porsi: item.porsi_standard??null
                            }
                        })
                    };
                }
            },
            templateSelection: optionFormatMenu,
            templateResult: optionFormatMenu
        });
        $('#kt_select2_menu').on('select2:select', function (e) {
            menuSelected = e.params.data;
            menuSelected={icon:menuSelected.icon,
                        id:menuSelected.id,
                        name:menuSelected.text,
                        selling_price:menuSelected.price,
                        porsi_request:menuSelected.porsi,
                        porsi_standard:menuSelected.porsi};
        });
        $(document).on('click', '#add-menu', function() {
            if(!menuSelected){
                return Swal.fire({icon: 'info',text: 'Menu belum di pilih.'});
            }
            if (!listActivemenu.some(el => el.id == menuSelected.id)) {
                listActivemenu.push(menuSelected);
                filledOrder();
                scrollDown();
                menuSelected=null;
                $("#kt_select2_menu").val(null).trigger("change");
            }else{
                return Swal.fire({icon: 'info',text: 'Menu sudah masuk dalam daftar di pilih.'});
            }
        });
        $('[data-control="select2"][multiple]').select2({ tags: true,tokenSeparators: [',']});
        $(document).on('click', '#kt_cetak_order_internal', function() {
            let e=$(this),
                pform=e.closest('form'),
                refId=pform.find('[name="id"]').val();
            e.attr("data-kt-indicator", "on"),
            e.attr("disabled","true"),
            asyncData("{{ url('web/customer_service/export') }}/"+refId+"?internal=true",{},"GET").then((response) => {
                if (!response.status) {
                    blockUIBody.release();
                    return Swal.fire({icon: 'error',text: response.message});
                }
                e.removeAttr("data-kt-indicator"),
                e.removeAttr("disabled"),
                $("#pdfModal").find('iframe').attr('src',response.data??'');
                $("#pdfModal").modal('show');
            }).catch((error) => {
                e.removeAttr("data-kt-indicator"),
                e.removeAttr("disabled");
                let res = (error.responseJSON) ? error.responseJSON : defaultError;
                Swal.fire({icon: 'error',text: res.message ?? defaultError});
                console.log(error ?? defaultError)
            });
        });
        $(document).on('click', '#kt_cetak_rincian', function() {
            let e=$(this),
                pform=e.closest('form'),
                refId=pform.find('[name="id"]').val();
            e.attr("data-kt-indicator", "on"),
            e.attr("disabled","true"),
            asyncData("{{ url('web/customer_service/export_rincian') }}/"+refId,{},"GET").then((response) => {
                if (!response.status) {
                    blockUIBody.release();
                    return Swal.fire({icon: 'error',text: response.message});
                }
                e.removeAttr("data-kt-indicator"),
                e.removeAttr("disabled"),
                $("#pdfModal").find('iframe').attr('src',response.data??'');
                $("#pdfModal").modal('show');
            }).catch((error) => {
                e.removeAttr("data-kt-indicator"),
                e.removeAttr("disabled");
                let res = (error.responseJSON) ? error.responseJSON : defaultError;
                Swal.fire({icon: 'error',text: res.message ?? defaultError});
                console.log(error ?? defaultError)
            });
        });
        $(document).on('click', '#kt_cetak_order', function() {
            let e=$(this),
                pform=e.closest('form'),
                refId=pform.find('[name="id"]').val();
            e.attr("data-kt-indicator", "on"),
            e.attr("disabled","true"),
            asyncData("{{ url('web/customer_service/export') }}/"+refId,{},"GET").then((response) => {
                if (!response.status) {
                    blockUIBody.release();
                    return Swal.fire({icon: 'error',text: response.message});
                }
                e.removeAttr("data-kt-indicator"),
                e.removeAttr("disabled"),
                $("#pdfModal").find('iframe').attr('src',response.data??'');
                $("#pdfModal").modal('show');
            }).catch((error) => {
                e.removeAttr("data-kt-indicator"),
                e.removeAttr("disabled");
                let res = (error.responseJSON) ? error.responseJSON : defaultError;
                Swal.fire({icon: 'error',text: res.message ?? defaultError});
                console.log(error ?? defaultError)
            });
        });
        let loadMultipleSelect=function(select){
            asyncData('{{route("web.packet_menus.search-all")}}',{},"GET").then((response) => {
                let $select = $(select);
                $select.empty();
                response.data.forEach(row => {
                    $select.append(`<option value="${row.id}">${row.name}</option>`);
                });
                listPacket=response.data;
                $select.multipleSelect('refresh');
            }).catch((error) => {
                let res = (error.responseJSON) ? error.responseJSON : defaultError;
                console.log(res.message ?? defaultError)
            });
        },addTag=function(){
            let paket=$('#packet-id').multipleSelect('getSelects', 'text'),
                elPacket=formUpdate.find('[name="package_type[]"]');
            elPacket.find('option.locked-tag').remove();
            $.each(paket,function(x,y){
                let newOption = new Option(y, y, true, true);
                $(newOption).addClass('locked-tag');
                elPacket.append(newOption);
            })
            elPacket.trigger('change');
        },initRincian=function(){
            let listRincian=$("#list_rincian"),
                randID=Date.now().toString(36)+'-'+Math.random().toString(36).substr(2),
                tagRincian='<div class="row ps-2 pe-2 item-rincian mb-2" ref-id="'+randID+'">'+
                                '<div class="col-2 pe-1 ps-1">'+
                                    '<input type="text" name="rincian['+randID+'][qty]" class="form-control form-control-sm unvalidate text-end ref-qty input-fixed" placeholder="Qty">'+
                                '</div>'+
                                '<div class="col-5 pe-1 ps-1">'+
                                    '<input type="text" name="rincian['+randID+'][name]" class="form-control form-control-sm unvalidate" placeholder="Rincian">'+
                                '</div>'+
                                '<div class="col-2 pe-1 ps-1">'+
                                    '<input type="text" name="rincian['+randID+'][price]" class="form-control form-control-sm unvalidate text-end ref-price input-fixed" placeholder="Harga">'+
                                '</div>'+
                                '<div class="col-2 pe-1 ps-1">'+
                                    '<span data-ref="rincian_total" class="form-control form-control-sm text-end"></span>'+
                                '</div>'+
                            '</div>';
            listRincian.html(tagRincian);
            rincianItem=[{id:randID,qty:'',price:null,price_total:null}];
            calcRincian();
            // console.log(rincianItem);
        },calcRincian=function(){
            let listRincian=$("#list_rincian"),
                sumSub,
                allsum=0;
            $.each(rincianItem,function(x,y){
                sumSub=Number(y.qty || 1) * Number(y.price || 0);
                allsum+=sumSub;
                listRincian.find('[ref-id="'+y.id+'"] [data-ref="rincian_total"]').html(sumSub.toLocaleString("id-ID",{
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                }));
            });
            $("#estimate_order").val('Rp. '+allsum.toLocaleString("id-ID",{
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            }));
            // console.log(rincianItem)
            // trigger perhitungan
        };
        $(document).on('click', '#add-rincian', function() {
            let e =$(this),
                listRincian=$("#list_rincian"),
                randID=Date.now().toString(36)+'-'+Math.random().toString(36).substr(2),
                tag='<div class="row ps-2 pe-2 item-rincian mb-2" ref-id="'+randID+'">'+
                    '<div class="col-2 pe-1 ps-1">'+
                        '<input type="text" name="rincian['+randID+'][qty]" class="form-control form-control-sm unvalidate text-end ref-qty input-fixed" placeholder="Qty">'+
                    '</div>'+
                    '<div class="col-5 pe-1 ps-1">'+
                        '<input type="text" name="rincian['+randID+'][name]" class="form-control form-control-sm unvalidate" placeholder="Rincian">'+
                    '</div>'+
                    '<div class="col-2 pe-1 ps-1">'+
                        '<input type="text" name="rincian['+randID+'][price]" class="form-control form-control-sm unvalidate text-end ref-price input-fixed" placeholder="Harga">'+
                    '</div>'+
                    '<div class="col-2 pe-1 ps-1">'+
                        '<span data-ref="rincian_total" class="form-control form-control-sm text-end"></span>'+
                    '</div>'+
                    '<div class="col-1 pe-1 ps-2 pt-1">'+
                        '<a href="javascript:;" class="btn btn-sm btn-light-danger btn-active-danger px-2 py-1 text-center" data-delete="rincian"><i class="fa fa-trash p-0"></i></a>'+
                    '</div>'+
                '</div>';
            listRincian.append(tag);
            rincianItem.push({id:randID,qty:'',price:null,price_total:null});
            // console.log(rincianItem);
        });
        $(document).on('keyup', '.input-fixed', function() {
            let e=$(this),val = e.val();
            val = val.replace(/[^0-9,]/g, '');
            val = val.replace(/,(?=.*,)/g, '');
            if (val === ",") {
                e.val("0,");
                return;
            }
            if (val.endsWith(",")) {
                let angkaOnly = val.replace(/[^0-9]/g, "");
                angkaOnly = angkaOnly.replace(/^0+(?!$)/, "");
                angkaOnly = angkaOnly.replace(/\B(?=(\d{3})+(?!\d))/g, ".");
                e.val(angkaOnly + ",");
                return;
            }
            let parts = val.split(",");
            let angka = parts[0];
            let desimal = parts[1] ? parts[1].substring(0, 2) : "";
            angka = angka.replace(/^0+(?!$)/, "");
            angka = angka.replace(/\B(?=(\d{3})+(?!\d))/g, ".");
            let finalVal = desimal ? angka + "," + desimal : angka;
            e.val(finalVal);
            e.trigger('change');
        });
        $(document).on('click', '[data-delete="rincian"]', function() {
            let e =$(this),
                itemRincian=e.closest('.item-rincian');
            rincianItem = rincianItem.filter(x => x.id !== itemRincian.attr('ref-id'));
            itemRincian.remove();
            calcRincian();
        });
        $(document).on('change', '.ref-qty', function() {
            let e =$(this),
                itemRincian=e.closest('.item-rincian');
            const findRincian= rincianItem.find(item => item.id === itemRincian.attr('ref-id'));
            if(findRincian){
                findRincian.qty=parseFloat(
                    e.val().replace(/\./g, '').replace(',', '.')
                );
            }
            calcRincian();
        });
        $(document).on('change', '.ref-price', function() {
            let e =$(this),
                itemRincian=e.closest('.item-rincian');
            const findRincian= rincianItem.find(item => item.id === itemRincian.attr('ref-id'));
            if(findRincian){
                findRincian.price=parseFloat(
                    e.val().replace(/\./g, '').replace(',', '.')
                );
            }
            calcRincian();
        });
        $('#packet-id').multipleSelect({
            selectAll: false,
            animate: 'slide',
            minimumCountSelected: 2,
            formatCountSelected: function (numSelected, numTotal) {
                return numSelected + ' Paket dipilih';
            },
        }) .on('change', function() {
            addTag();
        });
        loadMultipleSelect("#packet-id");
        initRincian();
    });
</script>