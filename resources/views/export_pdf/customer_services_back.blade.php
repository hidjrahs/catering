@extends('export_pdf.layout')
@section('content')
    <div id="watermark">
        <img src="{{ public_path('logo_min.png')}}" alt="Logo" style="width:250px">
        <div style="clear: both;"></div>
        {{$title}}
    </div>
    @if(isset($qrcode))
        <img src="data:image/png;base64, {!! base64_encode($qrcode) !!} " height="70px" style="max-height: 80px; position:absolute">
    @endif
    <div class="head-title text-center mb-40">
        <img src="{{ public_path('logo_min.png')}}" alt="Logo" style="width:130px">
        <h2 class="text-center m-0 p-0">Cetak Order {{$title}}</h2>
    </div>
    <div class="add-detail mt-10">
        <div class="w-50 float-left logo mt-10 fs-default">
            <p class="m-0 pt-5 text-bold w-100">No Order : <span class="gray-color">{{$data['order_ticket']??''}}</span></p>
        </div>
        <div class="w-50 float-left mt-10 text-end fs-default">
            <p class="m-0 pt-5 text-bold w-100">Tgl Order : <span class="gray-color">{{$data['delivery_date']??''}}</span></p>
        </div>
        <div style="clear: both;"></div>
    </div>
    <div class="table-section bill-tbl w-100 mt-10">
        <table class="table w-100 mt-10">
            <tr>
                <th class="w-50">Detail Customer</th>
                <th class="w-50">Detail Order</th>
            </tr>
            <tr>
                <td>
                    <div class="box-text">
                        <table class="w-100">
                            <tr>
                                <td>Nama Lengkap</td>
                                <td>:</td>
                                <td>{{$data['customer']?$data['customer']['name']:''}}</td>
                            </tr>
                            <tr>
                                <td>No. HP</td>
                                <td>:</td>
                                <td>{{$data['customer']?$data['customer']['phone']:''}}</td>
                            </tr>
                            <tr>
                                <td>Alamat</td>
                                <td>:</td>
                                <td>{{$data['customer']?$data['customer']['address']:''}}</td>
                            </tr>
                            <tr>
                                <td>Kota / Provinsi</td>
                                <td>:</td>
                                <td> {{$data['customer']?$data['customer']['vilage']['district']['city']['name']:''}} / {{$data['customer']?$data['customer']['vilage']['district']['city']['province']['name']:''}}</td>
                            </tr>
                            <tr>
                                <td>Kelurahan / Kecamatan</td>
                                <td>:</td>
                                <td>{{$data['customer']?$data['customer']['vilage']['name']:''}} / {{$data['customer']?$data['customer']['vilage']['district']['name']:''}}</td>
                            </tr>
                        </table>
                    </div>
                </td>
                <td >
                    <div class="box-text">
                        <table class="w-100">
                            <tr>
                                <td>Tipe Pesanan</td>
                                <td>:</td>
                                <td>{{$data['package_type']?ucwords(strtolower($data['package_type'])):''}}</td>
                            </tr>
                            <tr>
                                <td>Tema Event</td>
                                <td>:</td>
                                <td>{{$data['event_type']?ucwords(strtolower($data['event_type'])):''}}</td>
                            </tr>
                            <tr>
                                <td>Venue</td>
                                <td>:</td>
                                <td>{{$data['venue']??''}}</td>
                            </tr>
                            <tr>
                                <td>Total Tamu</td>
                                <td>:</td>
                                <td>{{$data['total_guest']??''}} Undangan</td>
                            </tr>
                            <tr>
                                <td>Tgl Acara</td>
                                <td>:</td>
                                <td>{{$data['event_date']??''}}</td>
                            </tr>
                            <tr>
                                <td>Tgl Input</td>
                                <td>:</td>
                                <td>{{$data['created_at']??''}}</td>
                            </tr>
                            <tr>
                                <td>Petugas</td>
                                <td>:</td>
                                <td>{{$data['petugas']?$data['petugas']['name']:''}}</td>
                            </tr>
                        </table>
                    </div>
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <h3 class="text-center m-0 p-0 mb-10">Keterangan</h3>
                    <div class="box-text p-4">{{$data['desc']??''}}</div>
                </td>
            </tr>
        </table>
    </div>
    <!-- <div class="table-section bill-tbl w-100 mt-10">
        <table class="table w-100 mt-10">
            <tr>
                <th class="w-50">Payment Method</th>
                <th class="w-50">Shipping Method</th>
            </tr>
            <tr>
                <td>Cash On Delivery</td>
                <td>Free Shipping - Free Shipping</td>
            </tr>
        </table>
    </div> -->
    <div class="table-section bill-tbl w-100 mt-10">
        <h3 class="text-center m-0 p-0">Daftar Menu Pesanan</h3>
        <table class="table w-100 mt-10">
            <tr>
            @php $colspan=3; @endphp
            @if($billing)
                <th class="w-5 text-center">No</th>
                <th class="w-35 text-left">Nama Menu</th>
                <th class="w-15 text-end">Porsi Request</th>
                <th class="w-15 text-end">Porsi Standard</th>
                <th class="w-15 text-end">Harga</th>
                <th class="w-15 text-end">Total</th>
                 @php $colspan=6; @endphp
            @else
                <th class="w-5 text-center">No</th>
                <th class="w-80 text-left">Nama Menu</th>
                <th class="w-15 text-end">Porsi Request</th>
            @endif
            </tr>
            @php $row=1; $totalAll=0; @endphp
            @foreach($data['ref_item'] as $menu)
                <tr>
                    <td class="text-center">{{$row}}</td>
                    <td class="text-left">{{$menu['menu']? ucwords(strtolower($menu['menu']['name'])):''}}</td>
                    <td class="text-end">{{$menu['quantity']? number_format($menu['quantity'], 0, ',', '.'):''}}</td>
                    @if($billing)
                        @php $total=($menu['price']/$menu['menu']['porsi_standard'])*$menu['quantity']; @endphp
                        <td class="text-end">{{$menu['menu']? number_format($menu['menu']['porsi_standard'], 0, ',', '.'):''}}</td>
                        <td class="text-end">{{$menu['price']? number_format($menu['price'], 0, ',', '.'):''}}</td>
                        <td class="text-end">{{number_format($total)}}</td>
                        @php $totalAll=$totalAll+$total; @endphp
                    @endif
                    @php $row++;  @endphp
                </tr>
            @endforeach
            @if($billing)
            <tr>
                @php $tax=$totalAll/100*10;  @endphp
                <td colspan="{{$colspan}}">
                    <div class="total-part">
                        <div class="total-left w-80 float-left" align="right">
                            <p>Sub Total</p>
                            <p>Ppn (10%)</p>
                            <p>Total Harga</p>
                        </div>
                        <div class="total-right w-20 float-left text-bold" align="right">
                            <p>Rp. {{number_format($totalAll, 0, ',', '.')}}</p>
                            <p>Rp. {{number_format($tax, 0, ',', '.')}}</p>
                            <p>Rp. {{number_format($totalAll+$tax, 0, ',', '.')}}</p>
                        </div>
                        <div style="clear: both;"></div>
                    </div> 
                </td>
            </tr>
            @endif
        </table>
    </div>
@endsection