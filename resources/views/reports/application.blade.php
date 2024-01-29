<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>

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
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .left,
        .right {
            width: 50px; /* Adjust the width of your images */
            height: auto;
            margin: 0 10px;
        }

        .title {
            font-size: 20px;
        }

        table {
            font-family: 'kalpurush' !important;
            border-collapse: collapse;
            width: 100%;
        }

        td, th {
            border: 1px solid #dddddd;
            text-align: left;
            padding: 8px;
            font-size: 14px;
        }

        .footer {
            position: fixed;
            bottom: 0;
            width: 100%;
            text-align: center;
        }
        .left {
            width: 50px; /* Adjust the width of your images */
            height: auto;
            margin-right: auto;
            margin-left: 0;
        }

        .right {
            width: 50px; /* Adjust the width of your images */
            height: auto;
            margin-left: auto;
            margin-right: 0;
        }
    </style>
</head>
<body>


<p>তালিকাভুক্ত  রিপোর্ট | সমাজসেবা অধিদফতর </p>


<div class="title-container">
    <img src="{{ public_path('image/bangladesh-govt-logo.png') }}" alt="Left Image" class="left"style="width: 100px; height: 100px;">
    <h2 class="title">
        সমাজসেবা অধিদফতর <br>
        সামাজিক নিরাপত্তা কর্মসূচি
    </h2>
    <img src="{{ public_path('image/logo.png') }}" alt="Right Image" class="right" style="width: 100px; height: 100px;">
</div>
<br>

<table>
    <thead>
    <tr>
        <th style="width: 10%;">ক্রমিক নং </th>
        <th>বিভাগ</th>
    </tr>
    </thead>
    <tbody>
    <tr>
        <td>1</td>
        <td>Division</td>
    </tr>
    <tr>
        <td colspan="6" style="width: 100%; text-align: center">
            <p>
                কোন তথ্য পাওয়া যায়নি
            </p>
        </td>
    </tr>
    </tbody>
</table>

<div class="footer">
    Copyright © 2024, DSS
</div>

</body>
</html>
