<?php

namespace App\Http\Controllers\Api\ot;

use App\Http\Controllers\Controller;
use App\Models\dashboard\ot\OperationRegistration;
use App\Models\dashboard\patient\PatientLog;
use App\Traits\ApiStatusTrait;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Jenssegers\Agent\Facades\Agent;

class OperationController extends Controller
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


        // Validation: Check if the name and phone fields are filled
        $validationRules = [
            'patient_id' => 'required',
            'doctor_id' => 'required',
            'operation_id' => 'required',
            'operation_date' => 'required',
            'ot_amount' => 'required',
        ];

        $validationMessages = [
            'patient_id.required' => 'The patient id is required.',
            'doctor_id.required' => 'The operation doctor is required.',
            'operation_id.required' => 'The operation name is required.',
            'operation_date.required' => 'The operation date is required.',
            'ot_amount.required' => 'The operation amount is required.',
        ];

        $validator = Validator::make($request->all(), $validationRules,$validationMessages);

        if ($validator->fails()) {
            $response['errors'] = $validator->errors()->all();
            return $this->failureApiResponse($response);
        }

        DB::beginTransaction();
        try {


            // Continue with the rest of your code to save the data

            /*Start:: User Device Info */
            $browserName = Agent::browser();
            $browserVersion = Agent::version($browserName);
            $deviceInfo = Agent::device();
            $osPlatform = Agent::platform();
            $IP = request()->ip();
            /*Start:: User Device Info */

            /*Start:: Operation Register unique id created here */
            $currentDate = date('ymd');
            $primaryIdCount = OperationRegistration::count();
            $primaryIdCount++;
            $OTRID = 'OTR'. $currentDate . substr('000', 0, -strlen($primaryIdCount)) . $primaryIdCount;
            /*End:: Operation Register unique id created here */

            //patient information created here
            $operationRegisterData=new OperationRegistration();
            $operationRegisterData->registration_number = $OTRID;
            $operationRegisterData->patient_id = $request->patient_id;
            $operationRegisterData->operation_id = $request->operation_id;
            $operationRegisterData->operation_subhead = $request->operation_subhead;
            $operationRegisterData->operation_date = $request->operation_date;
            $operationRegisterData->operation_time = $request->operation_time;
            $operationRegisterData->ot_amount = $request->ot_amount;
            $operationRegisterData->doctor_id = $request->doctor_id;
            $operationRegisterData->refer_doctor_id = $request->refer_doctor_id;
            $operationRegisterData->ot_description = $request->ot_description;
            $operationRegisterData->status = 'Active';
            $operationRegisterData->user_id = $request->user_id;
            $operationRegisterData->status = 1;
            $operationRegisterData->create_date = Carbon::now();
            $operationRegisterData->ip_information ='IP-'.$IP.',DEV-'.$deviceInfo.',OS-'.$osPlatform.',BROW-'.$browserName.',VER-'.$browserVersion;
            $operationRegisterData->save();

            //patient log information created here
            $patientLogData=new PatientLog();
            $patientLogData->patient_id = $operationRegisterData->patient_id;
            $patientLogData->patient_log_data = $operationRegisterData->registration_number.','.$operationRegisterData->operation_date;
            $patientLogData->device_info = 'IP-'.$IP.',DEV-'.$deviceInfo.',OS-'.$osPlatform.',BROW-'.$browserName.',VER-'.$browserVersion;
            $patientLogData->user_id = $operationRegisterData->user_id;
            $patientLogData->save();

            $response['registration_number'] = $operationRegisterData->registration_number;

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
}
