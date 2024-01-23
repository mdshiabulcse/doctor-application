<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="Mark Otto, Jacob Thornton, and Bootstrap contributors">
    <meta name="generator" content="Hugo 0.104.2">
    <title>{{$pageTitle}}</title>
    <link href="{{asset('/')}}assets/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{asset('/')}}assets/bootstrap/font/fontawesome.min.css" rel="stylesheet">
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
            <div class="row d-flex">
                <div class="col-xl-3 float-end">
                    <button onclick="window.print()" class="btn btn-primary btn-lg">Print
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
<div>
    <table class="table">
        <thead>
        <div class="px-4 text-center">
            <h1 class=" fw-bold text-body-emphasis">Centered hero</h1>
        </div>
        <hr>
        </thead>
    </table>
    <table class="table">
        <tbody>
        <h4>Consultation</h4>
        <tr>
            <th scope="col">SL</th>
            <th scope="col">INVID</th>
            <th scope="col">PID</th>
            <th scope="col">PatientName</th>
            <th scope="col">Doctor</th>
            <th scope="col">Total</th>
            <th scope="col">Paid</th>
            <th scope="col">Discount</th>
            <th scope="col">Received</th>
            <th scope="col">Due</th>
            <th scope="col">Status</th>
        </tr>
        @foreach($consultation_invoice_data as $con_inv_data)
            <tr>
                <th scope="row">{{ $loop->iteration }}</th>
                <td>{{ $con_inv_data->invoice_id }}</td>
                <td>{{ $con_inv_data->patient_id }}</td>
                <td>{{ $con_inv_data->patient_info->patient_name }}</td>
                <td>{{ $con_inv_data->doctor_info->doctor_name }}</td>
                <td>{{ $con_inv_data->invoice_total_amount }}</td>
                <td>{{ $con_inv_data->paid_amount }}</td>
                <td>{{ $con_inv_data->total_discount_amount }}</td>
                <td>{{ $con_inv_data->received_amount }}</td>
                <td>{{ $con_inv_data->due_amount }}</td>
                <td>{{ $con_inv_data->status }}</td>
            </tr>
        @endforeach
        <tr>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th>Total:</th>
            <th>{{$consultation_sum_data->total_amount}}</th>
            <th>{{$consultation_sum_data->total_paid_amount}}</th>
            <th>{{$consultation_sum_data->discount_amount_total}}</th>
            <th>{{$consultation_sum_data->total_received_amount}}</th>
            <th>{{$consultation_sum_data->total_due_amount}}</th>
            <th></th>
        </tr>
        </tbody>
    </table>
    <table class="table">
        <tbody>
        <h4>Pathology</h4>
        <tr>
            <th scope="col">SL</th>
            <th scope="col">INVID</th>
            <th scope="col">PID</th>
            <th scope="col">PatientName</th>
            <th scope="col">Doctor</th>
            <th scope="col">Total</th>
            <th scope="col">Paid</th>
            <th scope="col">Discount</th>
            <th scope="col">Received</th>
            <th scope="col">Due</th>
            <th scope="col">Status</th>
        </tr>
        @foreach($pathology_invoice_data as $path_inv_data)
            <tr>
                <th scope="row">{{ $loop->iteration }}</th>
                <td>{{ $path_inv_data->invoice_id }}</td>
                <td>{{ $path_inv_data->patient_id }}</td>
                <td>{{ $path_inv_data->patient_info->patient_name }}</td>
                <td>{{ $path_inv_data->doctor_info->doctor_name }}</td>
                <td>{{ $path_inv_data->invoice_total_amount }}</td>
                <td>{{ $path_inv_data->paid_amount }}</td>
                <td>{{ $path_inv_data->total_discount_amount }}</td>
                <td>{{ $path_inv_data->received_amount }}</td>
                <td>{{ $path_inv_data->due_amount }}</td>
                <td>{{ $path_inv_data->status }}</td>
            </tr>
        @endforeach
        <tr>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th>Total:</th>
            <th>{{$pathology_sum_data->total_amount}}</th>
            <th>{{$pathology_sum_data->total_paid_amount}}</th>
            <th>{{$pathology_sum_data->discount_amount_total}}</th>
            <th>{{$pathology_sum_data->total_received_amount}}</th>
            <th>{{$pathology_sum_data->total_due_amount}}</th>
            <th></th>
        </tr>
        </tbody>
    </table>
    <table class="table col-6">
        <tbody>
        <h4>Summary</h4>
        <div class="row">
            <tr class="col-6">
                <th class="col-4">Invoice Total Amount:</th>
                <th class="col-6">{{number_format($summary_total['total_amount'],2)}}</th>
            </tr>
            <tr class="col-6">
                <th class="col-4">Invoice Paid Amount:</th>
                <th class="col-6">{{number_format($summary_total['total_paid_amount'],2)}}</th>
            </tr>
            <tr class="col-6">
                <th class="col-4">Invoice Discount Amount:</th>
                <th class="col-6">{{number_format($summary_total['discount_amount_total'],2)}}</th>
            </tr>
            <tr class="col-6">
                <th class="col-4">Invoice Due Amount:</th>
                <th class="col-6">{{number_format($summary_total['total_due_amount'],2)}}</th>
            </tr>
            <tr class="col-6">
                <th class="col-4">Invoice Received Amount:</th>
                <th class="col-6">{{number_format($summary_total['total_received_amount'],2)}}</th>
            </tr>
            <tr class="col-6">
                <th class="col-4">Balance:</th>
                <th class="col-6">{{number_format($summary_total['total_received_amount'],2)}}</th>
            </tr>
        </div>

        </tbody>
    </table>
</div>
<script src="{{asset('/')}}assets/bootstrap/js/bootstrap.bundle.js"></script>
<script src="{{asset('/')}}assets/bootstrap/js/fontawesome.min.js"></script>
</body>
</html>
