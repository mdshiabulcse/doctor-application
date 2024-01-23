<?php

namespace App\Http\Controllers\Api\print;

use App\Http\Controllers\Controller;
use App\Models\dashboard\invoice\Invoice;
use App\Models\dashboard\invoice\InvoiceInfo;
use App\Traits\ApiStatusTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PrintController extends Controller
{
    use ApiStatusTrait;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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

    public function InvoicePrint($inv_id)
    {
        $invData = Invoice::where('invoice_id',$inv_id)->with('patient_info')->first();
        $invInfoDatas= InvoiceInfo::where('invoice_id',$inv_id)->with('exam_data')->get();
        return view('print.InvoicePrint',compact('invData', 'invInfoDatas'));
    }
    public function consultationInvoicePrint($inv_id)
    {
        $invData = Invoice::where('invoice_id',$inv_id)->with('patient_info')->first();
        $invInfoDatas= InvoiceInfo::where('invoice_id',$inv_id)->with('exam_data')->get();
//        return $this->successApiResponse($invData);
        return view('print.consultationInvoicePrint',compact('invData', 'invInfoDatas'));
    }

    public function InvoicePrintList(Request $request)
    {

        $data['consultation_invoice_data'] = Invoice::query()
            ->when($request->has('create_date'), function ($query) use ($request) {
                $query->where('inv_create', $request->create_date);
            })
            ->when($request->filled('user_id'), function ($query) use ($request) {
                $query->where('user_id', $request->user_id);
            })
            ->where('invoice_type', 'Consultation')
            ->with(['patient_info', 'doctor_info', 'user_info'])
            ->get();

        $data['consultation_sum_data'] = Invoice::query()
            ->when($request->has('create_date'), function ($query) use ($request) {
                $query->where('inv_create', $request->create_date);
            })
            ->when($request->filled('user_id'), function ($query) use ($request) {
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
            ->when($request->filled('user_id'), function ($query) use ($request) {
                $query->where('user_id', $request->user_id);
            })
            ->where('invoice_type', 'Pathology')
            ->with(['patient_info', 'doctor_info', 'user_info'])
            ->get();

        $data['pathology_sum_data'] = Invoice::query()
            ->when($request->has('create_date'), function ($query) use ($request) {
                $query->where('inv_create', $request->create_date);
            })
            ->when($request->filled('user_id'), function ($query) use ($request) {
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

        $data['pageTitle']='Invoice Information';
        return view('print.invoice-list-print',$data);
//        return $this->successApiResponse($data);

    }
}
