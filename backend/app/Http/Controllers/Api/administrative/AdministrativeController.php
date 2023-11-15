<?php

namespace App\Http\Controllers\Api\administrative;

use App\Http\Controllers\Controller;
use App\Models\administrative\PatientSourceinfo;
use App\Models\dashboard\PatientInfo;
use App\Models\dashboard\UserGroup;
use App\Models\User;
use App\Traits\ApiStatusTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdministrativeController extends Controller
{
    use ApiStatusTrait;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data['patient_source'] = PatientSourceinfo::with('user_data')->get();
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
        DB::beginTransaction();
        try {
            $request->validate([
                'source_name' => ['required'],
            ]);

            $patientSource = new PatientSourceinfo();
            $patientSource->source_id = $this->generateUniquePSID();
            $patientSource->source_name = $request['source_name'];
            $patientSource->source_phone = $request['source_phone'];
            $patientSource->source_location = $request['source_location'];
            $patientSource->status = 1;
            $patientSource->user_id = $request->user_id;
            $patientSource->save();

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            $response['message'] = $e->getMessage();
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

        DB::beginTransaction();
        try {

            $patientSource = PatientSourceinfo::find($id);
            $patientSource->source_name = $request['source_name'];
            $patientSource->source_phone = $request['source_phone'];
            $patientSource->source_location = $request['source_location'];
            $patientSource->save();

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            $response['message'] = $e->getMessage();
            return $this->failureApiResponse($response);
        }

        $response['message'] = 'Updated Successfully';
        return $this->successApiResponse($response);

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

    public function generateUniquePSID()
    {
        // Step 1: Count Primary IDs
        $primaryIdCount = PatientSourceinfo::count();
        $primaryIdCount++;
        return 'PSID' . substr('0000', 0, -strlen($primaryIdCount)) . $primaryIdCount;
    }
    public function statusChange(Request $request,$id){
        DB::beginTransaction();
        try {

            $user =PatientSourceinfo::find($id);
            $user->status =$request->query('status');
            $user->save();

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            $response['message'] = $e->getMessage();
            return $this->failureApiResponse($response);
        }


        $response['message'] = 'Status Change Successfully';
        return $this->successApiResponse($response);
    }
}
