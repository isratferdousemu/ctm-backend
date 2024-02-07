<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{$title}}/title>

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

        @page {
            header: page-header;
            footer: page-footer;
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
                <h2 class="title">
                    সমাজসেবা অধিদফতর <br>
                    সামাজিক নিরাপত্তা কর্মসূচি
                </h2>
            </td>
            <td class="right">  <img src="{{ public_path('image/logo.png') }}" alt="Right Image" style="width: 80px; height: 80px;"></td>
        </tr>
    </tbody>
</table>
<table style="border: none;">
    <tbody>
        <tr>
            <td class="left"></td>
              <div style="background-color: #ccc; padding: 10px; border-radius: 5px;">
            <td class="center">
              
                  <h2 class="title" style="margin: 0; text-decoration: underline;">
    {{$request['title']}}
</h2>
               
            </td>
             </div>
        </tr>
    </tbody>
</table>
<table style="width: 100%; border-collapse: collapse; border: 1px solid black;">
    <tbody>
      <tr>
    <td class="left" style="width: 20%;  background-color: #ccc;">
        {{$request['program']}} <span class="right">:</span>
    </td>
 <td class="left" style="width: 50%;">
     {{ $request['language'] == 'bn' ? $data->program->first()->name_bn : $data->program->first()->name_en }}
    <!-- Notice the space character before the Blade directive -->
</td>
    <td class="right" style="width: 30%; "></td>
</tr>

        
    </tbody>
</table>




<htmlpageheader name="page-header">
</htmlpageheader>

<htmlpagefooter name="page-footer">
    <div class="footer">
        Copyright &copy; 2024, DSS
    </div>
</htmlpagefooter>

</body>
</html>
