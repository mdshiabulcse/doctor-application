<?php

namespace App\Http\Controllers\Api\examination;

use App\Http\Controllers\Controller;
use App\Models\administrative\DiscountList;
use App\Models\dashboard\DoctorInfo;
use App\Models\dashboard\examination\ExaminationList;
use App\Models\dashboard\invoice\Invoice;
use App\Traits\ApiStatusTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Jenssegers\Agent\Facades\Agent;

class ExaminationInvoiceController extends Controller
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
        //Invoice unique id created here
//        $currentDate = date('ymd');
//        $primaryIdCount = Invoice::where('inv_create', $currentDate)->count();
//        $primaryIdCount++;
//        $INV = 'INV'. $currentDate . substr('0000', 0, -strlen($primaryIdCount)) . $primaryIdCount;
//        $response = $INV;
        return $this->successApiResponse($request->all());


        DB::beginTransaction();
        try {
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
            $INV = 'INV'. $currentDate . substr('0000', 0, -strlen($primaryIdCount)) . $primaryIdCount;

            $ExInvoice=new Invoice();
            $ExInvoice->invoice_id = $INV;
            $ExInvoice->invoice_type = $INV;
            $ExInvoice->dr_id = $INV;
            $ExInvoice->ref_dr_id = $INV;
            $ExInvoice->invoice_total_amount = $INV;
            $ExInvoice->subtotal_amount = $INV;
            $ExInvoice->total_discount_amount = $INV;
            $ExInvoice->discount = $INV;
            $ExInvoice->paid_amount = $INV;
            $ExInvoice->received_amount = $INV;
            $ExInvoice->status = $INV;
            $ExInvoice->inv_create = $INV;
            $ExInvoice->user_id = $INV;
            $ExInvoice->create_user_device_info = $INV;
            $ExInvoice->save();

            foreach ($request->examinations as ){

            }

            DB::commit();
        }catch (\Exception $e){
            DB::rollBack();
            $response['errors']=$e->getMessage().$e->getLine();
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


    public function doctorData()
    {
        $data['special_doctor'] = DoctorInfo::whereStatus(1)->where('dr_type', 'Special')->get();
        $data['referral_doctor'] = DoctorInfo::whereStatus(1)->get();
        return $this->successApiResponse($data);
    }


    public function examinationList()
    {
        $data['examination_list']=ExaminationList::whereStatus(1)->get();
        return $this->successApiResponse($data);
    }
    public function discountList()
    {
        $data['discount_list']=DiscountList::whereStatus(1)->get();
        return $this->successApiResponse($data);
    }

}
