<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>উপকারভোগীর তালিকা</title>

    <style>
        body {
            font-family: 'kalpurush' !important;
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .title-container {
            text-align: center;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px; /* Add margin to separate tables */
        }

        table.img-table img {
            width: 30px; /* Adjust the width of your images */
            height: auto;
        }

        td {
            border: none;
        }

        .border-table td {
            border: 1px solid #dddddd;
            text-align: left;
            padding: 8px;
            font-size: 14px;
        }

        .left {
            text-align: left;
        }

        .center {
            text-align: center;
        }

        .right {
            text-align: right;
        }

        .title {
            font-size: 20px;
            margin: 0; /* Remove default margin */
        }

        .footer {
            position: fixed;
            bottom: 0;
            width: 100%;
            text-align: center;
        }
        /*@page {*/
        /*    header: page-header;*/
        /*    footer: page-footer;*/
        /*}*/
    </style>
</head>
<body>

<p>উপকারভোগীর তালিকা | সমাজসেবা অধিদফতর </p>

<div class="title-container">
    <!-- Empty div for the first table -->
</div>

<table style="border: none;">
    <tbody>
    <tr>
        <td class="left">
            <img src="{{ public_path('image/bangladesh-govt-logo.png') }}" alt="Left Image"
                 style="width: 100px; height: auto;">
        </td>
        <td class="center">
            <h2 class="title">
                সমাজসেবা অধিদফতর <br>
                সামাজিক নিরাপত্তা কর্মসূচি
            </h2>
        </td>
        <td class="right"><img src="{{ public_path('image/logo.png') }}" alt="Right Image"
                               style="width: 80px; height: 80px;"></td>
    </tr>
    </tbody>
</table>

<table class="border-table">
    <thead>
    <tr>
        <th style="width: 10%;">ক্রমিক নং</th>
        <th>উপকারভোগীর আইডি</th>
        <th>উপকারভোগীর নাম</th>
        <th>পিতার নাম</th>
        <th>প্রোগ্রাম নাম</th>
        <th>জেলা</th>
        <th>সিটি / জেলা পৌর / উপজেলা</th>
        <th>থানা /ইউনিয়ন /পৌর</th>
        <th>ওয়ার্ড</th>
        <th>একাউন্ট নং</th>
        <th>মাসিক ভাতা (টাকা)</th>
    </tr>
    </thead>
    <tbody>
    @foreach($beneficiaries as $index => $beneficiary)
        <tr>
            <td>{{$index + 1}}</td>
            <td>{{$beneficiary->application_id}}</td>
            <td>{{$beneficiary->name_en}}</td>
            <td>{{$beneficiary->father_name_en}}</td>
            <td>{{$beneficiary->program?->name_en}}</td>
            <td>{{$beneficiary->permanentDistrict?->name_en}}</td>
            <td>{{$beneficiary->permanentUpazila?->name_en}}</td>
            <td>{{$beneficiary->permanentUnion?->name_en}}</td>
            <td>{{$beneficiary->permanentWard?->name_en}}</td>
            <td>{{$beneficiary->account_number}}</td>
            <td>700</td>
        </tr>
    @endforeach
    </tbody>
</table>
{{--<html-separator/>--}}
{{--<htmlpagefooter name="page-footer">--}}
{{--    <p style="text-align: center;">{PAGENO}</p>--}}
{{--</htmlpagefooter>--}}
<div class="footer">
    Copyright &copy; 2024, DSS
</div>

</body>
</html>
