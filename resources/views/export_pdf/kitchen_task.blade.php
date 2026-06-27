@extends('export_pdf.layout_ver2')
@section('content')
    <header>
        <div class="head-title text-center">
            <img src="{{ public_path('header_lila.png')}}" alt="Logo" style="width:100%;height:110px;">
        </div>
        <div class="table-section bill-tbl w-100 ">
            <table class="table w-100">
                <tr>
                    <td colspan="2" class="text-end bg-theme text-white">Data SR : {{$data['order_ticket']??''}}</td>
                </tr>
                <tr class="">
                    <td class="w-50"><b>Nama Pemesan</b>: {{$data['customer']?$data['customer']['name']:''}}</td>
                    <td class="w-50"><b>Hari, Tanggal: </b>{{$data['tgl_event']??''}}</td>
                </tr>
                <tr class="">
                    <td class="w-50"><b>No Telepon</b>: {{$data['customer']?$data['customer']['phone']:''}}</td>
                    <td class="w-50"><b>Venue</b>: {{$data['venue']??''}}</td>
                </tr>
                <tr class="">
                    <td class="w-50"><b>Alamat</b>: {{$data['customer']?$data['customer']['address']:''}} {{$data['customer']?$data['customer']['vilage']['name']:''}}/{{$data['customer']?$data['customer']['vilage']['district']['name']:''}} {{$data['customer']?$data['customer']['vilage']['district']['city']['name']:''}} / {{$data['customer']?$data['customer']['vilage']['district']['city']['province']['name']:''}}</td>
                    <td class="w-50"><b>Cost Control</b>: {{$data['petugas']['name']??''}}</td>
                </tr>
            </table>
        </div>
    </header>
    <main>
        <div class="table-section bill-tbl w-100 mt-10">
            <table class="table w-100">
                <tr class="bg-theme text-white">
                    <td colspan="2" class="text-center">
                        Menu
                    </td>
                </tr>
                @php 
                    $menuleft="";
                    $menuright="";
                    $r=1;
                @endphp
                @foreach($data['ref_item'] as $item)
                    @php $ing=""; $gramasi=0;@endphp
                    @foreach($item['menu']['menuingredients'] as $ingred)
                        @if($ingred['ingredient_label'])
                            @php $ingNew="<tr><td colspan='3'>".$ingred['ingredient_label']."</td></tr>"; @endphp
                        @else
                            @php $ingNew="<tr><td class='text-end'>".$ingred['quantity']."</td><td class='text-center'>".$ingred['ingredient']['satuan']."</td><td>".$ingred['ingredient']['name']."</td></tr>"; @endphp
                        @endif
                        @php 
                            $ing.=$ingNew; 
                            $gramasi+=$ingred['quantity'];
                        @endphp
                    @endforeach
                    @if($r%2===0){
                        @php
                            $menuright='<table class="w-100 mb-10 table"><thead>'.
                                    '<tr>'.
                                        '<td colspan="2">'.$item['menu']['name'].'</td>'.
                                        '<td class="text-end">'.$item['quantity'].' Porsi</td>'.
                                    '</tr>'.
                                    '<tr>'.
                                        '<td class="text-end">Order</td>'.
                                        '<td class="text-center">Satuan</td>'.
                                        '<td>Bahan</td>'.
                                    '</tr>'.
                                '</thead>'.
                                '<tbody>'.$ing.
                                    '<tr>'.
                                        '<td colspan="2">Total Gramasi</td>'.
                                        '<td class="text-end">'.number_format($gramasi,2,',','.').'</td>'.
                                    '</tr>'.
                                    '<tr><td colspan="3">'.$item['notes'].'</td></tr>'.
                                '</tbody>'.
                                '</table>';
                        @endphp
                        <td class="w-50">{!!$menuright!!}</td><tr>
                    @else
                        @php
                            $menuleft='<table class="w-100 mb-10 table"><thead>'.
                                    '<tr>'.
                                        '<td colspan="2">'.$item['menu']['name'].'</td>'.
                                        '<td class="text-end">'.$item['quantity'].' Porsi</td>'.
                                    '</tr>'.
                                    '<tr>'.
                                        '<td class="text-end">Order</td>'.
                                        '<td class="text-center">Satuan</td>'.
                                        '<td>Bahan</td>'.
                                    '</tr>'.
                                '</thead>'.
                                '<tbody>'.$ing.
                                    '<tr>'.
                                        '<td colspan="2">Total Gramasi</td>'.
                                        '<td class="text-end">'.number_format($gramasi,2,',','.').'</td>'.
                                    '</tr>'.
                                    '<tr><td colspan="3">'.$item['notes'].'</td></tr>'.
                                '</tbody>'.
                                '</table>';
                        @endphp
                        <tr class="v-top"><td class="w-50">{!!$menuleft!!}</td>
                    @endif
                    @php $r++; @endphp
                @endforeach
            </table>
        </div>
    </main>
@endsection