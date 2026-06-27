<script>
    let table,
        tableBatch,
        form,submit,
        defaultError= "Proses tidak berhasil.";
    $(function() {
        'use strict';
        $.fn.dataTable.ext.errMode = 'none' ?? 'throw';
        table = $('#main-table').DataTable({
            processing: true,
            serverSide: true,
            orderable: false,
            ajax: {
                url: "{{ route('web.purchasing.all-paginate') }}?device=web",
                method: 'GET',
                data: function(d) {
                    d.search = $('#search').val();
                    d.status = $('#order_status').val();
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
                    data: 'order_ticket',
                    searchable: false,
                    orderable: false,
                    render: function(data, type, row, meta) {
                        return '<span class="badge py-2 px-3 badge-light-primary fs-7"><i class="fa-solid fa-barcode me-2"></i> '+(data??='-')+'</span>'+
                        '<span class="text-muted fw-semibold text-muted d-block fs-8 py-1 px-3">'+row.created_at+'</span>';
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
                            '<span class="text-muted fw-semibold text-muted d-block fs-8">Nomor : '+row.phone+'</span>'+
                        '</div></div>';
                    }
                }, {
                    data: 'estimate_price',
                    orderable: false,
                    render: function(data, type, row, meta) {
                        return '<span class="text-dark fw-bold text-hover-primary d-block mb-1 fs-6"><i>(~)</i> Rp. '+data.toLocaleString("id-ID")+'</span>'+
                        '<span class="text-muted fw-semibold text-muted d-block fs-8">Total: '+row.total_items+' menu</span>';
                    }
                }, {
                    data: 'status',
                    orderable: false,
                    render: function(data, type, row, meta) {
                        return '<span class="badge py-2 px-3 badge-light-primary fs-6"> '+(data??='-')+'</span>'+
                        '<span class="text-muted fw-semibold text-muted d-block fs-8">Tamu: '+(row.total_guest??'-')+'</span>';
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
        tableBatch = $('#batch-table').DataTable({
            processing: true,
            serverSide: true,
            orderable: false,
            ajax: {
                url: "{{ route('web.purchasing.all-batch') }}?device=web",
                method: 'GET',
                data: function(d) {
                    d.batch_order = $('#batch_order').val();
                    d.batch_range = $('#batch_range').val();
                }
            },
            columns: [{
                    data: 'order_ticket',
                    searchable: false,
                    orderable: false,
                    render: function(data, type, row, meta) {
                        return '<span class="badge py-2 px-3 badge-light-primary fs-7"><i class="fa-solid fa-barcode me-2"></i> '+(data??='-')+'</span>'+
                        '<span class="text-muted fw-semibold text-muted d-block fs-8 py-1 px-3">'+row.created_at+'</span>';
                    },
                    class:'mw-100px',
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
                    },
                    class:'min-w-200px',
                }, {
                    data: 'estimate_price',
                    orderable: false,
                    render: function(data, type, row, meta) {
                        return '<span class="text-dark fw-bold text-hover-primary d-block mb-1 fs-6">Total: '+row.total_items+' menu</span>';
                    },
                    class:'min-w-100px mw-150px',
                }, {
                    data: 'delivery_date',
                    orderable: false,
                    render: function(data, type, row, meta) {
                        return '<span class="text-dark fw-bold text-hover-primary d-block mb-1 fs-8">'+(data??='-')+'<i class="fa-solid fa-truck text-primary ms-2"></i><span>'+
                        '<span class="text-muted fw-semibold text-muted d-block fs-8">'+(row.event_date??'-')+'<i class="fa-solid fa-calendar-day text-success ms-2"></i></span>';
                    },
                    class:"text-end min-w-100px mw-150px"
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
        $(document).on('change', '.search-batch', function() {
            tableBatch.draw();
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
        blockUIPuchase = new KTBlockUI($("#modal-purchasing .modal-content")[0], {
            message: '<div class="blockui-message"><span class="spinner-border text-primary"></span> Loading...</div>',
        }),
        modalPurchase=$("#modal-purchasing"),
        modalBatch=$("#modal-batch"),
        formPuchase=$("#kt_purchase_main"),
        formBatch=$("#kt_batch_main"),
        fieldValidation=function(form){
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
        validationPurchase=FormValidation.formValidation(formPuchase[0],fieldValidation(formPuchase)),
        validationBatch=FormValidation.formValidation(formBatch[0],fieldValidation(formBatch)),
        formatIndonesia=function(numberString){
            let num = parseFloat(numberString);
            return num
                .toLocaleString("id-ID", {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                });
        },
        ribuan=function(num){
            return Math.ceil(num).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
        },ribuanDecimal=function(num){
            return num.toLocaleString('id-ID', {
                minimumFractionDigits: 0,
                maximumFractionDigits: 2
            });
        },toDecimal=function(str){
            str=str.replace(/[^0-9,.]/g, '');
            str=str.replace(/\./g, "");
            const clean = str.replace(/\,/g, ".");
            return parseFloat(clean) || 0;
        },
        orderCalcQty=function(qty,standard,request){
            // qty=toDecimal(qty);
            // standard=toNumber(standard);
            // request=toNumber(request);
            return (qty/standard)*request;
        },orderCalcTotal=function(unit,default_price,qty){
            unit=unit;
            default_price=default_price;
            // console.log(default_price+'|'+unit+'|'+qty+'='+(default_price/unit)*qty);
            return (default_price/unit)*qty;
        },toNumber=function(str) {
            str=str.replace(/[^0-9.]/g, '');
            const clean = str.replace(/\./g, "");
            // .replace(/[^0-9.]/g, '')
            return parseFloat(clean) || 0;
        },
        parseQuantity=function(str){
            if (typeof str === "number") return str;
            str = (str || "").trim();
            if (str === "") return 0;
            if (str.includes(".") && str.includes(",")) {
                str = str.replace(/\./g, "").replace(",", ".");
            } else if (str.includes(",")) {
                str = str.replace(",", ".");
            } else {
                const parts = str.split(".");
                if (parts.length > 1 && parts[1].length === 3) {
                    str = parts.join(""); 
                }
            }
            const num = parseFloat(str);
            return isNaN(num) ? 0 : num;
        },
        clearItemDescInput=function(){
            let parent=$('[data-replace="item_purchase"]');
            parent.find('#item_purchase_list table>tbody').empty();
            parent.find('#item_purchase_none').removeClass('hidden');
            parent.find('#item_purchase_list').addClass('hidden');
        },
        filledItemDescSupplier=function(purchaseitems){
            if(!purchaseitems) return;
            let supplierlist=purchaseitems.item.filter(x => x.supplier_id);
            $.each(supplierlist,function(x,y){
                let supplierText = [
                    y.suppliers?.name,
                    y.suppliers?.phone
                ].filter(Boolean).join(' | ');
                let newOption = new Option(supplierText, y.supplier_id, true, true);
                modalPurchase.find('[name="ingredients['+y.ingredient_id+'][supplier_id]"]').append(newOption).trigger('change');
            });
        },
        filledItemDescInput=function(items){
            // console.log(items);
            const allIngredients = items.flatMap(menu => (menu.ingredients || []).map(ing => ({
                    ingredient_id: ing.ingredient_id,
                    ingredient_name: ing.ingredient?.name || "",
                    unit: ing.ingredient?.unit || "",
                    quantity: orderCalcQty(ing.quantity,menu.porsi_standard,menu.porsi_request),
                    ingredient_name: ing.ingredient?.name || "",
                    unit: ing.ingredient?.unit || "",
                    default_price: ing.ingredient?.default_price || "",
                    main_supplier: ing.ingredient?.main_supplier || null,
                }))
            );
            // console.log(allIngredients);
            const mergedIngredients = Object.values(
                allIngredients.reduce((acc, item) => {
                    const key = item.ingredient_id || item.ingredient?.name;
                    const qty = parseQuantity(item.quantity);
                    if (!acc[key]) {
                        acc[key] = {
                            ingredient_id: item.ingredient_id,
                            ingredient_name: item.ingredient_name,
                            unit: item.unit,
                            default_price: item.default_price,
                            quantity: qty,
                            main_supplier: item.main_supplier
                        };
                    } else {
                        acc[key].quantity += qty;
                    }
                    return acc;
                }, {})
            ).filter(item => {
                return item.ingredient_id && item.ingredient_id.trim() !== "";
            });
            let parent=$('[data-replace="item_purchase"]'),tagInput,tagSelect;
            // console.log(mergedIngredients);
            let tot=0,nrow=1;
            $.each(mergedIngredients,function(x,y){
                tot=orderCalcTotal(y.unit,y.default_price,y.quantity);
                tagSelect="";
                if(y.main_supplier){
                    tagSelect='<option value="'+y.main_supplier.id+'">'+y.main_supplier.name+' | '+y.main_supplier.phone+'</option>';
                    // console.log(tagSelect);
                }
                tagInput='<tr>'+
                    '<td>'+nrow+'.</td>'+
                    '<td>'+
                        '<input type="hidden" name="ingredients['+y.ingredient_id+'][quantity]" value="'+y.quantity+'">'+
                        '<input type="hidden" name="ingredients['+y.ingredient_id+'][price]" value="'+tot+'">'+
                        y.ingredient_name+
                    '</td>'+
                    '<td class="text-end">'+ribuan(y.quantity)+'</td>'+
                    '<td class="text-end">'+ribuan(y.unit)+'</td>'+
                    '<td class="text-end">'+ribuan(y.default_price)+'</td>'+
                    '<td class="text-end">'+formatIndonesia(tot)+'</td>'+
                    '<td class="text-end py-0 pe-0 w-250px">'+
                        '<select class="form-select form-select-solid form-select-sm fs-7 ps-2 select2-auto" '+ 
                            'data-placeholder="Pilih Supplier atau Supplier Baru : ..." '+
                            'name="ingredients['+y.ingredient_id+'][supplier_id]" '+
                            'data-refsup="'+y.ingredient_id+'"'+
                            'placeholder="Pilih Supplier atau Supplier Baru : ...">'+tagSelect+
                        '</select>'+
                        // '<select class="form-select form-select-solid form-select-sm rounded-0" data-select="supplier" name="ingredients['+y.ingredient_id+'][supplier_id]" placeholder="Pilih atau cari Supplier "></select>'+
                    '</td>'+
                '</tr>';
                parent.find('#item_purchase_list table>tbody').append(tagInput);
                nrow++;
            });
            if(mergedIngredients.length){
                parent.find('#item_purchase_none').addClass('hidden');
                parent.find('#item_purchase_list').removeClass('hidden');
                $('#content_purchasing [data-purchase="count"]').html('['+mergedIngredients.length+' Bahan Baku]');
            }else{
                parent.find('#item_purchase_none').removeClass('hidden');
                parent.find('#item_purchase_list').addClass('hidden');
                $('#content_purchasing [data-purchase="count"]').html('');
            }
        },
        filledItemDesc=function(items){
            let parent=$('[data-replace="item_desc"]'),
                tagItem,tagTr,orderQty,total,totalCost,netCost,subTotal,grandTotal=0,estimated_margin;
            // console.log(items)
            $.each(items,function(x,y){
                tagTr="",orderQty=0,total=0,subTotal=0,totalCost=0;
                $.each(y.ingredients,function(xingred,yingred){
                    if(!yingred.ingredient){
                        tagTr+='<tr class="bg-light-success">'+
                            '<td colspan="8">'+yingred.ingredient_label+'</td>'+
                        '</tr>';
                    }else{
                        orderQty=orderCalcQty(yingred.quantity,y.porsi_standard,y.porsi_request);
                        total=orderCalcTotal(yingred.ingredient.unit,yingred.ingredient.default_price,orderQty);
                        // console.log(yingred)
                        totalCost+=total;
                        tagTr+='<tr>'+
                            '<td>'+ribuan(orderQty)+'</td>'+
                            '<td>'+yingred.ingredient.name+'</td>'+
                            '<td>'+yingred.ingredient.unit+'</td>'+
                            '<td class="text-end">'+ribuan(yingred.ingredient.default_price)+'</td>'+
                            '<td class="text-end">'+formatIndonesia(total)+'</td>'+
                        '</tr>';
                    }
                })
                netCost=(10/100)*totalCost;
                subTotal=totalCost+netCost;
                grandTotal+=subTotal;
                tagItem='<div class="d-flex align-items-center justify-content-between border rounded mb-2 border-gray-300">'+
                    '<table class="table align-middle fs-7 gy-3 gs-3 table-hover table-sm table-row-bordered m-0">'+
                        '<thead>'+
                            '<tr class="fw-bold bg-success text-white">'+
                                '<th colspan="3"><i class="fa-solid '+y.icon+' text-white me-2"></i>'+y.name+'</th>'+
                                '<th class="text-start">Porsi Pesanan</th>'+
                                '<th class="text-end">'+ribuan(y.porsi_request)+'</th>'+
                            '</tr>'+
                            '<tr class="fw-bold text-muted bg-light">'+
                                '<th>Order</th>'+
                                '<th>Bahan</th>'+
                                '<th>Unit</th>'+
                                '<th class="text-end">Harga</th>'+
                                '<th class="text-end">Total</th>'+
                            '</tr>'+
                        '</thead>'+
                        '<tbody>'+
                            tagTr+
                        '</tbody>'+
                        '<tfoot>'+
                            '<tr class="fw-bold bg-light">'+
                                '<td colspan="3">Total cost</td>'+
                                '<td colspan="2" class="text-end">Rp. '+formatIndonesia(totalCost)+'</td>'+
                            '</tr>'+
                        '</tfoot>'+
                    '</table>'+
                '</div>';
                parent.append(tagItem)
            });
            if(items.length){
                modalPurchase.find('[data-replace="estimated_cost"]').val(ribuan(grandTotal??0));
                estimated_margin=modalPurchase.find('input[name="estimated_margin"]').val();
                estimated_margin=toNumber(estimated_margin??'');
                modalPurchase.find('[data-replace="estimated_selling_price"]').val(ribuan(grandTotal+estimated_margin));
                parent.find('#item_desc_none').addClass('hidden')
            }else{
                // modalPurchase.find('[data-replace="calculate_price"]').val('');
                parent.find('#item_desc_none').removeClass('hidden')
            }
        },
        filledItem=function(items){
            let parent=$('[data-replace="item"]'),
                tagItem;
            $.each(items,function(x,y){
                tagItem='<div class="d-flex align-items-center justify-content-between border rounded mb-2 border-gray-300">'+
                            '<div class="d-flex align-items-center p-2">'+
                                '<span class="symbol-label fw-bold px-2 py-1 text-center rounded"><i class="fa-solid '+y.icon+'"></i></span>'+
                                '<a href="javascript:;" class="fs-8 text-dark text-hover-primary fw-semibold">'+
                                y.name+
                                '</a>'+
                            '</div>'+
                            '<div class="d-flex align-items-center p-2">'+
                                '<span class="badge py-3 px-4 fs-7 badge-light-success me-3">Porsi Pesanan: '+(y.porsi_request??0)+'</span>'+
                            '</div>'+
                        '</div>'+
                        '<div class="d-flex align-items-center justify-content-between rounded mb-2">'+
                            '<input type="text" class="form-control text-gray-800 fw-bold form-control-sm mh-150px minh-100px"  placeholder="Catatan Order Menu..." value="'+(y.notes??0)+'">'+
                        '</div>';
                parent.append(tagItem)
            });
            if(items.length){
                parent.find('#item_none').addClass('hidden')
            }else{
                parent.find('#item_none').removeClass('hidden')
            }
        },
        clearItem=function(){
            let parent=$('[data-replace="item"]');
            parent.find('div:not(#item_none)').remove();
            parent.find('#item_none').removeClass('hidden')
        },
        clearItemDesc=function(){
            let parent=$('[data-replace="item_desc"]');
            parent.find('div:not(#item_desc_none)').remove();
            parent.find('#item_desc_none').removeClass('hidden')
        },
        clearModalUpdate=function(){
            modalPurchase.find('[data-replace="title_modal"]').html('');
            modalPurchase.find('[name="id"]').val('');
            modalPurchase.find('[data-replace="order_ticket"]').html('');
            modalPurchase.find('[data-replace="customer_name"]').html('');
            modalPurchase.find('[data-replace="customer_phone"]').html('');
            modalPurchase.find('[data-replace="customer_province"]').html('');
            modalPurchase.find('[data-replace="customer_city"]').html('');
            modalPurchase.find('[data-replace="customer_district"]').html('');
            modalPurchase.find('[data-replace="customer_vilage"]').html('');
            modalPurchase.find('[data-replace="customer_address"]').html('');
            modalPurchase.find('[data-replace="venue"]').html('');
            modalPurchase.find('[data-replace="package_type"]').html('');
            modalPurchase.find('[data-replace="event_type"]').html('');
            modalPurchase.find('[data-replace="total_guest"]').html('');
            modalPurchase.find('[data-replace="delivery_date"]').html('');
            modalPurchase.find('[data-replace="event_date"]').html('');
            modalPurchase.find('[data-replace="status"]').html('');
            modalPurchase.find('#kt_purchase_submit').removeClass('hidden');
            modalPurchase.find('#kt_purchase_udpate').addClass('hidden');
            modalPurchase.find('input[name="estimated_margin"]').val('');
            modalPurchase.find('#kt_purchase_submit').removeClass('hidden');
            modalPurchase.find('#kt_purchase_udpate').addClass('hidden');
            clearItem();
            clearItemDesc();
            clearItemDescInput()
        },
        initPlugin=function(){
            let modalPurchasing=$("#modal-purchasing");
            // .select2('destroy')
            $('.select2-auto').each(function(){
                let $this=$(this);
                $this.select2({
                    dropdownParent: modalPurchasing,
                    tags: true, 
                    allowClear: true,
                    ajax: {
                        url: "{{ route('web.suppliers.search') }}?refid="+$this.attr('data-refsup'),
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
                                        text: item.name +' | '+ item.phone,
                                        newTag: false
                                    };
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
                });
            });
        },
        filledModalUpdate=function(response,url){
            modalPurchase.find('[data-replace="title_modal"]').html((response.order_ticket??'')+' => '+response.customer.name);
            modalPurchase.find('[name="id"]').val(response.id);
            modalPurchase.find('[data-replace="order_ticket"]').html(response.order_ticket??'');
            modalPurchase.find('[data-replace="customer_name"]').html(response.customer.name??'');
            modalPurchase.find('[data-replace="customer_phone"]').html(response.customer.phone??'');
            modalPurchase.find('[data-replace="customer_province"]').html(response.customer.city_id.province_id.name??'');
            modalPurchase.find('[data-replace="customer_city"]').html(response.customer.city_id.name??'');
            modalPurchase.find('[data-replace="customer_district"]').html(response.customer.vilage_id.district_id.name??'');
            modalPurchase.find('[data-replace="customer_vilage"]').html(response.customer.vilage_id.name??'');
            modalPurchase.find('[data-replace="customer_address"]').html(response.customer.address??'');
            modalPurchase.find('[data-replace="venue"]').html(response.venue??'');
            let packgeType=response.package_type.join(', ')
            let eventType=response.event_type.join(', ')
            modalPurchase.find('[data-replace="package_type"]').html(response.package_type?packgeType:'');
            modalPurchase.find('[data-replace="event_type"]').html(response.event_type?eventType:'');
            modalPurchase.find('[data-replace="total_guest"]').html(response.total_guest??'');
            modalPurchase.find('[data-replace="delivery_date"]').html(response.delivery_date??'');
            modalPurchase.find('[data-replace="event_date"]').html(response.event_date??'');
            modalPurchase.find('[name="purchase_date"]').val(response.purchases?.purchase_date ?? '');
            modalPurchase.find('#kt_purchase_udpate').attr('data-refid',response.purchases?.id ?? '');
            
            if(response.cost_estimation){
                modalPurchase.find('input[name="estimated_margin"]').val(response.cost_estimation.estimated_margin);
            }
            modalPurchase.find('[data-replace="status"]').html(response.status??'');
            if(response.status!='approved'){
                modalPurchase.find('#kt_purchase_submit').addClass('hidden');
                modalPurchase.find('#kt_purchase_udpate').removeClass('hidden');
                modalPurchase.find('#kt_cetak_purchasing').removeClass('hidden');
            }else{
                modalPurchase.find('#kt_purchase_submit').removeClass('hidden');
                modalPurchase.find('#kt_purchase_udpate').addClass('hidden');
                modalPurchase.find('#kt_cetak_purchasing').addClass('hidden');
            }
            filledItem(response.items)
            filledItemDesc(response.items)
            filledItemDescInput(response.items)
            filledItemDescSupplier(response.purchases)
            initPlugin()
        };
        $(document).on('click', '#main-table [data-ref]', function(event) {
            if ($(event.target).closest("div").hasClass("form-check")) {
                return;
            }
            let refId=$(this).attr('data-ref');
            if(!refId){
                return Swal.fire({icon: 'warning',text: 'Data Order tidak ditemukan.'});
            }
            blockUIBody.block();
            clearModalUpdate();
            asyncData("{{ url('web/purchasing') }}/"+refId,{},"GET").then((response) => {
                if (!response.status) {
                    blockUIBody.release();
                    return Swal.fire({icon: 'error',text: response.message});
                }
                filledModalUpdate(response.data, "{{ url('web/customer_service') }}/"+refId);
                modalPurchase.modal('show');
                blockUIBody.release();
            }).catch((error) => {
                blockUIBody.release();
                let res = (error.responseJSON) ? error.responseJSON : defaultError;
                Swal.fire({icon: 'error',text: res.message ?? defaultError});
                console.log(error ?? defaultError)
            });
        });
        $("#batch_range").flatpickr({
            dropdownParent: $("#modal-batch"),
            mode: "range",
            locale: "id",
            dateFormat: "Y-m-d",
            altInput: true,
            altFormat: "d F Y",
            position: "auto right",
            onChange: function(selectedDates, dateStr, instance) {
                if (selectedDates.length === 1) {
                    let startDate = selectedDates[0];
                    let maxDate = new Date(startDate);
                    maxDate.setDate(maxDate.getDate() + 7);
                    instance.set('maxDate', maxDate);
                }
                if (selectedDates.length === 2) {
                    instance.set('maxDate', null);
                }
            }
        });
        $("#purchase_date").flatpickr({
            dateFormat: "Y-m-d",
            altInput: true,
            position: "auto right",
            defaultDate: new Date()
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
        $(document).on('submit', "#kt_batch_main", function(e){
            e.preventDefault(),
            form=$(this),
            submit=form.find('#kt_batch_submit'),
            validationBatch.validate().then(function (valid) {
                valid=='Valid' && 
                (submit.attr("data-kt-indicator", "on"),
                submit.attr("disabled","true"),
                blockUIPuchase.block(),
                setTimeout(function () {
                    form.ajaxSubmit({
                        type: "post",
                        url: form.attr("action"),
                        dataType: "json",
                        success: function (response) {
                            if(response.status!='200'){
                                return Swal.fire({icon: "warning",text: response.message})
                            }
                            if(response.data){
                                $("#pdfModal").find('iframe').attr('src',response.data??'');
                                $("#pdfModal").modal('show');
                            }else{
                                Swal.fire({icon: "success",text: response.message});
                            }
                            submit.removeAttr("data-kt-indicator"),
                            submit.removeAttr("disabled"),
                            blockUIPuchase.release();
                        },
                        error: function (error) {
                            (error = error.responseJSON? error.responseJSON: defaultError),
                            Swal.fire({icon: "error",text: error.message? error.message: defaultError}),
                            submit.removeAttr("data-kt-indicator"),
                            submit.removeAttr("disabled"),
                            blockUIPuchase.release();
                        },
                    }) 
                },5e2))
            })
        });
        $(document).on('submit', "#kt_purchase_main", function(e){
            e.preventDefault(),
            form=$(this),
            submit=form.find('#kt_purchase_submit'),
            validationPurchase.validate().then(function (valid) {
                valid=='Valid' && 
                (submit.attr("data-kt-indicator", "on"),
                submit.attr("disabled","true"),
                blockUIPuchase.block(),
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
                            clearModalUpdate();
                            table.draw(false);
                            modalPurchase.modal('hide');
                            blockUIPuchase.release();
                        },
                        error: function (error) {
                            (error = error.responseJSON? error.responseJSON: defaultError),
                            Swal.fire({icon: "error",text: error.message? error.message: defaultError}),
                            submit.removeAttr("data-kt-indicator"),
                            submit.removeAttr("disabled"),
                            blockUIPuchase.release();
                        },
                    }) 
                },5e2))
            })
        });
        $(document).on('click', "#kt_purchase_udpate", function(){
            let e=$(this);
            form=e.closest('form'),
            submit=form.find('#kt_purchase_submit'),
            validationPurchase.validate().then(function (valid) {
                valid=='Valid' && 
                (submit.attr("data-kt-indicator", "on"),
                submit.attr("disabled","true"),
                blockUIPuchase.block(),
                setTimeout(function () {
                    form.ajaxSubmit({
                        type: "post",
                        url: '{{url("web/purchasing")}}/'+e.attr('data-refid'),
                        dataType: "json",
                        success: function (response) {
                            if(response.status!='200'){
                                return Swal.fire({icon: "warning",text: response.message})
                            }
                            Swal.fire({icon: "success",text: response.message});
                            submit.removeAttr("data-kt-indicator"),
                            submit.removeAttr("disabled"),
                            form.resetForm();
                            clearModalUpdate();
                            table.draw(false);
                            modalPurchase.modal('hide');
                            blockUIPuchase.release();
                        },
                        error: function (error) {
                            (error = error.responseJSON? error.responseJSON: defaultError),
                            Swal.fire({icon: "error",text: error.message? error.message: defaultError}),
                            submit.removeAttr("data-kt-indicator"),
                            submit.removeAttr("disabled"),
                            blockUIPuchase.release();
                        },
                    }) 
                },5e2))
            })
        });
        $(document).on('click', '#kt_cetak_purchasing', function() {
            let e=$(this),
                pform=e.closest('form'),
                refId=pform.find('[name="id"]').val();
            e.attr("data-kt-indicator", "on"),
            e.attr("disabled","true"),
            asyncData("{{ url('web/purchasing/export') }}/"+refId,{},"GET").then((response) => {
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
        $(document).on('click', '#kt_batch_purchasing', function() {
            modalBatch.modal('show');
        });
    });
</script>