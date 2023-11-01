<?php

namespace App\Http\Controllers\Api\dashboard;

use App\Http\Controllers\Controller;
use App\Models\dashboard\DoctorInfo;
use App\Models\dashboard\UserGroup;
use App\Traits\ApiStatusTrait;
use Illuminate\Http\Request;

class DoctorsController extends Controller
{
    use ApiStatusTrait;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return $this->successApiResponse($request->all());
        $userGroup = UserGroup::where(['user_id' => $request->login_user_id])->whereIn('group_id', [1,4])->first();
        if (!$userGroup) {
            $response['message'] = 'Sorry! You Have Not Allowed';
            return $this->notAllowedApiResponse($response);
        }
        $data['doctors']=DoctorInfo::get();
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
        //
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
