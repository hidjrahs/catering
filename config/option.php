<?php

return [
    'app_name' => 'Sistem Catering',
    'version' => '1.0.0',
    'contact' => [
        'email' => 'rofiqahmadfm647@yahoo.com',
        'phone' => '+62 895-3970-66471',
    ],
    'order_status'=>[
        'pending'=>'Pending', 
        'approved'=>'Approved', 
        'purchased'=>'Purchased', 
        'completed'=>'Completed', 
        'cancelled'=>'Cancelled'
    ],
    'education_level'=>[
        'smk'=>'SMK',
        'sma'=>'SMA',
        'diploma'=>'Diploma',
        'sarjana'=>'Sarjana',
        'smp'=>'SMP',
        'mts'=>'MTs',
        'sd'=>'SD',
        'tidak sekolah'=>'Tidak Sekolah',
    ],
    'package_type'=>[
        'buffet'=>'Buffet',
        'gubug'=>'Gubug',
        'breakfast'=>'Breakfast'
    ],
    'event_type'=>[
        'wedding'=>'Wedding',
        'ulang tahun'=>'Ulang Tahun',
        'seminar'=>'Seminar',
        'private'=>'Private',
        'umum'=>'Umum',
    ],
    'pass_default'=>'@default123',
    'packet_default'=>['Paket A', 'Paket B', 'Paket C'],
    'categories_default'=>['SAYURAN / VEGETABLE', 'Masakan Daging', 'Kue', 'Minuman'],
    'units_default'=>['gram','ml','butir','lembar','pcs','box','pack','sisir','klg','ekor','biji','porsi','bin','Bks'],
    'template_keterangan'=>'
    Detail Pesanan<br>
        * Nama 1      :<br>
        * Nama 2     :<br>
        * Rundown acara:<br>
            - 1      :<br>
            - 2     :<br>
    Vendor<br>
    * Vendor Dekorasi           :<br>
    * Vendor Tenda/Slayer   :<br>
    * Vendor MUA                   :<br>
    * Vendor WO                     :<br>
    * Jadwal Loading             :<br>
    * Konsep Acara                :<br>
    * Tone Bunga                    :<br>
    * Meja Kursi                       :<br>
<br>
    * Penataan Buffet 2 Set :<br>
        - 1 Set Umum<br>
        - 1 Set Ruang VIP, 50 Porsi (3 Round Table+ @6 bj Kursi)<br>
    <br>
    * Tanpa Standard Protokol Kesehatan<br>
    * Request<br>
    <br>
    * Noted Testfood:<br>
        - 1<br>
        - 2<br>
<br>
    * Catatan Hasil Technical Meeting:<br>
        - 1<br>
        - 2<br>
    ',
    'template_history_pemesanan'=>'History Pesanan:<br>
        <br>
        <br>
        - 1<br>
        - 2<br>
        - 3<br>
        - 4<br>'
];
