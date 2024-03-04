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
        .center{
             text-align: center;
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
  

<p>{{ $request['language'] == 'en' ? "Listed Report |Department of Social Services" : "তালিকাভুক্ত  রিপোর্ট | সমাজসেবা অধিদফতর" }} </p>

<div class="title-container">
    <!-- Empty div for the first table -->
</div>




<table style="border: none;">
    <tbody>
        <tr>
           <td class="left">
    <img src="{{ public_path('image/bangladesh-govt-logo.png') }}" alt="Left Image" style="width: 100px; height: auto;">
   
</td>
</td>
           @if($request['language'] == "en")
            <td class="center">
                <h3 class="title">
                    Government of the People's Republic of Bangladesh <br>
                    Department of Social Services
                </h3>
                <p style="font-size:15px" class="center">Cash Transfer Modernization(CTM)Project</p>
                <p style="font-size:12px">Social Service Building, E-8/B-1, Agargaon, Sherbangla Nagar, Dhaka-1207, Bangladesh.</p>
                <a target="_blank" href="https://dss.gov.bd/">www.dss.gov.bd</a>
            </td>
        @else
            <td class="center">
                <h3 class="title">
                    গণপ্রজাতন্ত্রী বাংলাদেশ সরকার <br>
                    সমাজসেবা অধিদফতর
                </h3>
                <p style="font-size:15px" class="center">ক্যাশ ট্রান্সফার মডার্নাইজেশন (সিটিএম) প্রকল্প</p>
                <p style="font-size:12px">সমাজসেবা ভবন, ই-৮/বি-১, আগারগাঁও, শেরেবাংলা নগর, ঢাকা-১২০৭, বাংলাদেশ।</p>
                <a target="_blank" href="https://dss.gov.bd/">www.dss.gov.bd</a>
            </td>
        @endif
            <td class="right">  <img src="{{ public_path('image/logo.png') }}" alt="Right Image" style="width: 80px; height: 80px;"></td>
        </tr>
    </tbody>
</table>
 
<table style="width: 100%; border-collapse: collapse;margin-left:40px;">
 <tbody>
     <tr>
    <td class="left" style="width: 40%;font-size: 20px;">
         {{$request['program']}}
    </td>
 <td class="left" style="width: 60%;font-size: 20px;">
     <span class="right">:</span> {{ $request['language'] == 'bn' ? $data->program->name_bn : $data->program->name_en }}
    <!-- Notice the space character before the Blade directive -->
</td>
 <td class="right" style="width: 30%; font-size: 20px;"></td>

</tr>
     <tr>
    <td class="left" style="width: 40%;font-size: 20px;">
      {{$request['application']}}
    </td>
 <td class="left" style="width: 60%;font-size: 20px;">
     <span class="right">:</span>  {{ $request['language'] == 'bn' ? $data->application_id : $data->application_id }}
     
    <!-- Notice the space character before the Blade directive -->
</td>
    <td class="right" style="width: 30%; font-size: 20px;"></td>
</tr>
    


        
    </tbody>
</table>
<div class="center" style="text-decoration: underline;margin-left: 40px;font-size: 20px;"> <b> {{$request['personal_info']}}</b></div>
  
<table style="width: 100%; border-collapse: collapse;margin-left:40px;">
 <tbody>
     <tr>
    <td class="left" style="width: 40%;font-size: 25px;">
        {{$request['name_en']}}
    </td>
 <td class="left" style="width: 60%;font-size: 25px;">
     <span class="right">:</span> {{  $data->name_en }}
    <!-- Notice the space character before the Blade directive -->
</td>
   <td class="center" style="width: 30%; font-size: 25px;" rowspan="5">
   <div style="text-decoration: underline;">
<img src="{{ $image }}" alt="Your Image">
</div>
 

  <div style="font-size: 20px; ">{{ $request['language'] == 'en' ? "Image" : "ছবি" }}</div>
</td>

</tr>
     <tr>
    <td class="left" style="width: 40%;font-size: 25px;">
        {{$request['name_bn']}}
    </td>
 <td class="left" style="width: 60%;font-size: 25px;">
     <span class="right">:</span> {{  $data->name_bn }}
    <!-- Notice the space character before the Blade directive -->
</td>
    <td class="right" style="width: 30%; font-size: 25px;"></td>
</tr>
    <tr>
    <td class="left" style="width: 40%;font-size: 25px;">
        {{$request['nid']}}
    </td>
 <td class="left" style="width: 60%;font-size: 25px;">
     <span class="right">:</span> {{  $data->verification_number }}
    <!-- Notice the space character before the Blade directive -->
</td>

</tr>
  <tr>
    <td class="left" style="width: 40%;font-size: 25px;">
        {{$request['nationality']}}
    </td>
 <td class="left" style="width: 60%;font-size: 25px;">
     <span class="right">:</span> {{  $data->nationality }}
    <!-- Notice the space character before the Blade directive -->
</td>

</tr>
    <tr>
    <td class="left" style="width: 40%;font-size: 25px;">
        {{$request['mobile']}}
    </td>
 <td class="left" style="width: 60%;font-size: 25px;">
     <span class="right">:</span> {{  $data->mobile }}
    <!-- Notice the space character before the Blade directive -->
</td>

</tr>

  <tr>
    <td class="left" style="width: 40%;font-size: 25px;">
        {{$request['date_of_birth']}}
    </td>
 <td class="left" style="width: 60%;ffont-size: 25px;">
     <span class="right">:</span> {{  $data->date_of_birth }}
    <!-- Notice the space character before the Blade directive -->
</td>

</tr>
  <tr>
    <td class="left" style="width: 40%;font-size: 25px;">
        {{$request['father_name_en']}}
    </td>
 <td class="left" style="width: 60%;font-size: 25px;">
     <span class="right">:</span> {{  $data->father_name_en }}
    <!-- Notice the space character before the Blade directive -->
</td>
   
</tr>
  <tr>
    <td class="left" style="width: 40%;font-size: 25px;">
        {{$request['father_name_bn']}}
    </td>
 <td class="left" style="width: 60%;font-size: 25px;">
     <span class="right">:</span> {{  $data->father_name_bn }}
    <!-- Notice the space character before the Blade directive -->
</td>
   
</tr>
  <tr>
    <td class="left" style="width: 40%;font-size: 25px;">
        {{$request['mother_name_en']}}
    </td>
 <td class="left" style="width: 60%;font-size: 25px;">
     <span class="right">:</span> {{  $data->mother_name_en }}
    <!-- Notice the space character before the Blade directive -->
</td>
 
</tr>
  <tr>
    <td class="left" style="width: 40%;font-size: 25px;">
        {{$request['mother_name_bn']}}
    </td>
 <td class="left" style="width: 60%;font-size: 25px;">
     <span class="right">:</span> {{  $data->mother_name_bn }}
    <!-- Notice the space character before the Blade directive -->
</td>
    
</tr>
  <tr>
    <td class="left" style="width: 40%;font-size: 25px;">
        {{$request['marital_status']}}
    </td>
 <td class="left" style="width: 60%;font-size: 25px;">
     <span class="right">:</span> {{  $data->marital_status }} @if($data->marital_status == 'Married')
      , &nbsp; {{ $request['language'] == 'bn' ? $request['spouse_name_bn'] : $request['spouse_name_en'] }}: {{ $data->spouse_name_en }}
@endif
    <!-- Notice the space character before the Blade directive -->
</td>
    
</tr>



        
    </tbody>
</table>
<div class="center" style="text-decoration: underline;margin-left: 40px;font-size: 20px;"> <b> {{$request['present_address']}}</b></div>
  
<table style="width: 100%; border-collapse: collapse;margin-left:40px;">
 <tbody>
     <tr>
    <td class="left" style="width: 40%;font-size: 20px;">
        {{$request['division']}}
    </td>
 <td class="left" style="width: 60%;font-size: 20px;">
     <span class="right">:</span> @if($data->current_location->location_type == '1')
    {{ $request['language'] == 'bn' ? $data->current_location->parent->parent->parent->name_bn : $data->current_location->parent->parent->parent->name_en }}
@endif

@if($data->current_location->location_type == '2' || $data->current_location->location_type == '3')
{{ $request['language'] == 'bn' ?$data->current_location->parent->parent->parent->parent->name_bn  : $data->current_location->parent->parent->parent->parent->name_en  }}

@endif
    <!-- Notice the space character before the Blade directive -->
</td>
 <td class="right" style="width: 30%; font-size: 20px;"></td>

</tr>
     <tr>
    <td class="left" style="width: 40%;font-size: 20px;">
        {{$request['district']}}
    </td>
 <td class="left" style="width: 60%;font-size: 20px;">
     <span class="right">:</span> @if($data->current_location->location_type == '1')
    {{ $request['language'] == 'bn' ? $data->current_location->parent->parent->name_bn : $data->current_location->parent->parent->name_en }}
@endif

@if($data->current_location->location_type == '2' || $data->current_location->location_type == '3')
{{ $request['language'] == 'bn' ?$data->current_location->parent->parent->parent->name_bn  : $data->current_location->parent->parent->parent->name_en  }}

@endif
    <!-- Notice the space character before the Blade directive -->
</td>
    <td class="right" style="width: 30%; font-size: 20px;"></td>
</tr>
 <tr>
    <td class="left" style="width: 40%;font-size: 20px;">
        {{$request['location']}}
    </td>
 <td class="left" style="width: 60%;font-size: 20px;">
     <span class="right">:</span> 
     @if($data->current_location->location_type == '1')
    {{ $request['language'] == 'bn' ? $data->current_location->parent->name_bn : $data->current_location->parent->name_en }}
@endif

@if($data->current_location->location_type == '2' || $data->current_location->location_type == '3')
{{ $request['language'] == 'bn' ?$data->current_location->parent->parent->name_bn  : $data->current_location->parent->parent->name_en  }}

@endif 
      
    <!-- Notice the space character before the Blade directive -->
</td>

</tr>
    <tr>
    <td class="left" style="width: 40%;font-size: 20px;">
        {{$request['union_pouro_city']}}
    </td>
 <td class="left" style="width: 60%;font-size: 20px;">
     <span class="right">:</span> @if($data->current_location->location_type == '2' || $data->current_location->location_type == '3')
{{ $request['language'] == 'bn' ?$data->current_location->parent->name_bn  : $data->current_location->parent->name_en  }}

@endif
    <!-- Notice the space character before the Blade directive -->
</td>

</tr>
  <tr>
    <td class="left" style="width: 40%;font-size: 20px;">
        {{$request['ward']}}
    </td>
 <td class="left" style="width: 60%;font-size: 20px;">
     <span class="right">:</span>    {{ $request['language'] == 'bn' ?$data->current_location->name_bn   : $data->current_location->name_en   }}  
    <!-- Notice the space character before the Blade directive -->
</td>

</tr>     
    </tbody>
</table>
<div class="center" style="text-decoration: underline;margin-left: 40px;font-size: 20px;"> <b> {{$request['permanent_address']}}</b></div>
  
<table style="width: 100%; border-collapse: collapse;margin-left:40px;">
 <tbody>
          <tr>
    <td class="left" style="width: 40%;font-size: 20px;">
        {{$request['division']}}
    </td>
 <td class="left" style="width: 60%;font-size: 20px;">
     <span class="right">:</span> @if($data->permanent_location->location_type == '1')
    {{ $request['language'] == 'bn' ? $data->permanent_location->parent->parent->parent->name_bn : $data->permanent_location->parent->parent->parent->name_en }}
@endif

@if($data->permanent_location->location_type == '2' || $data->permanent_location->location_type == '3')
{{ $request['language'] == 'bn' ?$data->permanent_location->parent->parent->parent->parent->name_bn  : $data->permanent_location->parent->parent->parent->parent->name_en  }}

@endif
    <!-- Notice the space character before the Blade directive -->
</td>
 <td class="right" style="width: 30%; font-size: 20px;"></td>

</tr>
     <tr>
    <td class="left" style="width: 40%;font-size: 20px;">
        {{$request['district']}}
    </td>
 <td class="left" style="width: 60%;font-size: 20px;">
     <span class="right">:</span>  @if($data->permanent_location->location_type == '1')
    {{ $request['language'] == 'bn' ? $data->permanent_location->parent->parent->name_bn : $data->permanent_location->parent->parent->name_en }}
@endif

@if($data->permanent_location->location_type == '2' || $data->permanent_location->location_type == '3')
{{ $request['language'] == 'bn' ?$data->permanent_location->parent->parent->parent->name_bn  : $data->permanent_location->parent->parent->parent->name_en  }}

@endif
    <!-- Notice the space character before the Blade directive -->
</td>
    <td class="right" style="width: 30%; font-size: 20px;"></td>
</tr>
    <tr>
    <td class="left" style="width: 40%;font-size: 20px;">
        {{$request['location']}}
    </td>
 <td class="left" style="width: 60%;font-size: 20px;">
     <span class="right">:</span> 
     @if($data->permanent_location->location_type == '1')
    {{ $request['language'] == 'bn' ? $data->permanent_location->parent->name_bn : $data->permanent_location->parent->name_en }}
@endif

@if($data->permanent_location->location_type == '2' || $data->permanent_location->location_type == '3')
{{ $request['language'] == 'bn' ?$data->permanent_location->parent->parent->name_bn  : $data->permanent_location->parent->parent->name_en  }}

@endif 
      
    <!-- Notice the space character before the Blade directive -->
</td>

</tr>
    <tr>
    <td class="left" style="width: 40%;font-size: 20px;">
        {{$request['union_pouro_city']}}
    </td>
 <td class="left" style="width: 60%;font-size: 20px;">
     <span class="right">:</span> @if($data->permanent_location->location_type == '2' || $data->permanent_location->location_type == '3')
{{ $request['language'] == 'bn' ?$data->permanent_location->parent->name_bn  : $data->permanent_location->parent->name_en  }}

@endif
    <!-- Notice the space character before the Blade directive -->
</td>

</tr>
  <tr>
    <td class="left" style="width: 40%;font-size: 20px;">
        {{$request['ward']}}
    </td>
 <td class="left" style="width: 60%;font-size: 20px;">
     <span class="right">:</span>  {{ $request['language'] == 'bn' ?$data->permanent_location->name_bn   : $data->permanent_location->name_en   }}  
    <!-- Notice the space character before the Blade directive -->
</td>

</tr> 



        
    </tbody>
</table>
<div class="center" style="text-decoration: underline;margin-left: 40px;font-size: 20px;"> <b> {{$request['nominee_info']}}</b></div>
  
<table style="width: 100%; border-collapse: collapse;margin-left:40px;">
 <tbody>
          <tr>
    <td class="left" style="width: 40%;font-size: 30px;">
        {{$request['nominee_en']}}
    </td>
    <td class="left" style="width: 60%;font-size: 30px;">
     <span class="right">:</span> {{$data->nominee_en}}
    <!-- Notice the space character before the Blade directive -->
</td>

 <td class="center" style="width: 30%; font-size: 30px;" rowspan="3">
    <div style="text-decoration: underline;">
        <img src="{{ $nominee_image }}" alt="Your Image" >
    </div>
   <div style="font-size: 20px; "class="center" >{{ $request['language'] == 'en' ? "Image" : "ছবি" }} 
</div>
   
</td>
 <td class="center" style="width: 60%;font-size: 30px;">
  <div style="text-decoration: underline;">
        <img src="{{ $nominee_signature }}" alt="Your Image"  style="width: 300px; height: 200px;">
    </div>
   <div style="font-size: 20px; ">{{ $request['language'] == 'en' ? "Signature" : "স্বাক্ষর" }} 
</div>
   
    <!-- Notice the space character before the Blade directive -->
</td>

</tr>
     <tr>
    <td class="left" style="width: 40%;font-size: 30px;">
        {{$request['nid']}}
    </td>
 <td class="left" style="width: 60%;font-size: 30px;">
     <span class="right">:</span> {{$data->nominee_verification_number}}
    <!-- Notice the space character before the Blade directive -->
</td>
    <td class="right" style="width: 30%; font-size: 30px;"></td>
</tr>
  <tr>
    <td class="left" style="width: 40%;font-size: 30px;">
        {{$request['date_of_birth']}}
    </td>
 <td class="left" style="width: 60%;font-size: 30px;">
     <span class="right">:</span>  {{$data->nominee_date_of_birth}}     
    <!-- Notice the space character before the Blade directive -->
</td>

</tr>
   <tr>
    <td class="left" style="width: 40%;font-size: 30px;">
        {{$request['relationship']}}
    </td>
 <td class="left" style="width: 60%;font-size: 30px;">
     <span class="right">:</span>  {{$data->nominee_relation_with_beneficiary}}     
    <!-- Notice the space character before the Blade directive -->
</td>

</tr>
    <tr>
    <td class="left" style="width: 40%;font-size: 30px;">
        {{$request['nominee_address']}}
    </td>
 <td class="left" style="width: 60%;font-size: 30px;">
     <span class="right">:</span>  {{$data->nominee_address}}     
    <!-- Notice the space character before the Blade directive -->
</td>
<td class="right" style="width: 30%; font-size: 30px;" rowspan="5">
 
</td>

</tr>

 
    
        
    </tbody>
</table>
<div class="center" style="text-decoration: underline;margin-left: 40px;font-size: 20px;"> <b> {{$request['bank_info']}}</b></div>
  
<table style="width: 100%; border-collapse: collapse;margin-left:40px;">
 <tbody>
     <tr>
    <td class="left" style="width: 40%;font-size: 20px;">
        {{$request['account_ownership']}}
    </td>
 <td class="left" style="width: 60%;font-size: 20px;">
     <span class="right">:</span> 
    {{$data->account_owner }}


    <!-- Notice the space character before the Blade directive -->
</td>
 <td class="right" style="width: 30%; font-size: 20px;"></td>

</tr>
     <tr>
    <td class="left" style="width: 40%;font-size: 20px;">
        {{$request['account_no']}}
    </td>
 <td class="left" style="width: 60%;font-size: 20px;">
     <span class="right">:</span>
    {{  $data->account_number }}

    <!-- Notice the space character before the Blade directive -->
</td>
    <td class="right" style="width: 30%; font-size: 20px;"></td>
</tr>
  <tr>
    <td class="left" style="width: 40%;font-size: 20px;">
        {{$request['bank_name']}}
    </td>
 <td class="left" style="width: 60%;font-size: 20px;">
     <span class="right">:</span>
 {{ $data->bank_name ?? "N/A" }}

    <!-- Notice the space character before the Blade directive -->
</td>
    <td class="right" style="width: 30%; font-size: 20px;"></td>
</tr>
  <tr>
    <td class="left" style="width: 40%;font-size: 20px;">
        {{$request['branch_name']}}
    </td>
 <td class="left" style="width: 60%;font-size: 20px;">
     <span class="right">:</span>
    {{ $data->branch_name ?? "N/A" }}

    <!-- Notice the space character before the Blade directive -->
</td>
    <td class="right" style="width: 30%; font-size: 20px;"></td>
</tr>
<tr>
    <td class="left" style="width: 40%;font-size: 30px;">
   
    </td>
     <td class="left" style="width: 40%;font-size: 30px;">
   
    </td>
     <td class="center" style="width: 30%; font-size: 25px;" rowspan="5">
    <div style="text-decoration: underline;">
        <img src="{{ $signature }}" alt="Your Image" style="width: 200px; height: 200px;">
    </div>
   <div style="font-size: 20px; border-bottom: 1px solid black;">{{ $request['language'] == 'en' ? "Date" : "তারিখ" }} :
{{ $request['language'] == 'en' ? $data->created_at->toDateString() :  \App\Helpers\Helper::englishToBangla($data->created_at->toDateString()) }}</div>
   
</td>



</tr>
 
     
    </tbody>
</table>






<htmlpageheader name="page-header">
</htmlpageheader>

<htmlpagefooter name="page-footer">
    <div class="footer">
    {{ $request['language'] == 'en' ? "Copyright @, " . date("Y ") . ", DSS" : "কপিরাইট @, " . \App\Helpers\Helper::englishToBangla(date("Y ")) . ", ডিএসএস" }}
   
</div>
</htmlpagefooter>

</body>
</html>
