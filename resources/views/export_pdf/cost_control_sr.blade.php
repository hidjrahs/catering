@extends('export_pdf.layout_ver2')
@section('content')
    <header>
        <div class="head-title text-center">
            <img src="{{ public_path('header_lila.png')}}" alt="Logo" style="width:100%; height:110px;">
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
    <div class="table-section bill-tbl w-100 table-themea">
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
                        @php $ingNew="<tr><td colspan='6'>".$ingred['ingredient_label']."</td></tr>"; @endphp
                    @else
                        @php
                            $orderIng=$ingred['quantity']/$item['menu']['porsi_standard']*$item['quantity']; 
                            $tot=round($ingred['ingredient']['default_price']/$ingred['ingredient']['unit']*$orderIng,2);
                            $ingNew="<tr><td class='text-end'>".$orderIng."</td><td class='text-center'>".$ingred['ingredient']['satuan']."</td><td>".$ingred['ingredient']['name']."</td><td class='text-end'>".$ingred['ingredient']['unit']."</td><td class='text-end'>".$ingred['ingredient']['default_price']."</td><td class='text-end'>".$tot."</td></tr>"; 
                            $gramasi+=$orderIng;
                        @endphp
                    @endif
                    @php $ing.=$ingNew; @endphp
                @endforeach
                @if($r%2===0){
                    @php
                        $menuright='<table class="w-100 mb-10 table"><thead>'.
                                '<tr>'.
                                    '<td colspan="4" rowspan="2">'.$item['menu']['name'].'</td>'.
                                    '<td>Standard</td>'.
                                    '<td class="text-end">'.$item['menu']['porsi_standard'].'</td>'.
                                '</tr>'.
                                '<tr>'.
                                    '<td>Request</td>'.
                                    '<td class="text-end">'.$item['quantity'].'</td>'.
                                '</tr>'.
                                '<tr>'.
                                    '<td colspan="2" class="text-center">Order Quantity</td>'.
                                    '<td>Bahan</td>'.
                                    '<td>Unit</td>'.
                                    '<td>harga</td>'.
                                    '<td>Total</td>'.
                                '</tr>'.
                            '</thead>'.
                            '<tbody>'.$ing.'</tbody>'.
                            '<tfoot>'.
                                '<tr>'.
                                    '<td colspan="4">'.$item['menu']['name'].'</td>'.
                                    '<td>Total Cost</td>'.
                                    '<td class="text-end">'.number_format($item['price'],2,',','.').'</td>'.
                                '</tr>'.
                                '<tr>'.
                                    '<td colspan="4">Total Gramasi</td>'.
                                    '<td colspan="2" class="text-end">'.number_format($gramasi,2,',','.').'</td>'.
                                '</tr>'.
                                '<tr>'.
                                    '<td colspan="6">'.$item['notes'].'</td>'.
                                '</tr>'.
                            '</tfoot>'.
                            '</table>';
                    @endphp
                    <td class="w-50">{!!$menuright!!}</td><tr>
                @else
                    @php
                        $menuleft='<table class="w-100 mb-10 table"><thead>'.
                                '<tr>'.
                                    '<td colspan="4" rowspan="2">'.$item['menu']['name'].'</td>'.
                                    '<td>Standard</td>'.
                                    '<td class="text-end">'.$item['menu']['porsi_standard'].'</td>'.
                                '</tr>'.
                                '<tr>'.
                                    '<td>Request</td>'.
                                    '<td class="text-end">'.$item['quantity'].'</td>'.
                                '</tr>'.
                                '<tr>'.
                                    '<td colspan="2" class="text-center">Order Quantity</td>'.
                                    '<td>Bahan</td>'.
                                    '<td>Unit</td>'.
                                    '<td>harga</td>'.
                                    '<td>Total</td>'.
                                '</tr>'.
                            '</thead>'.
                            '<tbody>'.$ing.'</tbody>'.
                            '<tfoot>'.
                                '<tr>'.
                                    '<td colspan="4">'.$item['menu']['name'].'</td>'.
                                    '<td>Total Cost</td>'.
                                    '<td class="text-end">'.number_format($item['price'],2,',','.').'</td>'.
                                '</tr>'.
                                '<tr>'.
                                    '<td colspan="4">Total Gramasi</td>'.
                                    '<td colspan="2" class="text-end">'.number_format($gramasi,2,',','.').'</td>'.
                                '</tr>'.
                                '<tr>'.
                                    '<td colspan="6">'.$item['notes'].'</td>'.
                                '</tr>'.
                            '</tfoot>'.
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