<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\administrative\PatientSourceinfo;
use App\Models\administrative\PaymentMethod;
use App\Models\dashboard\DoctorInfo;
use App\Models\dashboard\ot\OperationList;
use App\Models\User;
use App\Traits\ApiStatusTrait;
use Illuminate\Http\Request;

class DefaultDataController extends Controller
{
    use ApiStatusTrait;
    public function patientSourcesData()
    {
        $response['patient_sources'] = PatientSourceinfo::whereStatus(1)->get();
        return $this->successApiResponse($response);
    }

    public function operationListData()
    {
        $response['operation_list']=OperationList::where('status', 1)->get();
        return $this->successApiResponse($response);
    }
    public function specialDoctorData()
    {
        $response['special_doctor_list']=DoctorInfo::where(['status'=>1,'dr_type'=>'Special'])->get();
        return $this->successApiResponse($response);
    }

    public function allDoctorData()
    {
        $response['all_doctor_list']=DoctorInfo::where(['status'=>1])->get();
        return $this->successApiResponse($response);
    }

    public function userInfo()
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
