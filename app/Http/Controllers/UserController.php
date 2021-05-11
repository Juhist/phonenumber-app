<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Exception;
use App\Models\User;
use App\Models\PhoneNumber;
use App\Http\Resources\UserResource;

class UserController extends Controller
{
    /**
     * index
     *
     * @param  Request $request
     * @return Json
     */
    public function index(Request $request)
    {
        $perPage = $request->input('perPage') ?: $request->input('per_page') ?: $request->input('per-page') ?: 5;
        return UserResource::collection(User::paginate($perPage));
    }

    /**
     * view
     *
     * @param  Request $request
     * @param  Integer $id
     * @return
     */
    public function view(Request $request, $id)
    {
        $this->getValidationFactory()->make(
            [
                'id' => $id,
            ],
            [
                'id' => 'exists:users',
            ],
        )->validate();
        return UserResource::make(User::find($id));
    }

    /**
     * create
     *
     * @param  Request $request
     * @return
     */
    public function create(Request $request)
    {
        $request->validate([
            'name' => 'required|alpha',
            'email' => 'required|email',
            'phoneNumber' => 'required|phone:HU',
            'dateOfBirth' => 'required|date_format:Y-m-d|before:today',
            'isActive' => 'required|in:0,1',
        ]);
        DB::beginTransaction();
        if(!$user = User::create($request->all()))
        {
            DB::rollBack();
            throw new Exception('Error in saving data.');
        }
        if(!PhoneNumber::create([
            'user_id' => $user->id, 
            'phoneNumber' => $request->phoneNumber,
            'isDefault' => 1,
            ]))
        {
            DB::rollBack();
            throw new Exception('Error in saving data.');
        }        
        DB::commit();

        return;
    }

    /**
     * update
     *
     * @param  Request $request
     * @param  Integer $id
     * @return
     */
    public function update(Request $request, $id)
    {
        $this->getValidationFactory()->make(
            [
                'id' => $id,
                'name' => $request->name,
                'email' => $request->email,
                'phoneNumber' => $request->phoneNumber,
                'dateOfBirth' => $request->dateOfBirth,
                'isActive' => $request->isActive,
            ],
            [
                'id' => 'required|exists:users,id',
                'name' => 'alpha',
                'email' => 'email',
                'phoneNumber' => 'required|phone:HU',
                'dateOfBirth' => 'date_format:Y-m-d|before:today',
                'isActive' => 'in:0,1',
            ],
        )->validate();        

        $user = User::find($id);
        if($request->name !== null)$user->name = $request->name;
        if($request->email !== null)$user->email = $request->email;
        if($request->dateOfBirth !== null)$user->dateOfBirth = $request->dateOfBirth;
        if($request->isActive !== null)$user->isActive = $request->isActive;
        DB::beginTransaction();        
        if(!$user->save())
        {
            DB::rollBack();
            throw new Exception('Error in saving user data.');
        }        
        if($request->phoneNumber !== null)
        {
            $phoneNumber = PhoneNumber::where(['user_id' => $id, 'isDefault' => 1])->first();
            if(!$phoneNumber)
            {
                DB::rollBack();
                throw new Exception('Not found phone number.');
            }
            $phoneNumber->phoneNumber = $request->phoneNumber;
            if(!$phoneNumber->save())
            {
                DB::rollBack();
                throw new Exception('Error in saving default phone number data.');
            }                    
        }
        DB::commit();
        return;
    }    

    /**
     * delete
     *
     * @param  Request $request
     * @param  Integer $id
     * @return
     */
    public function delete(Request $request, $id)
    {
        $this->getValidationFactory()->make(
            [
                'id' => $id,
            ],
            [
                'id' => 'exists:users',
            ],
        )->validate();
        DB::beginTransaction();
        if(!PhoneNumber::where('user_id', $id)->delete())
        {
            DB::rollBack();
            throw new Exception('Error in deleting data.');
        }   
        if(!User::find($id)->delete())
        {
            DB::rollBack();
            throw new Exception('Error in deleting data.');            
        }   
        DB::commit();
        return;
    }    
}