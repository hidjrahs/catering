<script>
    let table,
        listActivemenu=[],
        totalKeseluruhan,
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
                url: "{{ route('web.management_stok.all-paginate') }}?device=web",
                method: 'GET',
                data: function(d) {
                    d.search = $('#search').val();
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
                            '<span class="text-muted fw-semibold text-muted d-block fs-8">Nomor : '+row.phone+'</span>'+
                        '</div></div>';
                    }
                }, {
                    data: 'est_price',
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
        });
    });
</script>