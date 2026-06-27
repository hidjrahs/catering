@extends('export_pdf.layout_ver2')
@section('content')
    <header>
        <div class="head-title text-center">
            <img src="{{ public_path('header_lila.png')}}" alt="Logo" style="width:100%; height:110px;">
        </div>
        <div class="table-section bill-tbl w-100 ">
            <table class="table w-100">
                <tr>
                    <td colspan="2" class="text-end bg-theme text-white">Data Rincian Biaya : {{$data['order_ticket']??''}}</td>
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
                    <td class="w-50"><b>Customer Service</b>: {{$data['petugas']['name']??''}}</td>
                </tr>
            </table>
        </div>
    </header>
    <main>
    <div class="table-section bill-tbl w-100 table-themea">
        <table class="table w-100">
            <tr class="bg-theme-second text-center">
                <td>NO</td>
                <td>JUMLAH (PORSI)</td>
                <td>RINCIAN</td>
                <td>HARGA</td>
                <td>JUMLAH</td>
            </tr>
            @php $num=1;$alltot=0;@endphp
            @foreach($data['rincianbiaya'] as $rincianbiaya)
                @php $tot=($rincianbiaya['quantity']?$rincianbiaya['quantity']:1)*$rincianbiaya['price'];@endphp
                <tr>
                    <td class="text-center">{{$num}}</td>
                    <td class="text-center w-10">{{$rincianbiaya['quantity']?number_format($rincianbiaya['quantity']):''}}</td>
                    <td class="w-65">{{$rincianbiaya['name']}}</td>
                    <td class="text-end w-10">{{number_format($rincianbiaya['price'],2,',','.')}}</td>
                    <td class="text-end w-10">{{number_format($tot,2,',','.')}}</td>
                </tr>
                @php $num++; $alltot+=$tot; @endphp
            @endforeach
            <tr>
                <td colspan="4">Total</td>
                <td class="text-end">{{number_format($alltot,2,',','.')}}</td>
            </tr>
            <tr>
                <td colspan="4">DP</td>
                <td class="text-end">{{number_format($data['dp'],2,',','.')}}</td>
            </tr>
            <tr>
                <td colspan="4">Sisa</td>
                <td class="text-end">{{number_format($alltot-$data['dp'],2,',','.')}}</td>
            </tr>
        </table>
    </div>
    <div class="table-section bill-tbl w-100 mt-10">
        <table class="table-nb w-100 mt-10 text-center">
            <tr>
                <td class="w-30">Pemesan</td>
                <td class="w-60"></td>
                <td class="w-30">Customer Service</td>
            </tr>
            <tr>
                <td colspan="3" class="h-30"></td>
            </tr>
            <tr>
                <td class="w-30">{{$data['customer']['name']??''}}</td>
                <td class="w-60"></td>
                <td class="w-30">{{$data['petugas']['name']??''}}</td>
            </tr>
        </table>
    </div>
    </main>
@endsection