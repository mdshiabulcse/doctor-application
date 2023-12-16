<?php

namespace App\Http\Controllers\Api\patients;

use App\Http\Controllers\Controller;
use App\Models\administrative\PatientSourceinfo;
use App\Models\dashboard\patient\PatientLog;
use App\Models\dashboard\PatientInfo;
use App\Traits\ApiStatusTrait;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Jenssegers\Agent\Facades\Agent;

class PatientsController extends Controller
{
    use ApiStatusTrait;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data['patient_info']=PatientInfo::all();
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
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        return $this->successApiResponse($request->all());

        DB::beginTransaction();
        try {
            //user browser history check here
            $browserName = Agent::browser();
            $browserVersion = Agent::version($browserName);
            $deviceInfo = Agent::device();
            $osPlatform = Agent::platform();
            $IP = request()->ip();

           //patient unique id created here
            $currentDate = date('ymd');
            $primaryIdCount = PatientInfo::where('create_date', $currentDate)->count();
            $primaryIdCount++;
            $PID = 'PID'. $currentDate . substr('000', 0, -strlen($primaryIdCount)) . $primaryIdCount;

            //patient information created here
            $patientData=new PatientInfo();
            $patientData->patient_id = $PID;
            $patientData->patient_name = $request->name;
            $patientData->patient_phone = $request->phone;
            $patientData->patient_email = $request->email;
            $patientData->patient_address = '';
            $patientData->patient_father = '';
            $patientData->patient_mother = '';
            $patientData->hospital_name = $request->source_name;
            $patientData->gender = $request->gender;
            $patientData->patient_dob = $request->selectedDob;
            $patientData->patient_age = $request->selectedAge;
            $patientData->user_id = $request->user_id;
            $patientData->ip_address = $IP;
            $patientData->status = 1;
            $patientData->create_date = Carbon::now()->toDateString();
            $patientData->save();

            //patient log information created here
            $patientLogData=new PatientLog();
            $patientLogData->patient_id = $patientData->patient_id;
            $patientLogData->patient_log_data = $patientData->patient_name.','.$patientData->patient_phone;
            $patientLogData->device_info = 'IP-'.$IP.',DEV-'.$deviceInfo.',OS-'.$osPlatform.',BROW-'.$browserName.',VER-'.$browserVersion;
            $patientLogData->user_id = $patientData->user_id;
            $patientLogData->save();

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            $response['errors'] = $e->getMessage() . $e->getLine();
            return $this->failureApiResponse($response);
        }

        $response['message'] = 'Created Successfully';
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

    public function patientSources()
    {
        $response['patient_sources'] = PatientSourceinfo::whereStatus(1)->get();
        return $this->successApiResponse($response);
    }
}
