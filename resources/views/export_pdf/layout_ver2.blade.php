<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{$title}}</title>
</head>
<style type="text/css">
    
    @page {
        margin: 210px 25px 60px 25px; /* TOP margin harus lebih besar dari tinggi header */
    }

    header {
        position: fixed;
        top: -190px; /* = -(margin-top - sedikit offset) */
        left: 0px;
        right: 0;
        height: 80px;
    }

    #watermark {
        position: fixed;
        top: 25%;
        width: 100%;
        text-align: center;
        opacity: .1;
        transform: rotate(320deg);
        transform-origin: 50% 50%;
        z-index: -1000;
        color: red;
        font-size: 120px;
    }
    body{
        font-family: 'Roboto Condensed', sans-serif;
    }
    .m-0{
        margin: 0px;
    }
    .p-0{
        padding: 0px;
    }
    .pt-5{
        padding-top:5px;
    }
    .mt-10{
        margin-top:10px;
    }
    .mt-80{
        margin-top:80px;
    }
    .mb-40{
        margin-bottom: 40px;
    }
    .mb-10{
        margin-bottom: 10px;
    }
    .text-center{
        text-align:center !important;
    }
    .w-100{
        width: 100%;
    }
    .w-50{
        width:50%;   
    }
    .w-75{
        width:75%;   
    }
    .w-70{
        width:70%;   
    }
    .w-25{
        width:25%;   
    }
    .w-80{
        width:80%;   
    }
    .w-20{
        width:20%;   
    }
    .w-35{
        width:35%;   
    }
    .w-85{
        width:85%;   
    }
    .w-15{
        width:15%;   
    }
    .w-5{
        width:5%;   
    }
    .w-90{
        width:90%;   
    }
    .w-10{
        width:10%;   
    }
    .w-60{
        width:60%;   
    }
    .w-65{
        width:65%;   
    }
    .w-55{
        width:55%;   
    }
    .w-30{
        width:30%;   
    }
    .logo img{
        width:200px;
        height:60px;        
    }
    .gray-color{
        color:#5D5D5D;
    }
    .text-bold{
        font-weight: bold;
    }
    .border{
        border:1px solid black;
    }
    table tr,
    table th,
    table td{
        border: 1px solid #d2d2d2;
        border-collapse:collapse;
        padding: 3px 5px;
    }
    table tr th{
        background: #F4F4F4;
        font-size:13px;
    }
    table tr td{
        font-size:8px;
    }
    table{
        border-collapse:collapse;
    }
    .box-text p{
        line-height:10px;
    }
    .float-left{
        float:left;
    }
    .total-part{
        font-size:13px;
        line-height:12px;
    }
    .p-4{
        padding: 4px;
    }
    .p-6{
        padding: 6px;
    }
    .p-8{
        padding: 8px;
    }
    .total-right p{
        padding-right:20px;
    }
    .text-end{
        text-align:right !important;
    }
    .no-padding{
        padding: 0px !important;
    }
    .text-left{
        text-align:left !important;
    }
    body{
        /* border: 2px solid #50cd89;
        border-style: dashed;
        padding: 10px; */
        font-size: 8px;
    }
    .table-theme{
        border: 2px solid #4d6a72;
    }
    table.table-nb tr,
    table.table-nb th,
    table.table-nb td{
        border-color: #fff;
    }
    .border-bottom{
        border-bottom: 2px solid #4d6a72 !important;
    }
    .bg-theme{
        background: #4d6a72;
    }
    .bg-theme-second{
        background: #eeeeee;
    }
    .bg-white{
        background: #fff;
    }
    .text-white{
        color: #fff;
    }
    .h-400{
        height: 400px;
    }
    .h-30{
        height: 30px;
    }
    .h-100{
        height: 100px;
    }
    .v-top{
        vertical-align: top;
    }
    .float-left{
        float: left;
    }
</style>
<body>
    @yield('content')
</body>
</html>