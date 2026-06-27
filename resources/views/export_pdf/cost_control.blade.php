@extends('export_pdf.layout_ver2')
@section('content')
    <header>
        <div class="head-title text-center">
            <img src="{{ public_path('header_lila.png')}}" alt="Logo" style="width:100%;height:110px;">
        </div>
        <div class="table-section bill-tbl w-100 ">
            <table class="table w-100">
                <tr>
                    <td colspan="2" class="text-end bg-theme text-white">Data Cost Structure : {{$data['order_ticket']??''}}</td>
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
            <!-- <h3 class="text-center m-0 p-0">Cost Structure</h3> -->
            <table class="table w-100">
                <tr>
                    <th class="text-left" colspan="3">Total Rincian Biaya</th>
                    <th class="text-end">Rp. {{number_format($totalrincian,2,',','.')}}</th>
                </tr>
                <tr>
                    <th class="w-20 text-left">Biaya</th>
                    <th class="w-50 text-left">Nama</th>
                    <th class="w-10 text-left">Jumlah</th>
                    <th class="w-20 text-end">Nominal</th>
                </tr>
                @php $first='Food Cost/HPP'; $totalAll=0; @endphp
                @foreach($data['ref_item'] as $menu)
                    <tr>
                        <td class="text-left">{{$first}}</td>
                        <td class="text-left">{{$menu['menu']? ucwords(strtolower($menu['menu']['name'])):''}}</td>
                        <td class="text-left">{{$menu['quantity']}} Porsi</td>
                        @php $total=$menu['price'] @endphp
                        <td class="text-end">Rp. {{number_format($total,2,',','.')}}</td>
                        @php $totalAll=$totalAll+$total; @endphp
                        @php $first='';  @endphp
                    </tr>
                @endforeach
                <tr>
                    <td class="text-end" colspan="3"><b>Total Food Cost</b></td>
                    <td class="text-end">Rp. {{number_format($totalAll,2,',','.')}}</td>
                </tr>
                @php $kategori=''; $totalActualStructure=0; @endphp
                @foreach($data['costestimation']['detail'] as $detail)
                    @php $price=$detail['prosentase_price']??$detail['fixed_price']; @endphp
                    <tr>
                        @if($detail['kategori']!=$kategori)
                            <td class="text-left">{{ucwords(strtolower($detail['kategori']))}}</td>
                            @php  $kategori=$detail['kategori']; @endphp
                        @else
                            <td class="text-left"></td>
                        @endif
                        <td class="text-left">{{ucwords(strtolower($detail['name']))}}</td>
                        <td class="text-left">{{$detail['fixed']=='1'?number_format($detail['fixed_qty']??'1').' Unit':number_format($detail['prosentase'],2,',','.').' %'}}</td>
                        <td class="text-end">Rp. {{number_format($price,2,',','.')}}</td>
                        @php $totalActualStructure=$totalActualStructure+$price; @endphp
                    </tr>
                @endforeach
                <tr>
                    <td class="text-end" colspan="3"><b>Total Cost Structure</b></td>
                    <td class="text-end">Rp. {{number_format($totalActualStructure,2,',','.')}}</td>
                </tr>
                <tr>
                    <td class="text-end" colspan="3"><b>Total Actual Order</b></td>
                    <td class="text-end">Rp. {{number_format($totalActualStructure+$totalAll,2,',','.')}}</td>
                </tr>
                @php $profit=$totalrincian-($totalActualStructure+$totalAll); @endphp
                <tr>
                    <td class="text-end" colspan="3"><b>Profit</b></td>
                    <td class="text-end">{{number_format($profit/($totalActualStructure+$totalAll)*100,2,',','.')}}%</td>
                </tr>
            </table>
        </div>
        <div class="table-section bill-tbl w-100 mt-10">
            <table class="table-nb w-100 mt-10 text-center">
                <tr>
                    <td class="w-30">Pemesan</td>
                    <td class="w-60"></td>
                    <td class="w-30">Customer Control</td>
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