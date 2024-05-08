<?php

namespace App\Http\Controllers\Api\invoice;

use App\Http\Controllers\Controller;
use App\Models\administrative\PaymentMethod;
use App\Models\dashboard\invoice\Invoice;
use App\Models\User;
use App\Traits\ApiStatusTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InvoiceController extends Controller
{
    use ApiStatusTrait;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $data['consultation_invoice_data'] = Invoice::query()
            ->when($request->has('create_date'), function ($query) use ($request) {
                $query->where('inv_create', $request->create_date);
            })
            ->when($request->has('user_id'), function ($query) use ($request) {
                $query->where('user_id', $request->user_id);
            })
            ->where('invoice_type', 'Consultation')
            ->with(['patient_info', 'doctor_info', 'user_info'])
            ->get();

        $data['consultation_sum_data'] = Invoice::query()
            ->when($request->has('create_date'), function ($query) use ($request) {
                $query->where('inv_create', $request->create_date);
            })
            ->when($request->has('user_id'), function ($query) use ($request) {
                $query->where('user_id', $request->user_id);
            })
            ->where('invoice_type', 'Consultation')
            ->select(
                DB::raw('SUM(invoice_total_amount) as total_amount'),
                DB::raw('SUM(paid_amount) as total_paid_amount'),
                DB::raw('SUM(total_discount_amount) as discount_amount_total'),
                DB::raw('SUM(received_amount) as total_received_amount'),
                DB::raw('SUM(due_amount) as total_due_amount'),
            )
            ->first();

        $data['pathology_invoice_data'] = Invoice::query()
            ->when($request->has('create_date'), function ($query) use ($request) {
                $query->where('inv_create', $request->create_date);
            })
            ->when($request->has('user_id'), function ($query) use ($request) {
                $query->where('user_id', $request->user_id);
            })
            ->where('invoice_type', 'Pathology')
            ->with(['patient_info', 'doctor_info', 'user_info'])
            ->get();

        $data['pathology_sum_data'] = Invoice::query()
            ->when($request->has('create_date'), function ($query) use ($request) {
                $query->where('inv_create', $request->create_date);
            })
            ->when($request->has('user_id'), function ($query) use ($request) {
                $query->where('user_id', $request->user_id);
            })
            ->where('invoice_type', 'Pathology')
            ->select(
                DB::raw('SUM(invoice_total_amount) as total_amount'),
                DB::raw('SUM(paid_amount) as total_paid_amount'),
                DB::raw('SUM(total_discount_amount) as discount_amount_total'),
                DB::raw('SUM(received_amount) as total_received_amount'),
                DB::raw('SUM(due_amount) as total_due_amount'),
            )
            ->first();

        $data['summary_total'] = [
            'total_amount' => $data['pathology_sum_data']['total_amount'] + $data['consultation_sum_data']['total_amount'],
            'total_paid_amount' => $data['pathology_sum_data']['total_paid_amount'] + $data['consultation_sum_data']['total_paid_amount'],
            'discount_amount_total' => $data['pathology_sum_data']['discount_amount_total'] + $data['consultation_sum_data']['discount_amount_total'],
            'total_received_amount' => $data['pathology_sum_data']['total_received_amount'] + $data['consultation_sum_data']['total_received_amount'],
            'total_due_amount' => $data['pathology_sum_data']['total_due_amount'] + $data['consultation_sum_data']['total_due_amount'],
        ];

        return $this->successApiResponse($data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function user_info()
    {
        $data['user_info']=User::where('isVerified',1)->get();
        return $this->successApiResponse($data);
    }

    public function paymentMethod()
    {
       $response['payment_method']= PaymentMethod::where('status',1)->get();
       return $this->successApiResponse($response);
    }
}
