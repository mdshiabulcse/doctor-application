<?php

namespace App\Http\Controllers\Api\examination;

use App\Http\Controllers\Controller;
use App\Models\administrative\DiscountList;
use App\Models\dashboard\DoctorInfo;
use App\Models\dashboard\examination\ExaminationList;
use App\Traits\ApiStatusTrait;
use Illuminate\Http\Request;

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
        //
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
