<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\RegisterRequest;
use App\Models\dashboard\GroupRole;
use App\Models\dashboard\UserGroup;
use App\Models\User;
use App\Traits\ApiStatusTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    use ApiStatusTrait;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {



        $userGroup = UserGroup::where(['user_id' => $request->user_login_id])->whereIn('group_id', [1,2])->first();
        if (!$userGroup) {
            $response['message'] = 'Sorry! You Have Not Allowed';
            return $this->notAllowedApiResponse($response);
        }
        $data['user_list'] = User::all();
        $data['group_role'] = GroupRole::all();
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
                'name' => ['required'],
                'phone' => ['required', 'unique:users,phone', 'min:11'],
                'email' => ['required', 'email', 'unique:users,email'],
                'password' => ['required'],
            ]);

            $user = new User();
            $user->name = $request['name'];
            $user->email = $request['email'];
            $user->phone = $request['phone'];
            $user->password = Hash::make($request['password']);
            $user->isVerified = 1;
            $user->status = 1;
            $user->save();
//            $user=User::create($request->validated());

            foreach ($request['selectedGroupRoles'] as $user_group_id) {
                $userGroup = new UserGroup();
                $userGroup->user_id = $user->id;
                $userGroup->group_id = $user_group_id;
                $userGroup->save();
            }

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
}
