<script>
    let table,
        listCostStructure=[],
        structuredetail=[],
        totalKeseluruhan,
        form,submit,dataDetail,grandTotal,totalgrandTotal,
        defaultError= "Proses tidak berhasil.";
    $(function() {
        'use strict';
        $.fn.dataTable.ext.errMode = 'none' ?? 'throw';
        table = $('#main-table').DataTable({
            processing: true,
            serverSide: true,
            orderable: false,
            ajax: {
                url: "{{ route('web.cost_controling.all-paginate') }}?device=web",
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
                        return '<span class="text-dark fw-bold text-hover-primary d-block mb-1 fs-6"><i>(~)</i> '+currency(data)+'</span>'+
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
        modalCC=$("#modal-cost-controlling"),
        formCost=$("#kt_cost_main"),
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
        validationCost=FormValidation.formValidation(formCost[0],fieldValidation(formCost));
        let blockUICost = new KTBlockUI($("#modal-cost-controlling .modal-content")[0], {
            message: '<div class="blockui-message"><span class="spinner-border text-primary"></span> Loading...</div>',
        });
        let clearItem=function(){
            let parent=$('[data-replace="item"]');
            parent.find('div:not(#item_none)').remove();
            parent.find('#item_none').removeClass('hidden')
            parent.find('table.detail-orders').remove()
        },filledItem=function(items){
            let parent=$('[data-replace="item"]'),
                tagItem;
            // console.log(items);
            $.each(items,function(x,y){
                // '<span class="fs-8 text-dark text-hover-primary fw-semibold me-2">Rp. '+formatIndonesia(y.selling_price??0)+' | </span>'+
                tagItem='<div class="d-flex align-items-center justify-content-between border rounded mb-2 border-gray-300">'+
                            '<div class="d-flex align-items-center p-2">'+
                                '<span class="symbol-label fw-bold px-2 py-1 text-center rounded"><i class="fa-solid '+y.icon+'"></i></span>'+
                                '<a href="javascript:;" class="fs-8 text-dark text-hover-primary fw-semibold">'+
                                y.name+
                                '</a>'+
                            '</div>'+
                            '<div class="d-flex align-items-center p-2">'+
                                'Porsi <input type="text" name="porsi['+y.id+']" data-refmenu="'+y.id+'" class="form-control form-control-sm rounded-0 unvalidate w-100px mx-2 text-end input-fixed" value="'+(y.porsi_request??0)+'">'+
                                '<button type="button" class="btn btn-sm btn-icon btn-light btn-active-light-primary toggle h-25px w-25px" data-toggle="'+y.id+'">'+
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
                                '</button>'+
                            '</div>'+
                        '</div>'+
                        '<div class="d-flex align-items-center justify-content-between rounded mb-2">'+
                        '<input type="text" class="form-control text-gray-800 fw-bold form-control-sm mh-150px minh-100px" name="notes['+y.id+']" placeholder="Catatan Menu :..." value="'+(y.notes??'')+'">'+
                        '</div>'+
                        '<div class="hidden" data-desc="'+y.id+'"></div>';
                parent.append(tagItem)
            });
            //  '<tr>'+
            //     '<td>Ppn (10%)</td>'+
            //     '<td>:</td>'+
            //     '<td class="text-end w-100px"><span data-replace="estimated_nc_label"></span></td>'+
            // '<tr>'+
            tagItem='<div class="separator separator-dashed mb-2"></div>'+
            '<table class="table align-middle fs-7 fw-bold gy-1 gs-3 table-sm table-row-bordered m-0 bg-success detail-orders">'+
                '<tr>'+
                    '<td>Total HPP</td>'+
                    '<td>:</td>'+
                    '<td class="text-end w-100px"><span data-replace="estimated_tc_label"></span></td>'+
                '<tr>'+
                '<tr>'+
                    '<td>Total Cost Structure</td>'+
                    '<td>:</td>'+
                    '<td class="text-end w-100px"><span data-replace="estimated_cc_label"></span></td>'+
                '<tr>'+
                '<tr>'+
                    '<td>Total Actual FC</td>'+
                    '<td>:</td>'+
                    '<td class="text-end w-100px"><span data-replace="estimated_cost_label"></span></td>'+
                '<tr>'+
                '<tr>'+
                    '<td>Profit</td>'+
                    '<td>:</td>'+
                    '<td class="text-end w-100px"><span data-replace="estimated_profit"></span></td>'+
                '<tr>'+
            '</table>';
            parent.append(tagItem)
            if(items.length){
                parent.find('#item_none').addClass('hidden')
            }else{
                parent.find('#item_none').removeClass('hidden')
            }
        },ribuan=function(num){
            return Math.round(num).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
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
        },toDecimal=function(str){
            str=str.replace(/[^0-9,.]/g, '');
            str=str.replace(/\./g, "");
            const clean = str.replace(/\,/g, ".");
            return parseFloat(clean) || 0;
        },toNumber=function(str) {
            str=str.replace(/[^0-9.]/g, '');
            const clean = str.replace(/\./g, "");
            return parseFloat(clean) || 0;
        },orderCalcQty=function(qty,standard,request){
            qty=toDecimal(qty);
            standard=toNumber(standard);
            request=toNumber(request);
            return (qty/standard)*request;
        },orderCalcTotal=function(unit,default_price,qty){
            unit=toNumber(unit);
            default_price=toNumber(default_price);
            return (default_price/unit)*qty;
        },filledItemDesc=function(items){
            let parent=$('[data-replace="item"]'),
                tagItem,tagTr,orderQty,total,totalCost,netCost,subTotal,grandTC=0,grandNc=0,estimated_margin;
                grandTotal=0,
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
                            '<td>'+yingred.quantity+'</td>'+
                            '<td>'+ribuanDecimal(orderQty)+'</td>'+
                            '<td>'+yingred.ingredient.satuan+'</td>'+
                            '<td colspan="2">'+yingred.ingredient.name+'</td>'+
                            '<td>'+yingred.ingredient.unit+'</td>'+
                            '<td class="text-end">'+yingred.ingredient.default_price+'</td>'+
                            '<td class="text-end">'+formatIndonesia(total)+'</td>'+
                        '</tr>';
                    }
                })
                // netCost=(10/100)*totalCost;
                // subTotal=totalCost+netCost;
                subTotal=totalCost;
                grandTotal+=subTotal;
                grandTC+=totalCost;
                // grandNc+=netCost;
                tagItem='<div class="d-flex align-items-center justify-content-between border rounded mb-2 border-gray-300">'+
                    '<table class="table align-middle fs-8 gy-3 gs-3 table-sm table-row-bordered m-0">'+
                        '<thead>'+
                            '<tr class="fw-bold bg-success text-white">'+
                                '<th colspan="1">Porsi Standard</th>'+
                                '<th>'+y.porsi_standard+'</th>'+
                                '<th>Harga:</th>'+
                                '<th class="mw-100px">Rp. '+formatIndonesia(totalCost)+'</th>'+
                                '<th colspan="3">Porsi Pesanan</th>'+
                                '<th class="mw-100px text-end">'+y.porsi_request+'</th>'+
                            '</tr>'+
                            '<tr class="fw-bold text-muted bg-light">'+
                                '<th>Standard</th>'+
                                '<th>Order</th>'+
                                '<th>Satuan</th>'+
                                '<th colspan="2">Bahan</th>'+
                                '<th>Unit</th>'+
                                '<th class="text-end">Harga</th>'+
                                '<th class="text-end mw-75px">Total</th>'+
                            '</tr>'+
                        '</thead>'+
                        '<tbody>'+
                            tagTr+
                        '</tbody>'+
                    '</table>'+
                '</div>';
                parent.find('[data-desc="'+y.id+'"]').html(tagItem);
            });
            if(items.length){
                modalCC.find('[data-replace="estimated_cost"]').val(formatIndonesia(grandTotal??0));
                modalCC.find('[data-replace="estimated_cost_label"]').html(currency(grandTotal??0));
                modalCC.find('[data-replace="estimated_tc_label"]').html(currency(parseFloat(grandTC.toFixed(2))));
                // modalCC.find('[data-replace="estimated_nc_label"]').html(currency(parseFloat(grandNc.toFixed(2))));
                estimated_margin=modalCC.find('input[name="estimated_margin"]').val();
                estimated_margin=toNumber(estimated_margin??'');
                modalCC.find('[data-replace="estimated_selling_price"]').val(formatIndonesia(grandTotal+estimated_margin));
            }
        },clearModalUpdate=function(){
            modalCC.find('[data-replace="title_modal"]').html('');
            modalCC.find('[name="id"]').val('');
            modalCC.find('[data-replace="order_ticket"]').html('');
            modalCC.find('[data-replace="customer_name"]').html('');
            modalCC.find('[data-replace="customer_phone"]').html('');
            modalCC.find('[data-replace="customer_province"]').html('');
            modalCC.find('[data-replace="customer_city"]').html('');
            modalCC.find('[data-replace="customer_district"]').html('');
            modalCC.find('[data-replace="customer_vilage"]').html('');
            modalCC.find('[data-replace="customer_address"]').html('');
            modalCC.find('[data-replace="venue"]').html('');
            modalCC.find('[data-replace="package_type"]').html('');
            modalCC.find('[data-replace="event_type"]').html('');
            modalCC.find('[data-replace="total_guest"]').html('');
            modalCC.find('[data-replace="total_invite"]').html('');
            modalCC.find('[data-replace="delivery_date"]').html('');
            modalCC.find('[data-replace="event_date"]').html('');
            modalCC.find('[data-replace="status"]').html('');
            modalCC.find('#kt_cost_submit').removeClass('hidden');
            modalCC.find('#kt_cost_udpate').addClass('hidden');
            clearItem()
            clearCostStructure(true)
        },filledModalUpdate=function(response,url){
            modalCC.find('[data-replace="title_modal"]').html((response.order_ticket??'')+' => '+response.customer.name);
            modalCC.find('[name="id"]').val(response.id);
            modalCC.find('[data-replace="order_ticket"]').html(response.order_ticket??'');
            modalCC.find('[data-replace="customer_name"]').html(response.customer.name??'');
            modalCC.find('[data-replace="customer_phone"]').html(response.customer.phone??'');
            modalCC.find('[data-replace="customer_province"]').html(response.customer.city_id.province_id.name??'');
            modalCC.find('[data-replace="customer_city"]').html(response.customer.city_id.name??'');
            modalCC.find('[data-replace="customer_district"]').html(response.customer.vilage_id.district_id.name??'');
            modalCC.find('[data-replace="customer_vilage"]').html(response.customer.vilage_id.name??'');
            modalCC.find('[data-replace="customer_address"]').html(response.customer.address??'');
            modalCC.find('[data-replace="venue"]').html(response.venue??'');
            let packgeType=response.package_type.join(', ')
            let eventType=response.event_type.join(', ')
            modalCC.find('[data-replace="package_type"]').html(response.package_type?packgeType:'');
            modalCC.find('[data-replace="event_type"]').html(response.event_type?eventType:'');
            modalCC.find('[data-replace="total_guest"]').html((response.total_guest??'')+' Orang');
            modalCC.find('[data-replace="total_invite"]').html((response.total_invite??'')+' Undangan');
            modalCC.find('[data-replace="delivery_date"]').html(response.delivery_date??'');
            modalCC.find('[data-replace="event_date"]').html(response.event_date??'');
            modalCC.find('[name="dp"]').val(formatIndonesia(response.dp));
            // console.log(response.cost_estimation);
            if(response.cost_estimation){
                modalCC.find('input[name="estimated_margin"]').val(response.cost_estimation.estimated_margin);
                modalCC.find('#kt_cetak_cost_control').removeClass('hidden');
            }else{
                modalCC.find('#kt_cetak_cost_control').addClass('hidden');
            }
            modalCC.find('[data-replace="status"]').html(response.status??'');
            if(response.status!='pending'){
                modalCC.find('#kt_cost_submit').addClass('hidden');
                modalCC.find('#kt_cost_udpate').removeClass('hidden');
            }else{
                modalCC.find('#kt_cost_submit').removeClass('hidden');
                modalCC.find('#kt_cost_udpate').addClass('hidden');
            }
            filledItem(response.items)
            filledItemDesc(response.items)
            filledCostStructure(response.cost_estimation)
            if(response.rincianbiaya.length){
                filledRincian(response.rincianbiaya);
            }else{
                initRincian();
            }
        },filledCostStructure=function(costestimation){
            if(!costestimation) return;
            let selectedItem=costestimation.detail,
                parent= modalCC.find('[data-replace="item_desc"]'),
                tagkategori,tagitem;
            clearCostStructure(false);
            $.each(selectedItem,function(x,y){
                tagitem='';
                let listItem=[];
                $.each(y.item,function(xitem,yitem){
                    if(yitem.fixed!='1'){
                        tagitem=tagitem+'<div class="input-group" data-structureid="'+yitem.id+'">'+
                            '<input type="hidden" name="structure['+yitem.id+'][kategori]" data-stref="kategori" value="'+y.name+'" readonly>'+
                            '<span class="input-group-text rounded-0 cursor-pointer text-hover-white bg-hover-danger delete-structure-item"><i class="fa fa-trash p-0"></i></span>'+
                            '<input type="text" name="structure['+yitem.id+'][name]" data-stref="name" class="form-control form-control-sm rounded-0 unvalidate w-100px keyup-change"  value="'+(yitem.name??'')+'"  placeholder="Nama Item:..">'+
                            '<input type="text" name="structure['+yitem.id+'][prosentase]" data-stref="prosentase" class="unvalidate w-50px text-center input-prosentase"  value="'+(yitem.prosentase?yitem.prosentase.replace('.', ','):'')+'"  placeholder="0">'+
                            '<span class="border border-1 border-secondary p-2 bg-secondary">%</span>'+
                            '<input type="hidden" name="structure['+yitem.id+'][prosentase_price]" data-stref="prosentase_price" data-cal="prosentase" value="0" readonly>'+
                            '<span class="form-control form-control-sm rounded-0 unvalidate readonly bg-gray-200" data-cal="prosentase_text">Nominal:..</span>'+
                        '</div>';
                        listItem.push({id:yitem.id,name:yitem.name,kategori:y.name,prosentase:yitem.prosentase,prosentase_price:null,fixed_price:null});
                    }else{
                        let name='',inputfixed='';
                        if(yitem.name!='-'){
                            name=yitem.name;
                            inputfixed=yitem.fixed_price?formatIndonesia(yitem.fixed_price):'';
                        }
                        tagitem=tagitem+'<div class="input-group" data-structureid="'+yitem.id+'">'+
                            '<input type="hidden" name="structure['+yitem.id+'][kategori]" data-stref="kategori" value="'+y.name+'" readonly>'+
                            '<span class="input-group-text rounded-0 cursor-pointer text-hover-white bg-hover-danger delete-structure-item"><i class="fa fa-trash p-0"></i></span>'+
                            '<input type="text" name="structure['+yitem.id+'][name]" data-stref="name" class="form-control form-control-sm rounded-0 unvalidate w-100px keyup-change" value="'+name+'" placeholder="Nama Item:..">'+
                            '<input type="text" name="structure['+yitem.id+'][fixed_price]" data-stref="fixed_price" class="form-control form-control-sm rounded-0 unvalidate input-fixed" value="'+inputfixed+'" placeholder="Nominal:..">'+
                        '</div>';
                        listItem.push({id:yitem.id,name:yitem.name,kategori:y.name,prosentase:null,prosentase_price:null,fixed_price:''});
                    }
                });
                tagkategori='<div class="w-100 mb-2 border border-2 p-1" data-structure="'+y.name+'">'+
                    '<div class="input-group">'+
                        '<span class="input-group-text rounded-0 cursor-pointer text-hover-white bg-hover-danger delete-structure"><i class="fa fa-trash p-0"></i></span>'+
                        '<span class="form-control form-control-sm rounded-0 pt-3"> <i class="fa-solid fa-list-ul me-2"></i>'+toTitleCase(y.name)+'</span>'+
                        '<select class="w-40px border-0 type-item-structure" data-control="select2" data-hide-search="true" >'+
                            '<option value="1">%</option>'+
                            '<option value="2">💵</option>'+
                        '</select>'+
                        '<span class="input-group-text rounded-0 cursor-pointer bg-hover-primary text-hover-white add-item-structure">'+
                            '<span class="svg-icon svg-icon-3 m-0 ">'+
                                '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">'+
                                    '<rect opacity="0.5" x="11" y="18" width="12" height="2" rx="1" transform="rotate(-90 11 18)" fill="currentColor" />'+
                                    '<rect x="6" y="11" width="12" height="2" rx="1" fill="currentColor" />'+
                                '</svg>'+
                            '</span>'+
                        '</span>'+
                    '</div>'+
                    tagitem+
                '</div>';
                parent.find('#cost-structure-item').append(tagkategori);
                structuredetail.push({name:y.name,item:listItem});
            });
            parent.find('#cost-structure-item .input-prosentase').trigger('keyup');
            parent.find('#cost-structure-item .input-fixed').trigger('keyup');
        },selectCost=function(cost_estimation){
            asyncData("{{route('web.cost_stucture.search')}}",{},"GET").then((response) => {
                if (!response.status) {
                    return console.log(response.message);
                }
                listCostStructure=response.data;
                $.each(listCostStructure,function(x,y){
                    modalCC.find('#kt_cost_structure_id').append(new Option(y.name, y.id, false, false));
                });
                if(cost_estimation){
                    modalCC.find('#kt_cost_structure_id').val(cost_estimation.cost_structure_id);
                }else{
                    modalCC.find('#kt_cost_structure_id').val('-');
                }
                modalCC.find('#kt_cost_structure_id').trigger("change");
            }).catch((error) => {
                let res = (error.responseJSON) ? error.responseJSON : defaultError;
                console.log(error ?? defaultError)
            });
        },toTitleCase=function(str){
            return str.replace(/\w\S*/g, function(txt) {
                return txt.charAt(0).toUpperCase() + txt.substr(1).toLowerCase();
            });
        },currency=function(cur){
            return new Intl.NumberFormat("id-ID", {
                    style: "currency",
                    currency: "IDR"
                }).format(cur);
        },formatIndonesia=function(numberString){
            let num = parseFloat(numberString);
            return num
                .toLocaleString("id-ID", {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                });
        },clearCostStructure=function(clearItem){
            $("#cost-structure-item").empty();
            if(clearItem){
                modalCC.find('#kt_cost_structure_id').html('<option value="-">Custom</option>');
            }
            structuredetail=[];
        },calcStrtucture=function(){
            let total = 0,sumrincian=0;;
            structuredetail.forEach(group => {
                group.item.forEach(i => {
                    if ((!i.prosentase_price || i.prosentase_price === "") && (!i.fixed_price || i.fixed_price === "")) {
                        return; 
                    } 
                    let val;
                    if (i.prosentase_price && i.prosentase_price !== "") {
                        val = parseFloat(i.prosentase_price);
                    } else {
                        let fp = (i.fixed_price ?? "0").toString().replace(/\./g, "").replace(/\,/g, ".");
                        val = parseFloat(fp);
                    }
                    total += val;
                });
            });
            modalCC.find('[data-replace="estimated_cc_label"]').html(currency(total));
            totalgrandTotal = parseFloat(total.toFixed(2)) + parseFloat(grandTotal.toFixed(2));
            modalCC.find('[data-replace="estimated_cost_label"]').html(currency(totalgrandTotal));
            modalCC.find('[data-replace="estimated_cost"]').val(formatIndonesia(totalgrandTotal));
            let em=modalCC.find('input[name="estimated_margin"]').val();
            modalCC.find('[data-replace="estimated_selling_price"]').val(formatIndonesia(totalgrandTotal+toNumber(em??0)));
            $.each(dataDetail.rincianbiaya,function(x,y){
                sumrincian+=Number(y.quantity || 1) * Number(y.price || 0);
            });
            let profit=sumrincian-totalgrandTotal;
            let profitPr=(profit / totalgrandTotal) * 100;
            modalCC.find('[data-replace="estimated_profit"]').html(ribuanDecimal(profitPr)+'%');
        },filledRincian=function(rincianbiaya){
            let listRincian=$("#list_rincian"),tag="";
            $.each(dataDetail.rincianbiaya,function(x,y){
                tag+='<div class="row ps-2 pe-2 item-rincian mb-2" ref-id="'+y.id+'">'+
                    '<div class="col-1 pe-1 ps-1">'+
                        '<input type="text" name="rincian['+y.id+'][qty]" class="form-control form-control-sm unvalidate text-end ref-qty input-fixed px-1" placeholder="Qty" value="'+(y.quantity?ribuanDecString(y.quantity):'')+'">'+
                    '</div>'+
                    '<div class="col-5 pe-1 ps-1">'+
                        '<input type="text" name="rincian['+y.id+'][name]" class="form-control form-control-sm unvalidate" placeholder="Rincian" value="'+(y.name??'')+'">'+
                    '</div>'+
                    '<div class="col-2 pe-1 ps-1">'+
                        '<input type="text" name="rincian['+y.id+'][price]" class="form-control form-control-sm unvalidate text-end ref-price input-fixed px-1" placeholder="Harga" value="'+(y.price?ribuanDecString(y.price):0)+'">'+
                    '</div>'+
                    '<div class="col-3 pe-1 ps-1">'+
                        '<span data-ref="rincian_total" class="form-control form-control-sm text-end px-1"></span>'+
                    '</div>'+
                    '<div class="col-1 pe-1 ps-2 pt-1">'+
                        '<a href="javascript:;" class="btn btn-sm btn-light-danger btn-active-danger px-2 py-1 text-center" data-delete="rincian"><i class="fa fa-trash p-0"></i></a>'+
                    '</div>'+
                '</div>';
            });
            listRincian.html(tag);
            calcRincian();
        },initRincian=function(){
            let listRincian=$("#list_rincian"),
                randID=Date.now().toString(36)+'-'+Math.random().toString(36).substr(2),
                tagRincian='<div class="row ps-2 pe-2 item-rincian mb-2" ref-id="'+randID+'">'+
                                '<div class="col-1 pe-1 ps-1">'+
                                    '<input type="text" name="rincian['+randID+'][qty]" class="form-control form-control-sm unvalidate text-end ref-qty input-fixed px-1" placeholder="Qty">'+
                                '</div>'+
                                '<div class="col-5 pe-1 ps-1">'+
                                    '<input type="text" name="rincian['+randID+'][name]" class="form-control form-control-sm unvalidate" placeholder="Rincian">'+
                                '</div>'+
                                '<div class="col-3 pe-1 ps-1">'+
                                    '<input type="text" name="rincian['+randID+'][price]" class="form-control form-control-sm unvalidate text-end ref-price input-fixed px-1" placeholder="Harga">'+
                                '</div>'+
                                '<div class="col-3 pe-1 ps-1">'+
                                    '<span data-ref="rincian_total" class="form-control form-control-sm text-end px-1"></span>'+
                                '</div>'+
                            '</div>';
            listRincian.html(tagRincian);
            dataDetail.rincianbiaya=[{id:randID,quantity:'',price:null,price_total:null}];
            calcRincian();
        },calcRincian=function(){
            let listRincian=$("#list_rincian"),
                sumSub,
                allsum=0;
            $.each(dataDetail.rincianbiaya,function(x,y){
                sumSub=Number(y.quantity || 1) * Number(y.price || 0);
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
        };
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
        });
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
            asyncData("{{ url('web/cost_controling') }}/"+refId,{},"GET").then((response) => {
                if (!response.status) {
                    blockUIBody.release();
                    return Swal.fire({icon: 'error',text: response.message});
                }
                dataDetail=response.data;
                filledModalUpdate(response.data, "{{ url('web/customer_service') }}/"+refId);
                selectCost(response.data.cost_estimation);
                modalCC.modal('show');
                blockUIBody.release();
            }).catch((error) => {
                blockUIBody.release();
                let res = (error.responseJSON) ? error.responseJSON : defaultError;
                Swal.fire({icon: 'error',text: res.message ?? defaultError});
                console.log(error ?? defaultError)
            });
        });
        $(document).on('submit', "#kt_cost_main", function(e){
            e.preventDefault(),
            form=$(this),
            submit=form.find('#kt_cost_submit'),
            validationCost.validate().then(function (valid) {
                valid=='Valid' && 
                (submit.attr("data-kt-indicator", "on"),
                submit.attr("disabled","true"),
                blockUICost.block(),
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
                            // clearOrder();
                            table.draw(false);
                            modalCC.modal('hide');
                            blockUICost.release();
                        },
                        error: function (error) {
                            (error = error.responseJSON? error.responseJSON: defaultError),
                            Swal.fire({icon: "error",text: error.message? error.message: defaultError}),
                            submit.removeAttr("data-kt-indicator"),
                            submit.removeAttr("disabled"),
                            blockUICost.release();
                        },
                        }) 
                },5e2))
            })
        });
        $(document).on('click', "#kt_cost_udpate", function(){
            let e=$(this);
            form=e.closest('form'),
            submit=form.find('#kt_cost_submit'),
            validationCost.validate().then(function (valid) {
                valid=='Valid' && 
                (submit.attr("data-kt-indicator", "on"),
                submit.attr("disabled","true"),
                blockUICost.block(),
                setTimeout(function () {
                    form.ajaxSubmit({
                        type: "post",
                        url: "{{route('web.cost_controling.update')}}",
                        dataType: "json",
                        success: function (response) {
                            if(response.status!='200'){
                                return Swal.fire({icon: "warning",text: response.message})
                            }
                            Swal.fire({icon: "success",text: response.message});
                            submit.removeAttr("data-kt-indicator"),
                            submit.removeAttr("disabled"),
                            form.resetForm();
                            // clearOrder();
                            table.draw(false);
                            modalCC.modal('hide');
                            blockUICost.release();
                        },
                        error: function (error) {
                            (error = error.responseJSON? error.responseJSON: defaultError),
                            Swal.fire({icon: "error",text: error.message? error.message: defaultError}),
                            submit.removeAttr("data-kt-indicator"),
                            submit.removeAttr("disabled"),
                            blockUICost.release();
                        },
                        }) 
                },5e2))
            });
        })
        $(document).on('change', '#kt_cost_main [name="estimated_margin"]', function(event){
            let e = $(this),
                val=e.val();
            if (!val || val.trim() === "") {
                val = 0;                       
            }else{
                val=val.replace(",", ".");
            }
            let totalFix = parseFloat(val)+parseFloat(totalgrandTotal??grandTotal);
            modalCC.find('[data-replace="estimated_selling_price"]').val(formatIndonesia(parseFloat(totalFix.toFixed(2))));
            // console.log(val);
            // console.log(totalFix);
        });
        $(document).on('click', '#kt_cetak_sr', function() {
            let e=$(this),
                pform=e.closest('form'),
                refId=pform.find('[name="id"]').val();
            e.attr("data-kt-indicator", "on"),
            e.attr("disabled","true"),
            asyncData("{{ url('web/cost_controling/exportsr') }}/"+refId,{},"GET").then((response) => {
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
        $(document).on('click', '#kt_cetak_sr_kitchen', function() {
            let e=$(this),
                pform=e.closest('form'),
                refId=pform.find('[name="id"]').val();
            e.attr("data-kt-indicator", "on"),
            e.attr("disabled","true"),
            asyncData("{{ url('web/kitchen/export') }}/"+refId,{},"GET").then((response) => {
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
        $(document).on('click', '#kt_cetak_cost_control', function() {
            let e=$(this),
                pform=e.closest('form'),
                refId=pform.find('[name="id"]').val();
            e.attr("data-kt-indicator", "on"),
            e.attr("disabled","true"),
            asyncData("{{ url('web/cost_controling/export') }}/"+refId,{},"GET").then((response) => {
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
        $(document).on('click', '[data-toggle]', function() {
            let e=$(this),
                parent=e.closest('[data-replace="item"]'),
                toggleTarger=e.attr('data-toggle');
            parent.find('[data-desc]').addClass('hidden');
            if(e.hasClass('active')){
                e.removeClass('active');
            }else{
                parent.find('[data-desc="'+toggleTarger+'"]').removeClass('hidden');
                parent.find('[data-toggle].active').removeClass('active');
                e.addClass('active');
            }
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
        $('#kt_cost_structure_id').on('select2:select', function (e) {
            let et=$(this),
                parent=et.closest('[data-replace="item_desc"]');
            clearCostStructure(false);
            if(e.params.data.id!=='-'){
                let selectItem=listCostStructure.find(x => x.id === e.params.data.id),
                    tagkategori,tagitem;
                $.each(selectItem.detail,function(x,y){
                    tagitem='';
                    let listItem=[];
                    $.each(y.item,function(xitem,yitem){
                        if(yitem.fixed!='1'){
                            tagitem=tagitem+'<div class="input-group" data-structureid="'+yitem.id+'">'+
                                '<input type="hidden" name="structure['+yitem.id+'][kategori]" data-stref="kategori" value="'+y.name+'" readonly>'+
                                '<span class="input-group-text rounded-0 cursor-pointer text-hover-white bg-hover-danger delete-structure-item"><i class="fa fa-trash p-0"></i></span>'+
                                '<input type="text" name="structure['+yitem.id+'][name]" data-stref="name" class="form-control form-control-sm rounded-0 unvalidate w-100px keyup-change"  value="'+(yitem.name??'')+'"  placeholder="Nama Item:..">'+
                                '<input type="text" name="structure['+yitem.id+'][prosentase]" data-stref="prosentase" class="unvalidate w-50px text-center input-prosentase"  value="'+(yitem.prosentase?yitem.prosentase.replace('.', ','):'')+'"  placeholder="0">'+
                                '<span class="border border-1 border-secondary p-2 bg-secondary">%</span>'+
                                '<input type="hidden" name="structure['+yitem.id+'][prosentase_price]" data-stref="prosentase_price" data-cal="prosentase" value="0" readonly>'+
                                '<span class="form-control form-control-sm rounded-0 unvalidate readonly bg-gray-200" data-cal="prosentase_text">Nominal:..</span>'+
                            '</div>';
                            listItem.push({id:yitem.id,name:yitem.name,kategori:y.name,prosentase:yitem.prosentase,prosentase_price:null,fixed_price:null});
                        }else{
                            let name='',inputfixed='';
                            if(yitem.name!='-'){
                                name=yitem.name;
                                inputfixed=yitem.fixed_price??'';
                            }
                            tagitem=tagitem+'<div class="input-group" data-structureid="'+yitem.id+'">'+
                                '<input type="hidden" name="structure['+yitem.id+'][kategori]" data-stref="kategori" value="'+y.name+'" readonly>'+
                                '<span class="input-group-text rounded-0 cursor-pointer text-hover-white bg-hover-danger delete-structure-item"><i class="fa fa-trash p-0"></i></span>'+
                                '<input type="text" name="structure['+yitem.id+'][name]" data-stref="name" class="form-control form-control-sm rounded-0 unvalidate w-100px keyup-change" value="'+name+'" placeholder="Nama Item:..">'+
                                '<input type="text" name="structure['+yitem.id+'][fixed_price]" data-stref="fixed_price" class="form-control form-control-sm rounded-0 unvalidate input-fixed" value="'+inputfixed+'" placeholder="Nominal:..">'+
                            '</div>';
                            listItem.push({id:yitem.id,name:yitem.name,kategori:y.name,prosentase:null,prosentase_price:null,fixed_price:''});
                        }
                    });
                    tagkategori='<div class="w-100 mb-2 border border-2 p-1" data-structure="'+y.name+'">'+
                        '<div class="input-group">'+
                            '<span class="input-group-text rounded-0 cursor-pointer text-hover-white bg-hover-danger delete-structure"><i class="fa fa-trash p-0"></i></span>'+
                            '<span class="form-control form-control-sm rounded-0 pt-3"> <i class="fa-solid fa-list-ul me-2"></i>'+toTitleCase(y.name)+'</span>'+
                            '<select class="w-40px border-0 type-item-structure" data-control="select2" data-hide-search="true" >'+
                                '<option value="1">%</option>'+
                                '<option value="2">💵</option>'+
                            '</select>'+
                            '<span class="input-group-text rounded-0 cursor-pointer bg-hover-primary text-hover-white add-item-structure">'+
                                '<span class="svg-icon svg-icon-3 m-0 ">'+
                                    '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">'+
                                        '<rect opacity="0.5" x="11" y="18" width="12" height="2" rx="1" transform="rotate(-90 11 18)" fill="currentColor" />'+
                                        '<rect x="6" y="11" width="12" height="2" rx="1" fill="currentColor" />'+
                                    '</svg>'+
                                '</span>'+
                            '</span>'+
                        '</div>'+
                        tagitem+
                    '</div>';
                    parent.find('#cost-structure-item').append(tagkategori);
                    structuredetail.push({name:y.name,item:listItem});
                });
                parent.find('#cost-structure-item .input-prosentase').trigger('keyup');
                parent.find('#cost-structure-item .input-fixed').trigger('keyup');
            }else{
                calcStrtucture();
            }
        });
        $(document).on('click', '#add_category', function() {
            let e=$(this),
                parent=e.closest('[data-replace="item_desc"]'),
                category_input=parent.find('#category_text').val().toLowerCase(),
                tagkategori;
            parent.find('#category_text').val('');
            if(parent.find('[data-structure="'+category_input+'"]').length){
                Toast.fire({icon: "info", title:'Kategori: '+category_input+' sudah ada.'});
                return ;
            }
            if(category_input.length){
                tagkategori='<div class="w-100 mb-2 border border-2 p-1" data-structure="'+category_input+'">'+
                        '<div class="input-group">'+
                        '<span class="input-group-text rounded-0 cursor-pointer text-hover-white bg-hover-danger delete-structure"><i class="fa fa-trash p-0"></i></span>'+
                        '<span class="form-control form-control-sm rounded-0 pt-3"> <i class="fa-solid fa-list-ul me-2"></i>'+toTitleCase(category_input)+'</span>'+
                        '<select class="w-40px border-0 type-item-structure" data-control="select2" data-hide-search="true" >'+
                            '<option value="1">%</option>'+
                            '<option value="2">💵</option>'+
                        '</select>'+
                        '<span class="input-group-text rounded-0 cursor-pointer bg-hover-primary text-hover-white add-item-structure">'+
                            '<span class="svg-icon svg-icon-3 m-0 ">'+
                                '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">'+
                                    '<rect opacity="0.5" x="11" y="18" width="12" height="2" rx="1" transform="rotate(-90 11 18)" fill="currentColor" />'+
                                    '<rect x="6" y="11" width="12" height="2" rx="1" fill="currentColor" />'+
                                '</svg>'+
                            '</span>'+
                        '</span>'+
                    '</div>'+
                '</div>';
                parent.find('#cost-structure-item').append(tagkategori);
                structuredetail.push({name:category_input,item:[]});
            }
        });
        $(document).on('click', '.add-item-structure', function() {
            let e=$(this),
                parentst=e.closest('[data-structure]'),
                typestructure=parentst.find('.type-item-structure').val(),
                structure=parentst.attr('data-structure').toLowerCase(),
                tagItem,keyst,itemstructure,
                uuid=Date.now().toString(36)+'-'+Math.random().toString(36).substr(2);
                if(typestructure=='1'){
                    tagItem='<div class="input-group" data-structureid="'+uuid+'">'+
                        '<input type="hidden" name="structure['+uuid+'][kategori]" data-stref="kategori" value="'+structure+'" readonly>'+
                        '<span class="input-group-text rounded-0 cursor-pointer text-hover-white bg-hover-danger delete-structure-item"><i class="fa fa-trash p-0"></i></span>'+
                        '<input type="text" name="structure['+uuid+'][name]" data-stref="name" class="form-control form-control-sm rounded-0 unvalidate w-100px keyup-change" placeholder="Nama Item:..">'+
                        '<input type="text" name="structure['+uuid+'][prosentase]" data-stref="prosentase" class="unvalidate w-50px text-center input-prosentase" placeholder="0" value="0">'+
                        '<span class="border border-1 border-secondary p-2 bg-secondary">%</span>'+
                        '<input type="hidden" name="structure['+uuid+'][prosentase_price]" data-stref="prosentase_price" data-cal="prosentase" value="0" readonly>'+
                        '<span class="form-control form-control-sm rounded-0 unvalidate readonly bg-gray-200" data-cal="prosentase_text">Nominal:..</span>'+
                    '</div>';
                    itemstructure={id:uuid,name:'',kategori:structure,prosentase:0,prosentase_price:0,fixed_price:null};
                }else{
                    tagItem='<div class="input-group" data-structureid="'+uuid+'">'+
                        '<input type="hidden" name="structure['+uuid+'][kategori]" data-stref="kategori" value="'+structure+'" readonly>'+
                        '<span class="input-group-text rounded-0 cursor-pointer text-hover-white bg-hover-danger delete-structure-item"><i class="fa fa-trash p-0"></i></span>'+
                        '<input type="text" name="structure['+uuid+'][name]" data-stref="name" class="form-control form-control-sm rounded-0 unvalidate w-100px keyup-change" placeholder="Nama Item:..">'+
                        '<input type="text" name="structure['+uuid+'][fixed_price]" data-stref="fixed_price" class="form-control form-control-sm rounded-0 unvalidate input-fixed" placeholder="Nominal:..">'+
                    '</div>';
                    itemstructure={id:uuid,name:'',kategori:structure,prosentase:null,prosentase_price:null,fixed_price:''};
                }
                parentst.append(tagItem);
                keyst = structuredetail.findIndex(x => x.name === structure);
                if (keyst !== -1) {
                    structuredetail[keyst].item.push(itemstructure);
                } 
        });
        $(document).on('click', '.delete-structure', function() {
            let e=$(this),
                parentst=e.closest('[data-structure]');
            parentst.remove();
            structuredetail = structuredetail.filter(x => x.name !== parentst.attr('data-structure'));
        });
        $(document).on('click', '.delete-structure-item', function() {
            let e=$(this),
                parentst=e.closest('[data-structure]'),
                stid=e.closest('[data-structureid]'),
                parent=e.closest('.input-group');
            let group = structuredetail.find(d => d.name === parentst.attr('data-structure'));
            if (group) {
                group.item = group.item.filter(i => i.id !== stid.attr('data-structureid'));
            }
            parent.remove();
        });
        $(document).on('keyup', '.input-prosentase', function() {
            let e=$(this),
                val=e.val(),
                parent=e.closest(".input-group");
            if(val === ""){
                val="0";
            }
            val = val.replace(/[^0-9,]/g, '');
            val = val.replace(/,(?=.*,)/g, '');
            if (val.includes(',')) {
                let parts = val.split(',');
                parts[1] = parts[1].substring(0, 2);
                val = parts.join(',');
            }
            val = val.replace(/^0+(?=\d)/, '');
            let numeric = parseFloat(val.replace(',', '.'));
            if (!isNaN(numeric) && numeric > 100) {
                val = '100';
            }
            e.val(val);
            let totalSellingPrice = dataDetail.rincianbiaya.reduce((sum, x) => {
                let qty = Number(x.quantity || 1);
                let price = x.price;
                if (!price) {
                    return sum; // skip
                }
                price = parseFloat(price);
                return sum + (isNaN(price) ? 0 : price*qty);
            }, 0), 
            totalsell=(totalSellingPrice/100*parseFloat(val.replace(',', '.'))).toFixed(2);
            // console.log(totalSellingPrice);
            parent.find('[data-cal="prosentase"]').val(totalsell);
            parent.find('[data-cal="prosentase_text"]').html(formatIndonesia(totalsell));
            e.trigger('change');
            parent.find('[data-cal="prosentase"]').trigger('change');
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
        $(document).on('change', '[data-refmenu]', function() {
            let e=$(this),
                refmenu=e.attr('data-refmenu');
            const findMenu= dataDetail.items.find(item => item.id === refmenu);
            if(findMenu){
                findMenu.porsi_request=e.val();
            }
            filledItemDesc(dataDetail.items)
        })
        $(document).on('keyup', '[data-structureid] input.keyup-change[data-stref]', function() {
            $(this).trigger('change');
        });
        $(document).on('change', '[data-structureid] input[data-stref]:not(hidden)', function() {
            let e=$(this),
                refname=e.attr('data-stref'),
                euid=e.closest('[data-structureid]'),
                uid=euid.attr('data-structureid'),
                parent=euid.closest('[data-structure]'),
                parentCategory=parent.attr('data-structure');
            
            let findparent = structuredetail.find(x => x.name === parentCategory);
            if (findparent) {
                let item = findparent.item.find(i => i.id === uid);
                if (item) {
                    item[refname] = e.val();  
                }
            }
            calcStrtucture();
        });
        $(document).on('click', '#add-rincian', function() {
            let e =$(this),
                listRincian=$("#list_rincian"),
                randID=Date.now().toString(36)+'-'+Math.random().toString(36).substr(2),
                tag='<div class="row ps-2 pe-2 item-rincian mb-2" ref-id="'+randID+'">'+
                    '<div class="col-1 pe-1 ps-1">'+
                        '<input type="text" name="rincian['+randID+'][qty]" class="form-control form-control-sm unvalidate text-end ref-qty input-fixed px-1" placeholder="Qty">'+
                    '</div>'+
                    '<div class="col-5 pe-1 ps-1">'+
                        '<input type="text" name="rincian['+randID+'][name]" class="form-control form-control-sm unvalidate" placeholder="Rincian">'+
                    '</div>'+
                    '<div class="col-2 pe-1 ps-1">'+
                        '<input type="text" name="rincian['+randID+'][price]" class="form-control form-control-sm unvalidate text-end ref-price input-fixed px-1" placeholder="Harga">'+
                    '</div>'+
                    '<div class="col-3 pe-1 ps-1">'+
                        '<span data-ref="rincian_total" class="form-control form-control-sm text-end px-1"></span>'+
                    '</div>'+
                    '<div class="col-1 pe-1 ps-2 pt-1">'+
                        '<a href="javascript:;" class="btn btn-sm btn-light-danger btn-active-danger px-2 py-1 text-center" data-delete="rincian"><i class="fa fa-trash p-0"></i></a>'+
                    '</div>'+
                '</div>';
            listRincian.append(tag);
            dataDetail.rincianbiaya.push({id:randID,quantity:'',price:null,price_total:null});
        });
        $(document).on('click', '[data-delete="rincian"]', function() {
            let e =$(this),
                itemRincian=e.closest('.item-rincian');
            dataDetail.rincianbiaya = dataDetail.rincianbiaya.filter(x => x.id !== itemRincian.attr('ref-id'));
            itemRincian.remove();
            calcRincian();
            $('#cost-structure-item .input-prosentase').trigger('keyup');
            $('#cost-structure-item .input-fixed').trigger('keyup');
        });
        $(document).on('change', '.ref-qty', function() {
            let e =$(this),
                itemRincian=e.closest('.item-rincian');
            const findRincian= dataDetail.rincianbiaya.find(item => item.id === itemRincian.attr('ref-id'));
            if(findRincian){
                findRincian.qty=parseFloat(
                    e.val().replace(/\./g, '').replace(',', '.')
                );
            }
            calcRincian();
            $('#cost-structure-item .input-prosentase').trigger('keyup');
            $('#cost-structure-item .input-fixed').trigger('keyup');
        });
        $(document).on('change', '.ref-price', function() {
            let e =$(this),
                itemRincian=e.closest('.item-rincian');
            const findRincian= dataDetail.rincianbiaya.find(item => item.id === itemRincian.attr('ref-id'));
            if(findRincian){
                findRincian.price=parseFloat(
                    e.val().replace(/\./g, '').replace(',', '.')
                );
            }
            calcRincian();
            $('#cost-structure-item .input-prosentase').trigger('keyup');
            $('#cost-structure-item .input-fixed').trigger('keyup');
        });
    });
</script>