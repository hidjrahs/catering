<script>
    let table,
    defaultError= "Proses tidak berhasil.";
    $(function() {
        'use strict';
        $.fn.dataTable.ext.errMode = 'none' ?? 'throw';
        table = $('#main-table').DataTable({
            processing: true,
            serverSide: true,
            orderable: false,
             ajax: {
                url: "{{ route('web.kitchen.all-paginate') }}?device=web",
                method: 'GET',
                data: function(d) {
                    d.search = $('#search').val();
                    d.date = $('#filter_time').val();
                    d.orders = $('#order').val();
                }
            },
            'createdRow': function( row, data, dataIndex ) {
                $(row).addClass('cursor-pointer')
                    .attr('title','Preview Order : '+data.name+' ?')
                    .attr('data-ref',data.id);
            },
            columns: [ {
                    data: 'order_ticket',
                    orderable: false,
                    render: function(data, type, row, meta) {
                        return '<div class="d-flex ">'+
                        '<div class="flex-grow-1">'+
                            '<span class="text-dark fw-bold text-hover-primary mb-1 fs-6">'+data+'</span>'+
                        '</div>'+
                        '<div class="flex-grow-1 text-end">'+
                             '<span class="text-dark fw-bold text-hover-primary mb-1 fs-6 ">'+row.event_date+'</span>'+
                        '</div></div>';
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
                "<'col-sm-12 d-flex align-items-center justify-content-center justify-content-md-start'i>" +
                "<'col-sm-12 d-flex align-items-center justify-content-center justify-content-md-end'p>" +
                ">"
        });
        $(document).on('change', '.search-input', function() {
            table.draw();
        });
        $(document).on('keyup', '.search-input', function(event) {
            event.which === 13 && (table.draw())
        });
        let blockUIBody = new KTBlockUI($("body")[0], {
            message: '<div class="blockui-message"><span class="spinner-border text-primary"></span> Loading...</div>',
        }),toDecimal=function(str){
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
        },ribuanDecimal=function(num){
            return num.toLocaleString('id-ID', {
                minimumFractionDigits: 0,
                maximumFractionDigits: 2
            });
        },formatIndonesia=function(numberString){
            let num = parseFloat(numberString);
            return num
                .toLocaleString("id-ID", {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                });
        },filledItemDesc=function(data){
            let items=data.items,
                parent=$('[data-replace="item"]'),
                titleorder=$('[data-ref="order-title"]'),
                tagItem,tagTr,orderQty,total,totalCost,netCost,subTotal,grandTC=0,grandNc=0,estimated_margin,grandTotal=0;
            titleorder.html(data.order_ticket+' | '+data.event_date)
            $.each(items,function(x,y){
                tagTr="",orderQty=0,total=0,subTotal=0,totalCost=0;
                $.each(y.ingredients,function(xingred,yingred){
                    if(!yingred.ingredient){
                        tagTr+='<tr class="bg-light-success">'+
                            '<td colspan="3">'+yingred.ingredient_label+'</td>'+
                        '</tr>';
                    }else{
                        orderQty=orderCalcQty(yingred.quantity,y.porsi_standard,y.porsi_request);
                        total=orderCalcTotal(yingred.ingredient.unit,yingred.ingredient.default_price,orderQty);
                        totalCost+=total;
                        tagTr+='<tr>'+
                            '<td>'+ribuanDecimal(orderQty)+'</td>'+
                            '<td>'+yingred.ingredient.satuan+'</td>'+
                            '<td colspan="2">'+yingred.ingredient.name+'</td>'+
                        '</tr>';
                    }
                })
                netCost=(10/100)*totalCost;
                subTotal=totalCost+netCost;
                grandTotal+=subTotal;
                grandTC+=totalCost;
                grandNc+=netCost;
                tagItem='<div class="card border mb-2"><div class="card-body p-0">'+
                    '<table class="table align-middle fs-8 gy-3 gs-3 table-sm table-row-bordered m-0">'+
                        '<thead>'+
                            '<tr class="fw-bold bg-success text-white">'+
                                '<th colspan="2">'+y.name+'</th>'+
                                '<th class="mw-100px text-end">'+y.porsi_request+' Porsi</th>'+
                            '</tr>'+
                            '<tr class="fw-bold text-muted bg-light">'+
                                '<th>Order</th>'+
                                '<th>Satuan</th>'+
                                '<th colspan="2">Bahan</th>'+
                            '</tr>'+
                        '</thead>'+
                        '<tbody>'+
                            tagTr+
                        '</tbody>'+
                    '</table>'+
                '</div></div>';
                parent.append(tagItem);
            });
            $("#kt_cetak_order").removeClass('disabled');
            $("#kt_cetak_order").attr('data-id',data.id);
        },clearItemDesc=function(){
            let parent=$('[data-replace="item"]');
            parent.html('');
            $("#kt_cetak_order").addClass('disabled');
            $("#kt_cetak_order").removeAttr('data-id');
        };
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
        $(document).on('click', '#main-table [data-ref]', function(event) {
            let refId=$(this).attr('data-ref');
            if(!refId){
                return Swal.fire({icon: 'warning',text: 'Data Order tidak ditemukan.'});
            }
            blockUIBody.block();
            clearItemDesc();
            asyncData("{{ url('web/kitchen') }}/"+refId,{},"GET").then((response) => {
                if (!response.status) {
                    blockUIBody.release();
                    return Swal.fire({icon: 'error',text: response.message});
                }
                filledItemDesc(response.data)
                blockUIBody.release();
            }).catch((error) => {
                blockUIBody.release();
                let res = (error.responseJSON) ? error.responseJSON : defaultError;
                Swal.fire({icon: 'error',text: res.message ?? defaultError});
                console.log(error ?? defaultError)
            });
        });
        $(document).on('click', '#kt_cetak_order', function() {
            let e=$(this),
                refId=e.attr('data-id');
            if(refId){
                e.attr("data-kt-indicator", "on");
                e.attr("disabled","true");
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
            }
        });
    });
</script>