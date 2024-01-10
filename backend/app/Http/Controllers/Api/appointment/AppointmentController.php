<?php

namespace App\Http\Controllers\Api\appointment;

use App\Http\Controllers\Controller;
use App\Models\administrative\AppointmentSetting;
use App\Models\dashboard\patient\PatientAppointment;
use App\Traits\ApiStatusTrait;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Jenssegers\Agent\Facades\Agent;

class AppointmentController extends Controller
{
    use ApiStatusTrait;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $data['appointment_data']=PatientAppointment::with(['patient_info','doctor_info'])->get();
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
        DB::beginTransaction();
        try {

            //user browser history check here
            $browserName = Agent::browser();
            $browserVersion = Agent::version($browserName);
            $deviceInfo = Agent::device();
            $osPlatform = Agent::platform();
            $IP = request()->ip();


            //Appointment data save
            $appoinment=new PatientAppointment();
            $appoinment->patient_id = $request->patient_id;
            $appoinment->doctor_id = $request->doctor_id;
            $appoinment->appointment_sl = $request->serial_number;
            $appoinment->appointment_time = $request->slot_time_start;
            $appoinment->appointment_date = $request->appointment_date;
            $appoinment->create_date = Carbon::now();
            $appoinment->status = 'Pending';
            $appoinment->ip_details = 'IP-'.$IP.',Browser Info-'.$browserName.',Version-'.$browserVersion.',Device Info-'.$deviceInfo.',OS-'.$osPlatform;
            $appoinment->user_id = $request->user_id;
            $appoinment->save();

            // Return the created InvoiceInfo
            $response['invoice_id'] = $appoinment->id;

            DB::commit();
        }catch (\Exception $e){
            DB::rollBack();
            $response['errors']=$e->getMessage().$e->getLine();
            return $this->failureApiResponse($response);
        }
        $response['message'] = 'Appointment Save Successfully';
        return $this->successApiResponse($response);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show()
    {

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

    public function appointmentSetting()
    {
        $response['appointment_settings']=AppointmentSetting::whereStatus(1)->get();
        return $this->successApiResponse($response);
    }

    public function appointmentBookingPatient(Request $request,$id)
    {
        $data['appointment_data']=PatientAppointment::where(['doctor_id'=>$id,'appointment_date'=>$request->appointment_date])->get();
        return $this->successApiResponse($data);
    }
}
