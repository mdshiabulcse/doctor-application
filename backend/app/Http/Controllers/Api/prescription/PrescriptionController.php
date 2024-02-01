<?php

namespace App\Http\Controllers\Api\prescription;

use App\Http\Controllers\Controller;
use App\Models\dashboard\patient\PatientPrescription;
use App\Models\dashboard\patient\PatientPrescriptionMedicine;
use App\Models\dashboard\prescription\MedicineData;
use App\Traits\ApiStatusTrait;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Jenssegers\Agent\Facades\Agent;
use Mockery\Exception;

class PrescriptionController extends Controller
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
        DB::beginTransaction();
        try {
            //user browser history check here
            $browserName = Agent::browser();
            $browserVersion = Agent::version($browserName);
            $deviceInfo = Agent::device();
            $osPlatform = Agent::platform();
            $IP = request()->ip();

            //Prescription data save
            $prescriptionData = new PatientPrescription();
            $prescriptionData->patient_id = $request['patient_id'];
            $prescriptionData->symptoms = $request['symptoms'];
            $prescriptionData->advice_note = $request['advice_note'];
            $prescriptionData->followup_date = $request['followup_date'];
            $prescriptionData->create_date = Carbon::now();
            $prescriptionData->status = 1;
            $prescriptionData->ip_address = 'IP-'.$IP.',Browser Info-'.$browserName.',Version-'.$browserVersion.',Device Info-'.$deviceInfo.',OS-'.$osPlatform;
            $prescriptionData->save();

            //Prescription Medicine Data Save
            foreach ($request['medicines'] as $medicineValue){
                $medicineData=new PatientPrescriptionMedicine();
                $medicineData->prescription_id= $prescriptionData->id;
                $medicineData->patient_id = $request['patient_id'];
                $medicineData->medicine_id = $medicineValue['medicine_id'];
                $medicineData->duration = $medicineValue['duration'];
                $medicineData->medicine_instruction = $medicineValue['medicine_instruction'];
                $medicineData->status = 1;
                $medicineData->save();
            }



            DB::commit();
        }catch (Exception $e){
            DB::rollBack();
            $response['errors']=$e->getMessage().$e->getLine();
            return $this->failureApiResponse($response);
        }
        $response['message']='Prescription Save successfully';
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

    public function getMedicineInPrescription()
    {
        $data['medicine_data']=MedicineData::whereStatus(1)->get();
        return $this->successApiResponse($data);
    }
}
