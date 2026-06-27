@extends('export_pdf.layout_ver3')
@section('content')
    <header>
        <div class="head-title text-center">
            <img src="{{ public_path('header_lila.png')}}" alt="Logo" style="width:100%; height:110px;">
        </div>
        <div class="table-section bill-tbl w-100 ">
            <table class="table w-100">
                <tr>
                    <td colspan="2" class="text-end bg-theme text-white">Data Purchasing Per Batch: {{$range??''}}</td>
                </tr>
            </table>
        </div>
    </header>
    <main>
        @php $numberbreak=1; @endphp
        @foreach($data as $itempurchase)
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
            <div class="table-section bill-tbl w-100">
                <table class="table w-100 mt-10">
                    <tr>
                        <th class="text-center">No</th>
                        <th class="w-55 text-left">Bahan</th>
                        <th class="w-10">Order</th>
                        <th class="w-10">Unit</th>
                        <th class="w-10">Harga</th>
                        <th class="w-10">Total</th>
                    </tr>
                    @php $num=1;$totS=0;$maxItem=46;@endphp
                    @foreach($itempurchase['duplicates'] as $itemp)
                        <tr>
                            <td class="text-center">{{$num}}</td>
                            <td class="text-left">{{$itemp['ingredient']['name']}}</td>
                            <td class="text-end">{{number_format($itemp['quantity'],2,',','.')}}</td>
                            <td class="text-end">{{number_format($itemp['ingredient']['unit'])}}</td>
                            <td class="text-end">{{number_format($itemp['ingredient']['default_price'],2,',','.')}}</td>
                            <td class="text-end">{{number_format($itemp['price'],2,',','.')}}</td>
                        </tr>
                            @php $num++; $totS+=$itemp['price'];@endphp
                            @if($num%$maxItem==0)
                                </table>
                                </div>
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
                                <div class="table-section bill-tbl w-100">
                                    <table class="table w-100 mt-10">
                                        <tr>
                                            <th class="text-center">No</th>
                                            <th class="w-55 text-left">Bahan</th>
                                            <th class="w-10">Order</th>
                                            <th class="w-10">Unit</th>
                                            <th class="w-10">Harga</th>
                                            <th class="w-10">Total</th>
                                        </tr>
                            @endif
                    @endforeach
                    <tr>
                            <td colspan="5">Total Harga</td>
                            <td class="text-end">Rp. {{number_format($totS,2,',','.')}}</td>
                    </tr>
                </table>
            </div>
            @php $numberbreak++; @endphp
            @if($numberbreak < count($data)+1)
                <div style="page-break-after: always;"></div>
            @endif
        @endforeach
    </main>
@endsection