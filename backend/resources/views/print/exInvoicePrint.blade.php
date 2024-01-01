<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="Mark Otto, Jacob Thornton, and Bootstrap contributors">
    <meta name="generator" content="Hugo 0.104.2">
    <title>{{$invData->invoice_id}}</title>
    <link href="{{asset('/')}}assets/bootstrap/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
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
                    <p style="color: #7e8d9f;font-size: 20px;">Invoice >> <strong>ID: #123-123</strong></p>
                </div>
                <div class="col-xl-3 float-end">
                    <button onclick="window.print()" style="background-color:#60bdf3 ;"
                            class="btn btn-light text-capitalize border-0" data-mdb-ripple-color="dark"><i
                            class="fas fa-print text-primary"></i> Print
                    </button>
                    <a class="btn btn-light text-capitalize" data-mdb-ripple-color="dark"><i
                            class="far fa-file-pdf text-danger"></i> Export</a>
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
                        <i class="fab fa-mdb fa-4x ms-0" style="color:#5d9fc5 ;"></i>
                        <p class="pt-0">MDBootstrap.com</p>
                    </div>

                </div>


                <div class="row">
                    <div class="col d-flex flex-row">
                        <ul class="list-unstyled">
                            <li class="text-muted">To: <span style="color:#5d9fc5 ;">John Lorem</span></li>
                            <li class="text-muted">Street, City</li>
                            <li class="text-muted">State, Country</li>
                            <li class="text-muted"><i class="fas fa-phone"></i> 123-456-789</li>
                        </ul>
                    </div>
                    <div class="col d-flex flex-row-reverse">
                        <p class="text-muted">Invoice</p>
                        <ul class="list-unstyled">
                            <li class="text-muted"><i class="fas fa-circle" style="color:#84B0CA ;"></i> <span
                                    class="fw-bold">ID:</span>#123-456
                            </li>
                            <li class="text-muted"><i class="fas fa-circle" style="color:#84B0CA ;"></i> <span
                                    class="fw-bold">Creation Date: </span>Jun 23,2021
                            </li>
                            <li class="text-muted"><i class="fas fa-circle" style="color:#84B0CA ;"></i> <span
                                    class="me-1 fw-bold">Status:</span><span
                                    class="badge bg-warning text-black fw-bold">
                  Unpaid</span></li>
                        </ul>
                    </div>
                </div>

                <div class="row my-2 mx-1 justify-content-center">

                    <table class="table table-striped table-borderless">
                        <thead style="background-color:#84B0CA ;" class="text-white">
                        <tr>
                            <th class="col-1">#</th>
                            <th class="col-8">Description</th>
                            <th class="col-3">Amount</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach(@$invInfoDatas as  $invInfoData)
                            <tr>
                                <th scope="row">{{ $loop->iteration }}</th>
                                <td>{{ @$invInfoData->exam_data->ex_name }}</td>
                                <td>{{ @$invInfoData->invoice_item_amount }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    <hr>
                    <table >
                        <thead class="text-white">
                        <tr>
                            <th class="col-1"></th>
                            <th class="col-8"></th>
                            <th class="col-3"></th>
                        </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="col-2"></td>
                                <td class="col-4 text-md-end me-5">SubTotal</td>
                                <td class="col-6 text-black text-md-start">৳1110</td>
                            </tr>
                            <tr>
                                <td class="col-2"></td>
                                <td class="col-4 text-md-end me-5">Discount(15%)</td>
                                <td class="col-6 text-md-start">৳111</td>
                            </tr>
                            <tr>
                                <td class="col-4"></td>
                                <td class="col-2 text-md-end me-5">Payable Amount</td>
                                <td class="col-6 text-md-start">৳111</td>
                            </tr>
                            <tr>
                                <td class="col-2"></td>
                                <td class="col-4 text-md-end me-5">Received Amount</td>
                                <td class="col-6 text-md-start">৳111</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="row">
                    <div class="col">
                        <p class="ms-3">Add additional notes and payment information</p>

                    </div>
                    <div class="col d-flex flex-row-reverse">
                        <ul class="list-unstyled">
                            <li class="text-muted ms-3"><span class="text-black me-4">SubTotal</span>$1110</li>
                            <li class="text-muted ms-3 mt-2"><span class="text-black me-4">Tax(15%)</span>$111</li>
                        </ul>
                        <p class="text-black float-start"><span class="text-black me-3"> Total Amount</span><span
                                style="font-size: 25px;">$1221</span></p>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xl-10">
                        <p>Thank you for your purchase</p>
                    </div>
                    <div class="col-xl-2">
                        <button type="button" class="btn btn-primary text-capitalize"
                                style="background-color:#60bdf3 ;">Pay Now
                        </button>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
<script src="{{asset('/')}}assets/bootstrap/js/bootstrap.bundle.js"
        integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4"
        crossorigin="anonymous"></script>
</body>
</html>
