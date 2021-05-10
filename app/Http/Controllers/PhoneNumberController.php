<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
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
     * @return Json
     */
    public function view(Request $request, $id)
    {
        return;
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
