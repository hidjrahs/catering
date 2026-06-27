@extends('export_pdf.layout')
@section('content')
    @if(isset($qrcode))
        <img src="data:image/png;base64, {!! base64_encode($qrcode) !!} " height="40px" style="max-height: 40px; position:absolute; top:-40px; left:-40px">
    @endif
    <div class="head-title text-center">
        <img src="{{ public_path('header_lila.png')}}" alt="Logo" style="width:100%">
    </div>
    <div class="table-section bill-tbl w-100">
        <table class="table w-100 table-theme">
            <tr>
                <th colspan="2" class="p-8 bg-white font-18">Data Pesanan</th>
            </tr>
            <tr>
                <th colspan="2" class="text-end p-6 bg-theme text-white">Update EO {{$data['tgl_indo']??''}}</th>
            </tr>
            <tr>
                <td class="w-50">
                    <table class="w-100 table-nb">
                        <tr>
                            <td class="w-30">Nama Pemesan</td>
                            <td class="w-70 border-bottom">{{$data['customer']?$data['customer']['name']:''}}</td>
                        </tr>
                        <tr>
                            <td class="w-30">No Telepon</td>
                            <td class="w-70 border-bottom">{{$data['customer']?$data['customer']['phone']:''}}</td>
                        </tr>
                        <tr>
                            <td class="w-30">Alamat</td>
                            <td class="w-70 border-bottom">{{$data['customer']?$data['customer']['address']:''}}</td>
                        </tr>
                        <tr>
                            <td class="w-30"></td>
                            <td class="w-70 border-bottom">Desa/Kel: {{$data['customer']?$data['customer']['vilage']['name']:''}} / Kec: {{$data['customer']?$data['customer']['vilage']['district']['name']:''}}</td>
                        </tr>
                        <tr>
                            <td class="w-30"></td>
                            <td class="w-70 border-bottom">{{$data['customer']?$data['customer']['vilage']['district']['city']['name']:''}} / {{$data['customer']?$data['customer']['vilage']['district']['city']['province']['name']:''}}</td>
                        </tr>
                    </table>
                </td>
                <td class="w-50">
                    <table class="w-100 table-nb">
                        <tr>
                            <td class="w-15"></td>
                            <td class="w-85">
                                <table class="w-100 table-nb">
                                    <tr>
                                        <td class="w-40 text-end">Hari, Tanggal: </td>
                                        <td class="w-60 border-bottom">{{$data['tgl_event']??''}}</td>
                                    </tr>
                                    <tr>
                                        <td class="w-40 text-end">Venue: </td>
                                        <td class="w-60 border-bottom">{{$data['venue']??''}}</td>
                                    </tr>
                                    <tr>
                                        <td class="w-40 text-white">E </td>
                                        <td class="w-60 border-bottom"> </td>
                                    </tr>
                                    <tr>
                                        <td class="w-40 text-end">*) Jumlah Undangan</td>
                                        <td class="w-60 border-bottom">{{$data['total_invite']??''}} Undangan</td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    </table>
                    <table class="w-100 table-nb">
                        <tr class="bg-theme text-white">
                            <td class="w-40 text-end"> Customer Service</td>
                            <td class="w-60">{{$data['petugas']['name']??''}} </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
        <table class="table w-100 table-theme text-center">
            <tr class="bg-theme text-white">
                <td>Tipe Pesanan</td>
                <td>Jumlah Pesanan</td>
                <td>Keterangan</td>
                <td>Waktu</td>
                <td>Tema Event</td>
                <td>Warna Taplak</td>
            </tr>
            <tr>
                <td>{{$data['package_type']?ucwords(strtolower($data['package_type'])):''}}</td>
                <td>{{$data['total_guest']??''}} Porsi</td>
                <td>{{$data['keterangan']??''}}</td>
                <td>{{$data['event_time']??''}}</td>
                <td>{{$data['event_type']?ucwords(strtolower($data['event_type'])):''}}</td>
                <td>-</td>
            </tr>
        </table>
    </div>
    <div class="table-section bill-tbl w-100 mt-10 table-theme">
        <table class="table w-100">
            <tr class="bg-theme text-white">
                <td class="w-50 text-center">
                    Menu
                </td>
                <td class="w-50 text-center">
                    Detail Pesanan
                </td>
            </tr>
            <tr class="v-top">
                <td class="h-400">
                    <p class="m-0 mt-10"><b>MENU</b></p>
                    @foreach($data['ref_item'] as $kategori=>$menus)
                        <!-- <p class="m-0 mt-10">{ {$kategori} }</p> -->
                        <table class="table-nb">
                            @php $row=1; $type=''; @endphp
                            @foreach($menus as $menu)
                                <tr>
                                    <td>{{$row}} {{$menu['menu']? ucwords(strtolower($menu['menu']['name'])):''}} </td>
                                    @php $row++;  @endphp
                                </tr>
                            @endforeach
                        </table>
                    @endforeach

                </td>
                <td class="h-400">{!!$data['desc']!!}</td>
            </tr>
        </table>
    </div>
    <p class="m-0 mt-10">*Menu bisa berubah maksimal H-12 hari</p>
    <p class="m-0">*Jumlah pesanan tidak bisa dikurangi, namun bisa ditambah maksimal h-7 hari</p>
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
        @if($internal)
        <table class="table w-100 mt-10 table-theme">
            <tr>
                <td class=" h-100 v-top" >
                    {!!$data['desc_extra']!!}
                </td>
            </tr>
        </table>
        @endif
    </div>
@endsection