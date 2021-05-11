<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Exception;
use App\Models\PhoneNumber;
use App\Http\Resources\PhoneNumberResource;

class PhoneNumberController extends Controller
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
        return PhoneNumberResource::collection(PhoneNumber::paginate($perPage));
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
                'id' => 'exists:phone_numbers',
            ],
        )->validate();
        return PhoneNumberResource::make(PhoneNumber::find($id));
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
            'phoneNumber' => 'required|phone:HU',
            'user_id' => 'required|exists:users,id',
            'isDefault' => 'required|in:0,1',
        ]);
        DB::beginTransaction();
        if (!$new = PhoneNumber::create($request->all())) {
            DB::rollBack();
            throw new Exception('Error in saving data.');
        }
        if ($request->isDefault)
        {
            $others = PhoneNumber::where([
                'user_id' => $request->user_id,
                'isDefault' => 1,
            ])->where('id', '!=', $new->id);
            if($others->count() != 0)
            {
                if(!$others->update(['isDefault' => 0]))
                {
                    DB::rollBack();
                    throw new Exception('Error in saving data.');
                }
            }
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
                'phoneNumber' => $request->phoneNumber,
                'isDefault' => $request->isDefault,
            ],
            [
                'id' => 'required|exists:phone_numbers,id',
                'phoneNumber' => 'required|phone:HU',
                'isDefault' => 'nullable|in:1',
            ],
        )->validate();

        $phone = PhoneNumber::find($id);
        if ($request->phoneNumber !== null) $phone->phoneNumber = $request->phoneNumber;
        DB::beginTransaction();
        if ($request->isDefault !== null) 
        {
            $others = PhoneNumber::where([
                'user_id' => $phone->user_id,
                'isDefault' => 1,
            ]);
            if ($others->count() != 0) {
                if (!$others->update(['isDefault' => 0])) {
                    DB::rollBack();
                    throw new Exception('Error in saving data.');
                }
            }
            $phone->isDefault = $request->isDefault;
        }
        if (!$phone->save()) {
            DB::rollBack();
            throw new Exception('Error in saving user data.');
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
                'id' => 'exists:phone_numbers',
            ],
        )->validate();

        $phone = PhoneNumber::find($id);
        if($phone->isDefault)
        {
            throw new Exception('Default phone number not delete.');
        }    
        if (!$phone->delete()) {
            throw new Exception('Error in deleting data.');
        }
        return;
    }   
}
