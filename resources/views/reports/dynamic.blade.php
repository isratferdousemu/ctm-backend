<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ $fileName }}</title>

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
    </style>
</head>
<body>

<p>তালিকাভুক্ত  রিপোর্ট | সমাজসেবা অধিদফতর </p>

<div class="title-container">
    <!-- Empty div for the first table -->
</div>

<table style="border: none;">
    <tbody>
    <tr>
        <td class="left">
            <img src="{{ public_path('image/bangladesh-govt-logo.png') }}" alt="Left Image" style="width: 100px; height: auto;">
        </td>
        <td class="center">
            <h3 class="title">
                গণপ্রজাতন্ত্রী বাংলাদেশ সরকার <br>
                সমাজসেবা অধিদফতর
            </h3>
            <p style="font-size:15px" class="center">ক্যাশ ট্রান্সফার মডার্নাইজেশন (সিটিএম) প্রকল্প</p>
            <p style="font-size:12px">শ্যামলী স্কোয়ার, ২৪/১-২, মিরপুর রোড, ঢাকা -১২০৭</p>
            <a target="_blank" href="https://dss.gov.bd/">www.dss.gov.bd</a>
        </td>
        <td class="right">  <img src="{{ public_path('image/logo.png') }}" alt="Right Image" style="width: 80px; height: 80px;"></td>
    </tr>
    </tbody>
</table>

<table class="border-table">
    <thead>

    <tr>
        {{--        <th style="width: 10%;">ক্রমিক নং </th>--}}

        @foreach($headerInfo as $Key => $chunkList)
            <th>{{ $chunkList }}</th>
        @endforeach
    </tr>
    </thead>
    <tbody>

    @foreach($dataInfo as $key => $data)
        <tr>
            @foreach($data as $Key => $chunkList)
                <td>{{ $chunkList }}</td>
            @endforeach

        </tr>
    @endforeach

    </tbody>

</table>

<div class="footer">
    Copyright &copy; 2024, DSS  <div align="right"><b>{PAGENO} / {nbpg}</b></div>
</div>

</body>
</html>