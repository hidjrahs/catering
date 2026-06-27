@extends('export_pdf.layout_ver2')
@section('content')
    <header>
        <div class="head-title text-center">
            <img src="{{ public_path('header_lila.png')}}" alt="Logo" style="width:100%; height:110px;">
        </div>
        <div class="table-section bill-tbl w-100 ">
            <table class="table w-100">
                <tr>
                    <td colspan="2" class="text-end bg-theme text-white">Data Purchasing Per EO: {{$data['order_ticket']??''}}</td>
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
    @php $numberbreak=1; @endphp
    @foreach($data['purchases'] as $itempurchase)
        <div class="table-section bill-tbl w-100">
            <table class="table w-100 mt-10">
                <tr>
                    <th colspan="3">Supplier</th>
                </tr>
                <tr>
                    <td class="w-20">Nama</td>
                    <td class="w-5">:</td>
                    <td class="w-75">{{$itempurchase['supplier']['name']??''}}</td>
                </tr>
                <tr>
                    <td>No. HP</td>
                    <td>:</td>
                    <td>{{$itempurchase['supplier']['phone']??''}}</td>
                </tr>
                <tr>
                    <td>Alamat</td>
                    <td>:</td>
                    <td>{{$itempurchase['supplier']['address']??''}}</td>
                </tr>
                <tr>
                    <td>PJ</td>
                    <td>:</td>
                    <td>{{$itempurchase['supplier']['penanggung_jawab']??''}}</td>
                </tr>
            </table>
        </div>
        <div class="table-section bill-tbl w-100 mt-10">
            <table class="table w-100 mt-10">
                <tr>
                    <th class="text-center">No</th>
                    <th class="w-55 text-left">Bahan</th>
                    <th class="w-10">Order</th>
                    <th class="w-10">Unit</th>
                    <th class="w-10">Harga</th>
                    <th class="w-10">Total</th>
                </tr>
                @php $num=1;@endphp
                @foreach($itempurchase['item'] as $itemp)
                    <tr>
                        <td class="text-center">{{$num}}</td>
                        <td class="text-left">{{$itemp['ingredient']['name']}}</td>
                        <td class="text-end">{{number_format($itemp['quantity'])}}</td>
                        <td class="text-end">{{number_format($itemp['ingredient']['unit'])}}</td>
                        <td class="text-end">{{number_format($itemp['ingredient']['default_price'],2,',','.')}}</td>
                        <td class="text-end">{{number_format($itemp['price'],2,',','.')}}</td>
                    </tr>
                        @php $num++;@endphp
                @endforeach
            </table>
        </div>
        @php $numberbreak++; @endphp
        @if($numberbreak < count($data['purchases'])+1)
            <div style="page-break-after: always;"></div>
        @endif
    @endforeach
    </main>
@endsection