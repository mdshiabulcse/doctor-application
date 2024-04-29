<?php

namespace App\Http\Controllers\Api\invoice;

use App\Http\Controllers\Controller;
use App\Models\dashboard\invoice\Invoice;
use App\Models\dashboard\invoice\InvoiceInfo;
use App\Models\dashboard\invoice\InvoiceLog;
use App\Models\dashboard\patient\PatientAppointment;
use App\Traits\ApiStatusTrait;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Jenssegers\Agent\Facades\Agent;

class ConsultationInvoiceController extends Controller
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
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        DB::beginTransaction();
        try {

            // if already created today visited doctor
            $existingInvoice = Invoice::where('patient_id', $request['patient_id'])
                ->where('dr_id', $request['doctor_id'])
                ->where('status', 'Paid')
                ->whereDate('inv_create', Carbon::now()->toDateString()) // Check for the same creation date
                ->first();

            if ($existingInvoice) {
                // Handle validation error, for example:
                $response['errors'] = 'Consultation Invoice already exists for patient, Today Visited Doctor.';
                return $this->failureApiResponse($response);
            }

            if(!$request['doctor_id']){
                $response['errors'] = 'Consultation Doctor is required. please reload your page!';
                return $this->failureApiResponse($response);
            }

            //user browser history check here
            $browserName = Agent::browser();
            $browserVersion = Agent::version($browserName);
            $deviceInfo = Agent::device();
            $osPlatform = Agent::platform();
            $IP = request()->ip();

            //Invoice unique id created here
            $currentDate = date('ymd');
            $primaryIdCount = Invoice::where('inv_create', $currentDate)->count();
            $primaryIdCount++;
            $INV = 'INV' . $currentDate . substr('0000', 0, -strlen($primaryIdCount)) . $primaryIdCount;


            //Invoice Information create

            $exInvoiceInfo = new InvoiceInfo();
            $exInvoiceInfo->invoice_id = $INV;
            $exInvoiceInfo->invoice_type = $request['invoice_type'];
            $exInvoiceInfo->patient_id = $request['patient_id'];
            $exInvoiceInfo->invoice_item_id = $request['invoice_item_id'];
            $exInvoiceInfo->invoice_item_amount = $request['invoice_total_amount'];
            $exInvoiceInfo->discount = $request['discount_selected'] ?? 0;
            $exInvoiceInfo->user_id = $request['user_id'];
            $exInvoiceInfo->inv_create = Carbon::now();
            $exInvoiceInfo->status = $request['due_amount'] == 0 ? 'Paid' : 'Due';
            $exInvoiceInfo->save();


            //Invoice create
            $ExInvoice = new Invoice();
            $ExInvoice->invoice_id = $INV;
            $ExInvoice->invoice_type = $request['invoice_type'];
            $ExInvoice->patient_id = $request['patient_id'];
            $ExInvoice->dr_id = $request['doctor_id'];
            $ExInvoice->ref_dr_id = $request['ref_doctor_id'];
            $ExInvoice->invoice_total_amount = $request['invoice_total_amount'];
            $ExInvoice->subtotal_amount = $request['subtotal'];
            $ExInvoice->total_discount_amount = $request['total_discount'] ?? 0;
            $ExInvoice->discount = $request['discount_selected'] ?? 0;
            $ExInvoice->paid_amount = $request['total_paid_amount'];
            $ExInvoice->received_amount = $request['received_amount'];
            $ExInvoice->due_amount = $request['due_amount'];
            $ExInvoice->status = $request['due_amount'] == 0 ? 'Paid' : 'Due';
            $ExInvoice->inv_create = Carbon::now();
            $ExInvoice->user_id = $request['user_id'];
            $ExInvoice->create_user_device_info = 'IP-' . $IP . ',Browser Info-' . $browserName . ',Version-' . $browserVersion . ',Device Info-' . $deviceInfo . ',OS-' . $osPlatform;
            $ExInvoice->save();

            //Invoice create Log
            $exInvoiceLog = new InvoiceLog();
            $exInvoiceLog->invoice_id = $ExInvoice->invoice_id;
            $exInvoiceLog->invoice_type = $ExInvoice->invoice_type;
            $exInvoiceLog->invoice_create_details = 'Invoice Consultation Created';
            $exInvoiceLog->user_device_info = 'IP-' . $IP . ',Browser Info-' . $browserName . ',Version-' . $browserVersion . ',Device Info-' . $deviceInfo . ',OS-' . $osPlatform;
            $exInvoiceLog->user_id = $request->user_id;
            $exInvoiceLog->save();

            //Appointment Confirmation Status Change Here
            $appointment = PatientAppointment::whereId($request['appointment_id'])->first();
            $appointment->status = 'Waiting';
            $appointment->save();

            // Return the created InvoiceInfo
            $response['invoice_id'] = $ExInvoice->invoice_id;
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            $response['errors'] = $e->getMessage() . $e->getLine();
            return $this->failureApiResponse($response);
        }
        $response['message'] = 'Invoice Created Successfully';
        return $this->successApiResponse($response);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
