<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="Mark Otto, Jacob Thornton, and Bootstrap contributors">
    <meta name="generator" content="Hugo 0.104.2">
    <title>{{@$invData->invoice_id}}</title>
    <link href="{{asset('/')}}assets/bootstrap/css/bootstrap.min.css" rel="stylesheet" >
    <style>
        body {
            background-color: white; /* Set background color to white for printing */
            margin: 0; /* Remove default margin for printing */
            padding: 0; /* Remove default padding for printing */
        }


        @media print {
            #pagePrintNone {
                display: none;
            }

            @page {
                size: B5;
            }
        }
    </style>
</head>

<body>
<div class="card " id="pagePrintNone">
    <div class="card-body">
        <div class="container mb-5 mt-3">
            <div class="row d-flex align-items-baseline">
                <div class="col-xl-9">
                    <p style="font-size: 20px;">Invoice >> <strong>ID: #{{$invData->invoice_id}}</strong>
                    </p>
                </div>
                <div class="col-xl-3 float-end">
                    <button onclick="window.print()" style="background-color:#60bdf3 ;"
                            class="btn btn-light text-capitalize border-0" data-mdb-ripple-color="dark"><i
                            class="fas fa-print text-primary"></i> Print
                    </button>
                </div>
                <hr>
            </div>
        </div>
    </div>
</div>
<div class="card">
    <div class="card-body">
        <div class="container mb-5 mt-3">
            <div class="container">
                <div class="col-md-12">
                    <div class="text-center">
                        <i class="fab fa-mdb fa-4x ms-0"  ></i>
                        <p class="pt-0">Oriant Dental</p>
                    </div>

                </div>


                <div class="row">
                    <div class="col d-flex flex-row">
                        <ul class="list-unstyled">
                            <li class="">To: <span class="fw-bold">{{$invData->patient_info->patient_name}}</span></li>
                            <li >ID: <span class="fw-bold">{{$invData->patient_info->patient_id}}</span></li>
                            <li >Phone: <span >{{$invData->patient_info->patient_phone}}</span></li>
                            <li >Age: <span >{{\Carbon\Carbon::parse($invData->patient_info->patient_dob)->diff(\Carbon\Carbon::now())->format('%y y, %m m and %d d')}}</span></li>
                            <li >DOB: <span >{{\Carbon\Carbon::parse($invData->patient_info->patient_dob)->format('d-M-Y')}}</span></li>
                        </ul>
                    </div>
                    <div class="col">
                        <ul class="list-unstyled">
                            <li ><i class="fas fa-circle" ></i> <span
                                    class="fw-bold">INVOICE: </span> {{$invData->invoice_id}}
                            </li>
                            <li ><i class="fas fa-circle" ></i> <span
                                    class="fw-bold">Invoice Type: </span> {{$invData->invoice_type}}
                            </li>
                            <li ><i class="fas fa-circle" ></i> <span
                                    class="fw-bold">Creation Date: </span>{{\Carbon\Carbon::parse($invData->inv_create)->format('d-M-Y')}}
                            </li>
                            <li><i class="fas fa-circle" ></i>
                                <span class="me-1 fw-bold">Status:</span><span class="badge {{$invData->status == 'Paid' ? 'bg-success':'bg-warning'}} text-black fw-bold">{{$invData->status}}</span></li>
                        </ul>
                    </div>
                </div>

                <div class="row my-2 mx-1 justify-content-center">
                    <hr>
                    <table class="table table-striped table-borderless">
                        <thead >
                        <tr>
                            <th class="col-1">#</th>
                            <th class="col-8">Description</th>
                            <th class="col-3">Amount</th>
                        </tr>
                        </thead>
                        <tbody>
                        @if($invData->invoice_type == )

                        @else
                            @foreach(@$invInfoDatas as  $invInfoData)
                                <tr>
                                    <th scope="row">{{ $loop->iteration }}</th>
                                    <td>{{ @$invInfoData->exam_data->ex_name }}</td>
                                    <td>{{ @$invInfoData->invoice_item_amount }}</td>
                                </tr>
                            @endforeach
                        @endif

                        </tbody>

                    </table>
                    <hr>
                    <table>
                        <tbody>
                        <tr>
                            <td class="col-3"></td>
                            <td class="col-6">
                                <div class="row">
                                    <ul class="list-unstyled">
                                        <li class=" ms-3"><span class="text-black me-4">SubTotal</span></li>
                                        <li class=" ms-3 mt-2"><span
                                                class="text-black me-4">Discount( {{$invData->discount}}% )</span></li>
                                        <li class=" ms-3 mt-2"><span
                                                class="text-black me-4">Payable Amount</span></li>
                                        <li class=" ms-3 mt-2"><span
                                                class="text-black me-4">Received Amount</span></li>
                                    </ul>
                                </div>
                            </td>
                            <td class="col-3 ">
                                <div class="row">
                                    <ul class="list-unstyled">
                                        <li class=" ms-3"><span class="text-black me-4">{{$invData->subtotal_amount}}</span></li>
                                        <li class=" ms-3 mt-2"><span class="text-black me-4">{{$invData->total_discount_amount}}</span></li>
                                        <li class=" ms-3 mt-2"><span class="text-black me-4 ">{{$invData->paid_amount}}</span>
                                        </li>
                                        <li class=" ms-3 mt-2"><span class="text-black me-4">{{$invData->received_amount}}</span></li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>

                <div class="row">
                    <div class="col">
                        <p class="ms-3">In Words: <span>@numberToWord({{$invData->received_amount}})</span></p>
                    </div>
                </div>

                <div class="row">
                    <div class="col">
                        <p>Thank you for trusting us with your healthcare needs,Your happiness is our top concern.</p>
                    </div>
                    <div class="col">
                        <h1> <span class="badge bg-secondary">{{$invData->status}}</span></h1>
                    </div>
                </div>
                <br>
                <div>
                    <figure>
                        <figcaption class="blockquote-footer">
                            Printed By: <cite title="Source Title">{{$invData->user_info->name}}-{{\Carbon\Carbon::now()->format('d-M-Y h:i:s A')}}</cite>
                        </figcaption>
                    </figure>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="{{asset('/')}}assets/bootstrap/js/bootstrap.bundle.js" ></script>
</body>
</html>
