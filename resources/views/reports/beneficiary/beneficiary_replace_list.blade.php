<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>উপকারভোগীর প্রস্থান তালিকা</title>

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

        .border-table th {
            border: 1px solid #dddddd;
            text-align: center;
            background-color: #d1d1d1;
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
        @page {
            header: page-header;
            footer: page-footer;
        }
    </style>
</head>
<body>

<div class="title-container">
    <!-- Empty div for the first table -->
</div>

<table border="none" width="100%">
    <tbody>
    <tr>
        <td width="33%" class="left">
            <img src="{{ public_path('image/bangladesh-govt-logo.png') }}" alt="Left Image"
                 style="width: 100px; height: auto;">
        </td>
        <td width="33%" align="center" class="center">
            <h3>গণপ্রজাতন্ত্রী বাংলাদেশ সরকার</h3>
            <h4>সমাজসেবা অধিদফতর</h4>
            <h5>ক্যাশ ট্রান্সফার মডার্নাইজেশন (সিটিএম) প্রকল্প</h5>
            <h6>শ্যামলী স্কোয়ার, ২৪/১-২, মিরপুর রোড, ঢাকা -১২০৭</h6>
            <p>www.dss.gov.bd</p>
            <br />
            <h2>উপকারভোগীর পরিবর্তন</h2>
        </td>
        <td width="33%" class="right"><img src="{{ public_path('image/logo.png') }}" alt="Right Image"
                                           style="width: 80px; height: 80px;"></td>
    </tr>
    </tbody>
</table>

<table class="border-table">
    <thead>
    <tr>
        <th rowspan="2">ক্রমিক নং</th>
        <th rowspan="2">প্রোগ্রাম নাম</th>
        <th colspan="4">প্রস্থানকৃত  উপকারভোগী</th>
        <th colspan="4">সংযোজনকৃত উপকারভোগী</th>
        <th rowspan="2">পরিবর্তনের তারিখ</th>
    </tr>
    <tr>
        <th>উপকারভোগীর আইডি</th>
        <th>উপকারভোগীর নাম</th>
        <th>পিতার নাম</th>
        <th>জেলা</th>
        <th>উপকারভোগীর আইডি</th>
        <th>উপকারভোগীর নাম</th>
        <th>পিতার নাম</th>
        <th>জেলা</th>
    </tr>
    </thead>
    <tbody>
    @foreach($beneficiaries as $index => $beneficiary)
        <tr>
            <td>{{\App\Facades\BengaliUtil::bn_number($index + 1)}}</td>
            <td>{{$beneficiary?->program_name_bn}}</td>

            <td>{{$beneficiary?->application_id}}</td>
            <td>{{$beneficiary?->name_bn}}</td>
            <td>{{$beneficiary?->father_name_bn}}</td>
            <td>{{$beneficiary?->district_name_bn}}</td>

            <td>{{$beneficiary?->replace_with_application_id}}</td>
            <td>{{$beneficiary?->replace_with_name_bn}}</td>
            <td>{{$beneficiary?->replace_with_father_name_bn}}</td>
            <td>{{$beneficiary?->replace_with_district_name_bn}}</td>

            <td>{{\App\Facades\BengaliUtil::bn_date(\Illuminate\Support\Carbon::parse($beneficiary->cause_date)->format('d/m/Y'))}}</td>
        </tr>
    @endforeach
    </tbody>
</table>

<htmlpagefooter name="page-footer">
    <table width="100%">
        <tr>
            <td width="33%">প্রিন্টঃ {{\App\Facades\BengaliUtil::bn_date_time(\Illuminate\Support\Carbon::now()->format('j F Y h:i A'))}}</td>
            <td width="33%" align="center">{{\App\Facades\BengaliUtil::bn_number('{PAGENO}/{nbpg}')}}</td>
            <td width="33%" style="text-align: right;">রিপোর্ট প্রস্তুতকারীঃ {{$generated_by}}{{$assign_location}}</td>
        </tr>
    </table>
</htmlpagefooter>

</body>
</html>
