<?php

namespace App\Http\Controllers\Api\print;

use App\Http\Controllers\Controller;
use App\Models\dashboard\invoice\Invoice;
use App\Models\dashboard\invoice\InvoiceInfo;
use App\Traits\ApiStatusTrait;
use Illuminate\Http\Request;

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
}
