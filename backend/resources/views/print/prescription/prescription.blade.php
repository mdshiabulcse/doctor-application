<!DOCTYPE html>
<html lang="english">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="{{ asset('frontend/bootstrap@4.4.1/dist/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans&family=Noto+Sans+Bengali:wght@300&display=swap"
          rel="stylesheet">
    <title>{{$pageTitle}}</title>
    <style>
        body {
            font-family: 'Noto Sans', sans-serif;
            font-family: 'Noto Sans Bengali', sans-serif;
        }

        .page-footer {
            position: fixed;
            left: 0;
            bottom: 0;
            width: 100%;
            color: black;
            text-align: center;
            margin-bottom: -18px;
        }

        .table tr td {
            padding: 0px;
        }

        .table tr th {
            padding: 0px;

        }

        @page {
            size: A4;
            margin-top: 200px;
            margin-bottom: 80px;
            margin-left: 70px;

        }

        @media print {
            #pagePrintNone {
                display: none;
            }

            html, body {
                width: 210mm;
                height: 297mm;
            }

            @page {
                size: A4;
                margin-top: 120px;
                margin-bottom: 90px;
                margin-left: 70px;
            }
        }

        .table-page {
            margin: auto;

        }

        th td {
            font-size: 16px;

        }

        tr td {
            line-height: 26px;

        }


        ul li {
            line-height: 38px;
            font-size: 18px;

        }

        .title {
            font-size: 18px;
            font-weight: bold;
        }
        .patient-barcode{
            text-align: center;
            float: left;
            padding-right: 275px;
        }
        .barcode-id{
           background-color: #fff;
        }

        .barcode-div{
            margin-top: -10px;
        }
        .barcode-img{
            width: 200px; height: 35px; margin-top: 5px
        }
        .patient-span{
            font-weight: bold;
        }
        .doctor-degree{
            font-size: 15px; line-height: 1px
        }

    </style>
</head>


<body>
<div class="card " id="pagePrintNone">
    <div class="card-body">
        <div class="container mb-5 mt-3">
            <div class="row d-flex">
                <div class="col-xl-3 float-end">
                    <button onclick="window.print()" class="btn btn-primary btn-lg"><i class="bi bi-printer"></i> Print
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
<table class="table-page" style="width:100%">
    <thead>
    <tr>
        <td>
            <div class="container">
                <div class="row border" >
                    <div class="col-6 border-right" >
                        <div class="row">
                            <div class="col-12 patient-barcode">
                                <img id="barcode" class="barcode-img"/><br/>
                                <div class="barcode-div">
                                    <span class="barcode-id">{{ @$prescription_data->patient_id }}</span>
                                </div>
                            </div>
                            <div class="col-12">
                                <p>
                                    Name: <span class="title">{{$prescription_data->patient_info->patient_name .' ['.$prescription_data->id.']'}}</span><br/>
                                    Age: <span class="patient-span">{{\Carbon\Carbon::parse($prescription_data->patient_info->patient_dob )->diff(\Carbon\Carbon::now())->format('%y years, %m months and %d days')}}</span><br/>
                                    DOB: <span class="patient-span">{{date('d-m-Y', strtotime(@$prescription_data->patient_info->patient_dob))}}</span>,
                                    Gender: <span class="patient-span">{{$prescription_data->patient_info->gender }}</span><br/>
                                    Phone: <span class="patient-span">{{$prescription_data->patient_info->patient_phone }}</span><br/>
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="col-6" >
                        <p class="title">{{$prescription_data->doctor_info->doctor_name }}</p>
                        <span class="doctor-degree">{!!  $prescription_data->doctor_info->doctor_details !!}</span>
                    </div>


                </div>
                <div class="row d-flex justify-content-between border-bottom" >
                    <div>
                        Date: <strong>{{date('d M Y  h:i A', strtotime(@$prescription_data->created_at))}}</strong>
                    </div>
                </div>
            </div>
        </td>
    </tr>

    </thead>
    <tbody style="font-size: 16px">
    <tr>
        <td>
            <div>
                <div class="container mt-2">
                    <div class="row">
                        <div class="col-md-12 col-12 col-lg-12">
                            <main class=" mt-2 page-content">
                                <table>
                                    <tr>
                                        <td>
                                            <div class="d-flex bd-highlight">
                                                <div class="p-2 flex-fill bd-highlight"><p>
                                                        <strong>Symptoms:</strong> {{$prescription_data->symptoms}}</p>
                                                </div>
                                                <div class="p-2 flex-fill bd-highlight">Flex item</div>
                                                <div class="p-2 flex-fill bd-highlight">Flex item</div>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div class="d-flex bd-highlight">
                                                <div class="p-2 flex-fill bd-highlight">
                                                    <strong>Examination:</strong>
                                                    <ul>
                                                        <li>1. blood test</li>
                                                        <li>1. blood test</li>
                                                        <li>1. blood test</li>
                                                        <li>1. blood test</li>
                                                        <li>1. blood test</li>
                                                        <li>1. blood test</li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                </table>
                                <table class="table">
                                    <thead>
                                    <tr>
                                        <th>Medicine:</th>
                                    </tr>
                                    <tr>
                                        <th scope="col">Sl</th>
                                        <th scope="col">Title</th>
                                        <th scope="col">Duration</th>
                                        <th scope="col">Instruction</th>
                                    </tr>
                                    </thead>
                                    <tbody class="table-group-divider">
                                    @foreach(@$prescription_medicine_data as $prescription_medicine)
                                        <tr>
                                            <th scope="row">1</th>
                                            <td><small class="text-muted">{{$prescription_medicine->prescription_medicine->type}}</small> {{$prescription_medicine->prescription_medicine->medicine_name}}</td>
                                            <td>{{$prescription_medicine->duration}}</td>
                                            <td>{{$prescription_medicine->medicine_instruction}}</td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </main>
                        </div>
                    </div>
                </div>
            </div>
        </td>
    </tr>
    </tbody>
</table>
<!-- -->

<footer class="page-footer">

</footer>
<script src="{{ asset('frontend/jquery/jquery-3.4.1.slim.min.js') }}"></script>
<script src="{{ asset('frontend/bootstrap@4.4.1/dist/js/bootstrap.min.js') }}"></script>
<script src="{{ asset('frontend/js/JsBarcode/EAN_UPC.js') }}"></script>
<script src="{{ asset('frontend/js/JsBarcode/CODE128.js') }}"></script>
<script src="{{ asset('frontend/js/JsBarcode/JsBarcode.js') }}"></script>
<script>
    $("#barcode").JsBarcode("{{ $prescription_data->patient_id }}", {
        width: 1,
        height: 30,
        displayValue: false,
        fontSize: 12
    });
</script>
</body>
</html>
