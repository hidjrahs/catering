<script>
    let category,
        pageActive,
        scroolLimit=false,
        listmenus=[],
        listActivemenu=[],
        totalKeseluruhan,
        form,submit,
        rincianItem,
        defaultError= "Proses tidak berhasil.";
    $(function() {
        'use strict';
        $.fn.dataTable.ext.errMode = 'none' ?? 'throw';
        let blockUIMenus = new KTBlockUI($("#list_menus")[0], {
            message: '<div class="blockui-message"><span class="spinner-border text-primary"></span> Loading...</div>',
        });
        let blockUIOrder = new KTBlockUI($("#kt_form_main")[0], {
            message: '<div class="blockui-message"><span class="spinner-border text-primary"></span> Loading...</div>',
        });
        let contentmenu=$('#list_menus'),
        contentorder=$("#list_order"),
        initPage=function(){
            pageActive=1;
        },upPage=function(){
            pageActive+=1;
        },
        filledOrder=function(){
            let tagOrder,porsiHide;
            contentorder.find("#item_none").addClass('hidden');
            $.each(listActivemenu,function(x,y){
                if(!contentorder.find('[data-ref="'+y.id+'"]').length){
                    tagOrder='<div class="d-flex flex-wrap align-items-center border rounded p-2 mb-2" data-ref="'+y.id+'">'+
                            '<input type="hidden" value="'+y.id+'" name="item['+y.id+'][menus_catering_id]">'+
                            '<input type="hidden" value="'+(y.porsi_standard??0)+'" name="item['+y.id+'][porsi_standard]">'+
                            '<input type="hidden" value="'+(y.is_quantity)+'" name="item['+y.id+'][is_request]">'+
                            '<div class="d-flex align-items-center w-50">'+
                                '<span class="symbol-label fw-bold px-2 py-1 text-center rounded"><i class="fa-solid '+y.icon+'"></i></span>'+
                                '<a href="javascript:;" class="fs-8 text-dark text-hover-primary fw-semibold">'+
                                y.name+
                                '</a>'+
                            '</div>'+
                            '<div class="d-flex align-items-center w-50 justify-content-end">'+
                                '<label class="me-2 fw-semibold hidden">Est. (Rp):</label>'+
                                '<input type="hidden" name="item['+y.id+'][price]" class="form-control form-control-sm w-100px me-2 bg-light unvalidate" readonly value="'+(y.selling_price??0)+'"/>'+
                                '<label class="me-2 fw-semibold '+(y.is_quantity=='1'?'':'hidden')+'">Porsi:</label>'+
                                '<input type="'+(y.is_quantity=='1'?'text':'hidden')+'" name="item['+y.id+'][quantity]" class="form-control form-control-sm w-100px me-2 item_request" placeholder="Jumlah" value="'+(y.porsi_request??0)+'"/>'+
                                '<a href="javascript:;" class="btn btn-sm btn-light-danger btn-active-danger px-2 py-1 text-center" data-delete="'+y.id+'"><i class="fa fa-trash p-0"></i></a>'+
                            '</div>'+
                            '<div class="d-flex align-items-center w-100 pt-2">'+
                                '<input type="text" name="item['+y.id+'][notes]" class="form-control form-control-sm w-100 " placeholder="Catatan:..." value=""/>'+
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
            formMain.find('[name="estimate_price"]').val(totalKeseluruhan);
            formMain.find('[name="estimate_price_label"]').val('Rp. '+totalKeseluruhan.toLocaleString("id-ID"));
        },
        clearOrder=function(){
            listActivemenu=[]
            contentorder.find('[data-ref]').remove();
            contentorder.find("#item_none").removeClass('hidden');
            formMain.find("#kt_select2_customer").val(null).trigger("change");
            contentmenu.find('[data-ref].active').removeClass('active');
            countItem();
            formMain.find('[name="vilage_id"]').val(null).trigger('change');
            formMain.find('[name="city_id"]').val(null).trigger('change');
            formMain.find('[name="package_type[]"]').val(null).trigger('change');
            formMain.find('[name="event_type[]"]').val(null).trigger('change');
            $('#packet-id').multipleSelect('setSelects', []);//.change();
        },getRandomItem=function(arr){
            return arr[Math.floor(Math.random() * arr.length)];
        },
        filledMenu =function(listmenu){
            let tagMenu,imageMenu,isActive,color;
            listmenus = listmenus.length ? [...listmenus, ...listmenu] : [...listmenu];
            // console.log(listmenus)
            let colors=['primary','warning','success','danger','info'];
            $.each(listmenu,function(x,y){
                if(y.icon){
                    imageMenu='<i class="fa-solid '+y.icon+' fs-2"></i>';
                }else{
                    imageMenu=y.label;
                }
                isActive=listActivemenu.some(el => el.id == y.id);
                color=colors[x % colors.length];
                tagMenu='<div class="card card-flush flex-row-fluid mw-100 mh-150px mw-300px border border-3 bg-light-'+color+' cursor-pointer '+(isActive?'active':'')+'" data-ref="'+y.id+'">'+
                    '<div class="card-body text-center p-2">'+
                        '<span class="symbol-label bg-light-'+color+' text-primary fw-bold p-2 text-center">'+
                            imageMenu+
                        '</span>'+
                        '<div class="mt-4">'+
                            '<div class="text-center">'+
                                '<span class="fw-bold text-gray-800 cursor-pointer text-hover-primary fs-7">'+y.name+'</span>'+
                                '<span class="text-gray-400 fw-semibold d-block fs-8 mt-n1">'+y.category_menu+'</span>'+
                            '</div>'+
                        '</div>'+
                    '</div>'+
                '</div>';
                contentmenu.append(tagMenu);
            });
        },clearMenu=function(){
            contentmenu.html('');
        },debounce=function (func, wait) {
            let timeout;
            return function (...args) {
                clearTimeout(timeout);
                timeout = setTimeout(() => func.apply(this, args), wait);
            };
        },getMenu=function(){
            if(pageActive==1){
                clearMenu();
            }
            let resultlist,search,paket;
            category=$('[name="category_menu"]:checked').val();
            paket=$('#packet-id').val();
            blockUIMenus.block();
            search=$("#search").val();
            asyncData("{{ route('web.menus_catering.search') }}/?category="+category+'&search='+search+'&page='+pageActive+'&paket='+paket,{},"GET").then((response) => {
                if (!response.status) {
                    blockUIMenus.release();
                    return Swal.fire({icon: 'error',text: response.message});
                }
                async function wait() {
                    resultlist = response.data;
                    await filledMenu(resultlist);
                }
                wait().then(function(){
                    blockUIMenus.release();
                    if(response.data.length){
                        upPage();
                    }else{
                        scroolLimit=true;
                    }
                })
            }).catch((error) => {
                blockUIMenus.release();
                let res = (error.responseJSON) ? error.responseJSON : defaultError;
                // Swal.fire({icon: 'error',text: res.message ?? defaultError});
                console.log(res.message ?? defaultError)
            });
        },getSideMenu=function(){
            let paket=$('#packet-id').val(),section=$("#category_menu"),totalCategory;
            asyncData('{{route("web.category_menus.all-data")}}?paket='+paket,{},"GET").then((response) => {
                $.each(response.data,function(x,y){
                    section.find('[data-category="'+y.id+'"]').html(y.active_menus_count!=0?y.active_menus_count:'');
                })
                totalCategory = response.data.reduce((sum, item) => sum + Number(item.active_menus_count || 0), 0);
                section.find('[data-category="total-all"]').html(totalCategory!=0?totalCategory:'');
            }).catch((error) => {
                let res = (error.responseJSON) ? error.responseJSON : defaultError;
                console.log(res.message ?? defaultError)
            });
        },addTag=function(){
            let paket=$('#packet-id').multipleSelect('getSelects', 'text'),
                elPacket=formMain.find('[name="package_type[]"]');
            elPacket.find('option.locked-tag').remove();
            $.each(paket,function(x,y){
                let newOption = new Option(y, y, true, true);
                $(newOption).addClass('locked-tag');
                elPacket.append(newOption);
            })
            elPacket.trigger('change');
        };
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
        formMain=$("#kt_form_main"),
        validation=FormValidation.formValidation(formMain[0],fieldValidation(formMain)),
        optionFormat = (item) => {
            if (!item.id) return item.text;

            let html  = '<div class="d-flex flex-column">';
            html += '<span class="fs-5 fw-bold lh-1">' + item.text + '</span>';
            html += '<span class="text-muted fs-6">' + (item.newTag ? 'Customer Baru' : 'Kontak: ' + item.phone) + '</span>';
            html += '</div>';

            return $(html);
        },selectionFormat = (item) => {
            if (item.newTag) {
                return item.text + " (Baru)";
            }
            return item.text || item.id;
        }, hitungHarga=(menu)=>{
            // const sprice = parseFloat(menu.selling_price.replace(/\./g, "")),
            const sprice = parseFloat(menu.selling_price),
                pstandard = parseFloat(menu.porsi_standard.replace(/\./g, "")),
                prequest = parseFloat(menu.porsi_request.replace(/\./g, "")),
                harga = (sprice / pstandard) * prequest;
            return harga;
            // return Math.round(harga);
        },scrollDown=function(){
            let e=$("#list_order"),
                height=e[0].scrollHeight;
            e.find('[data-list="ingredient_list"]').animate({ scrollTop: height }, 500);
        },initRincian=function(){
            let listRincian=$("#list_rincian"),
                randID=crypto.randomUUID(),
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
        $(document).on('keyup', '#search', function(event) {
            let e=$(this);
            if(event.which === 13){
                scroolLimit=false;
                initPage();
                getMenu();
            }
        });
        $(document).on('click', '#category_menu>li', function() {
            let e=$(this),
                parent=e.closest('#category_menu');
            parent.find('li>a.active').removeClass('active ');
            parent.find('li>a.active>bullet-custom').addClass('hidden ');
            e.find('a').addClass('active');
            e.find('.bullet-custom').removeClass('hidden');
            e.find('[name="category_menu"]').prop('checked',true);
            scroolLimit=false;
            initPage();
            listmenus=[];
            getMenu();
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
                contentmenu.find('[data-ref="'+refId+'"]').removeClass('active')
                countItem();
                totalHarga();
            }
            if(!listActivemenu.length){
                contentorder.find("#item_none").removeClass('hidden');
            }
        });
        $(document).on('click', '#list_menus [data-ref]', function() {
            let e =$(this),
                refId=e.attr('data-ref'),
                item=listmenus.find(el => el.id == refId);
            if (item && !listActivemenu.some(el => el.id == item.id)) {
                item.porsi_request=item.porsi_standard;
                listActivemenu.push(item);
                filledOrder();
                scrollDown();
                e.addClass('active');
            }
            // console.log(listActivemenu);
        });
        $(document).on('click', '#add-rincian', function() {
            let e =$(this),
                listRincian=$("#list_rincian"),
                randID=crypto.randomUUID(),
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
        $("#list_menus").on("scroll", debounce(function () {
            let el = $(this),
                scrollPos = el.scrollTop(),
                maxScroll = el[0].scrollHeight - el.innerHeight(),
                maxHeight=200;
            if (maxScroll - scrollPos <= maxHeight&&scroolLimit==false) {
                getMenu();
            }
        }, 200));
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
        $(document).on('submit', "#kt_form_main", function(e){
            e.preventDefault(),
            form=$(this),
            submit=form.find('#kt_form_submit'),
            validation.validate().then(function (valid) {
                valid=='Valid' && 
                (submit.attr("data-kt-indicator", "on"),
                submit.attr("disabled","true"),
                blockUIOrder.block(),
                setTimeout(function () {
                    if(!listActivemenu.length){
                        return Swal.fire({icon: "warning",text: 'belum melakukan order menu.'}) 
                    }else{
                        form.ajaxSubmit({
                            type: "post",
                            url: form.attr("action"),
                            dataType: "json",
                            success: function (response) {
                                if(response.status!='200'){
                                    return Swal.fire({icon: "warning",text: response.message})
                                }
                                if(response.data.export){
                                    $("#pdfModal").find('iframe').attr('src',response.data.export??'');
                                    $("#pdfModal").modal('show');
                                }else{
                                    Swal.fire({icon: "success",text: response.message});
                                }
                                submit.removeAttr("data-kt-indicator"),
                                submit.removeAttr("disabled"),
                                form.resetForm();
                                clearOrder();
                                blockUIOrder.release();
                                initRincian();
                            },
                            error: function (error) {
                                (error = error.responseJSON? error.responseJSON: defaultError),
                                Swal.fire({icon: "error",text: error.message? error.message: defaultError}),
                                submit.removeAttr("data-kt-indicator"),
                                submit.removeAttr("disabled"),
                                blockUIOrder.release();
                            },
                        }) 
                    }
                    // console.log('Proses Input')
                },5e2))
            })
        });
        const optionFormatCity = (item) => {
            if (!item.id) return item.text;

            let html  = '<div class="d-flex flex-column">';
            html += '<span class="fs-5 fw-bold lh-1">' + item.text + '</span>';
            html += '<span class="text-muted fs-6">' + 'Provinsi: ' + (item.cat??$(item.element).attr('data-cat')) + '</span>';
            html += '</div>';

            return $(html);
        },optionFormatVilage= (item) => {
            if (!item.id) return item.text;

            let html  = '<div class="d-flex flex-column">';
            html += '<span class="fs-5 fw-bold lh-1">' + item.text + '</span>';
            html += '<span class="text-muted fs-6">' + 'Kec: ' +  (item.cat??$(item.element).attr('data-cat'))  + '</span>';
            html += '</div>';

            return $(html);
        };
        $("#delivery_date, #event_date").flatpickr({
            enableTime: true,
            dateFormat: "Y-m-d H:i",
            time_24hr: true,
            defaultDate: new Date()
        });
        $("#event_time").flatpickr({
            enableTime: true,
            noCalendar: true,
            dateFormat: "H:i",
            time_24hr: true,
            defaultDate: new Date()
        });
        $('#kt_form_main [name="city_id"]').select2({
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
        $('#kt_form_main [name="vilage_id"]').select2({
            placeholder: "Kelurahan/Desa:...",
            ajax: {
                url: "{{route('web.ref_wilayah.search_vilage')}}",
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return {
                        q: params.term,
                        refId: $('#kt_form_main [name="city_id"]').val()
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
        $('[data-control="select2"][multiple]').select2({ tags: true });
        $('#packet-id').multipleSelect({
            selectAll: false,
            animate: 'slide',
            minimumCountSelected: 2,
            formatCountSelected: function (numSelected, numTotal) {
                return numSelected + ' Paket dipilih';
            },
        }) .on('change', function() {
            initPage();
            getMenu();
            getSideMenu();
            addTag();
        });
        let loadMultipleSelect=function(select){
            asyncData('{{route("web.packet_menus.search-all")}}',{},"GET").then((response) => {
                let $select = $(select);
                $select.empty();
                response.data.forEach(row => {
                    $select.append(`<option value="${row.id}">${row.name}</option>`);
                });
                $select.multipleSelect('refresh');
            }).catch((error) => {
                let res = (error.responseJSON) ? error.responseJSON : defaultError;
                console.log(res.message ?? defaultError)
            });
        }
        $('#package_type').on('select2:unselecting', function (e) {
            let data = e.params.args.data;
            let option = $(this).find('option[value="'+data.id+'"]');
            if (option.hasClass('locked-tag')) {
                e.preventDefault();
            }
        });
        loadMultipleSelect("#packet-id");
        initPage();
        getMenu();
        initRincian();
    });
</script>