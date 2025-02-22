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
use Illuminate\Support\Facades\Validator;
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
            $prescriptionData->doctor_id = $request['doctor_id'];
            $prescriptionData->symptoms = $request['symptoms'];
            $prescriptionData->advice_note = $request['advice_note'];
            $prescriptionData->followup_date = $request['followup_date'];
            $prescriptionData->create_date = Carbon::now();
            $prescriptionData->status = 1;
            $prescriptionData->user_id = $request['user_id'];
            $prescriptionData->ip_address = 'IP-'.$IP.',Browser Info-'.$browserName.',Version-'.$browserVersion.',Device Info-'.$deviceInfo.',OS-'.$osPlatform;
            $prescriptionData->save();

            //Prescription Medicine Data Save
            foreach ($request['medicines'] as $medicineValue){
                $medicineData=new PatientPrescriptionMedicine();
                $medicineData->prescription_id= $prescriptionData->id;
                $medicineData->patient_id = $request['patient_id'];
                $medicineData->type = $medicineValue['type'];
                $medicineData->medicine_id = $medicineValue['medicine_id'];
                $medicineData->duration = $medicineValue['duration'];
                $medicineData->medicine_instruction = $medicineValue['medicine_instruction'];
                $medicineData->status = 1;
                $medicineData->save();
            }


            $response['prescription_id'] = $prescriptionData->id;
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

        $data['prescription_data']=PatientPrescription::whereId($id)->with(['patient_info','doctor_info'])->first();
        $data['prescription_medicine_data']=PatientPrescriptionMedicine::where('prescription_id',$id)->get();
        return $this->successApiResponse($data);
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


    public function prescriptionUpdate(Request $request)
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
            $prescriptionData =PatientPrescription::find($request['prescription_id']);
            $prescriptionData->patient_id = $request['patient_id'];
            $prescriptionData->doctor_id = $request['doctor_id'];
            $prescriptionData->symptoms = $request['symptoms'];
            $prescriptionData->advice_note = $request['advice_note'];
            $prescriptionData->followup_date = $request['followup_date'];
            $prescriptionData->create_date = Carbon::now();
            $prescriptionData->status = 1;
            $prescriptionData->user_id = $request['user_id'];
            $prescriptionData->ip_address = 'IP-'.$IP.',Browser Info-'.$browserName.',Version-'.$browserVersion.',Device Info-'.$deviceInfo.',OS-'.$osPlatform;
            $prescriptionData->save();

            //Prescription Medicine Data Save
            $prescriptionMedicineData=PatientPrescriptionMedicine::where('prescription_id',$prescriptionData->id)->delete();
            foreach ($request['medicines'] as $medicineValue){
                $medicineData=new PatientPrescriptionMedicine();
                $medicineData->prescription_id= $prescriptionData->id;
                $medicineData->patient_id = $request['patient_id'];
                $medicineData->medicine_id = $medicineValue['medicine_id'];
                $medicineData->type = $medicineValue['type'];
                $medicineData->duration = $medicineValue['duration'];
                $medicineData->medicine_instruction = $medicineValue['medicine_instruction'];
                $medicineData->status = 1;
                $medicineData->save();
            }


            $response['prescription_id'] = $prescriptionData->id;
            DB::commit();
        }catch (Exception $e){
            DB::rollBack();
            $response['errors']=$e->getMessage().$e->getLine();
            return $this->failureApiResponse($response);
        }
        $response['message']='Prescription Save successfully';
        return $this->successApiResponse($response);
    }

    public function getMedicineInPrescription()
    {
        $data['medicine_data']=MedicineData::whereStatus(1)->get();
        return $this->successApiResponse($data);
    }
    public function getPreviousPrescription($patient_id)
    {
        $data['previous_prescription']=PatientPrescription::where('patient_id',$patient_id)->get();
        return $this->successApiResponse($data);
    }

    public function getPrescriptionSuggestionData(Request $request,$doctor_id)
    {

        $query = $request->query('query', '');

        $data['suggest_prescription_data']=PatientPrescription::where('doctor_id',$doctor_id)->where('symptoms', 'like', "%$query%")
            ->distinct()
            ->pluck('symptoms');
        return $this->successApiResponse($data);
    }

    public function medicineDataStore(Request $request)
    {
        DB::beginTransaction();
        try {
            // Validation: Check if the medicine_type and medicine_name fields are filled
            $validationRules = [
                'medicine_type' => 'required',
                'medicine_name' => 'required',
            ];

            $validationMessages = [
                'medicine_type.required' => 'The Medicine type is required.',
                'medicine_name.required' => 'The Medicine Name is required.',
            ];

            $validator = Validator::make($request->all(), $validationRules,$validationMessages);

            if ($validator->fails()) {
                $response['errors'] = $validator->errors()->all();
                return $this->failureApiResponse($response);
            }

            $addMedicine=new MedicineData();
            $addMedicine->type = $request['medicine_type'];
            $addMedicine->medicine_name = $request['medicine_name'];
            $addMedicine->status = 1;
            $addMedicine->save();
            DB::commit();
        }catch (\Exception $e){
            DB::rollBack();
            $response['errors']=$e->getMessage().$e->getLine();
            return $this->failureApiResponse($response);
        }
        $response['message']='Medicine Save Successfully!';
        return $this->successApiResponse($response);
    }


    public function suggestions(Request $request)
    {
        $query = $request->input('query');
        $suggestions = [
            "Take after meal",
            "Before bedtime",
            "Twice a day",
            "Once in the morning",
            "Apply externally"
        ];

        $filtered = array_filter($suggestions, function ($instruction) use ($query) {
            return stripos($instruction, $query) !== false;
        });

        return response()->json(['suggestions' => array_values($filtered)]);
    }
}
