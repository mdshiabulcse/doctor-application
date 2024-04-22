<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="Mark Otto, Jacob Thornton, and Bootstrap contributors">
    <meta name="generator" content="Hugo 0.104.2">
    <title>{{$pageTitle}}</title>
    <link href="{{asset('assets/bootstrap/css/bootstrap.min.css')}}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans&family=Noto+Sans+Bengali:wght@300&display=swap" rel="stylesheet">
    <style>
        body {
            background-color: white; /* Set background color to white for printing */
            margin: 0 0 0 30px; /* Remove default margin for printing */
            padding: 0; /* Remove default padding for printing */
            font-family: 'Noto Sans', sans-serif;
            font-family: 'Noto Sans Bengali', sans-serif;
            font-size: 14px;
        }


        @media print {
            #pagePrintNone {
                display: none;
            }

            @page {
                size: A4;
            }
            font-family: 'Noto Sans', sans-serif;
            font-family: 'Noto Sans Bengali', sans-serif;
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
<main>
<table>
    <thead>
    <div class="border">
        <div class="row">
            <div class="col">
                <div class=" p-2 border-end " style="line-height: 2px">
                    <p class="text-uppercase"><svg id="barcode"></svg></p>
                    <p class="text-uppercase"><strong>Name: {{$prescription_data->patient_info->patient_name .' ['.$prescription_data->id.']'}}  </strong></p>
                    <p class="text-uppercase"><strong>Age: {{\Carbon\Carbon::parse($prescription_data->patient_info->patient_dob )->diff(\Carbon\Carbon::now())->format('%y years, %m months and %d days')}} </strong></p>
                    <p class="text-uppercase"><strong>Phone: {{$prescription_data->patient_info->patient_phone }} </strong></p>
                </div>
            </div>
            <div class="col">
                <p class="text-uppercase"><strong>{{$prescription_data->doctor_info->doctor_name }}  </strong></p>
                <span style="line-height: 2px">{!!  $prescription_data->doctor_info->doctor_details !!}</span>
            </div>
        </div>
        </div>
    </div >
    <div class="border-bottom ">
        <div class="row">
            <div class="col"><p>Date: <strong>{{date('Y-m-d H:i a',strtotime($prescription_data->created_at))}}</strong></p></div>
            <div class="col"></div>
        </div>
    </div>

    </thead>
    <tbody>
    <table>
        <tr>
            <td>
                <div class="row">
                    <div class="col-6">
                        <p><strong>Symptoms:</strong> {{$prescription_data->symptoms}}</p>
                    </div>
                    <div class="col-6"></div>
                </div>
            </td>
        </tr>
    </table>
    </tbody>
</table>
</main>
<script src="{{asset('/')}}assets/bootstrap/js/bootstrap.bundle.js"></script>
<script src="{{asset('/')}}assets/bootstrap/js/fontawesome.min.js"></script>
<script src="{{asset('/')}}assets/barcode/JsBarcode.all.min.js"></script>
<script >
    var patientId = '{{ $prescription_data->patient_id }}';
    JsBarcode('#barcode', patientId, {height:30,width:1, format: 'CODE128', textPosition: "top", fontSize: 16, marginTop: 15 }).render();
</script>
</body>
</html>
