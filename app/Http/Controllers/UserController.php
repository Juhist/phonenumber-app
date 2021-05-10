<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use App\Models\User;
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
     * @return Json
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
            [],
            [
                'id' => 'User ID',
            ]
        )->validate();
        return UserResource::make(User::find($id));
    }

    /**
     * create
     *
     * @param  Request $request
     * @return Json
     */
    public function create(Request $request)
    {
        return;
    }

    /**
     * update
     *
     * @param  Request $request
     * @param  Integer $id
     * @return Json
     */
    public function update(Request $request, $id)
    {
        return;
    }    
}
