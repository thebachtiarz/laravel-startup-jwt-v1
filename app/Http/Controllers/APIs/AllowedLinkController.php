<?php

namespace App\Http\Controllers\APIs;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class AllowedLinkController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function getAllowedLink()
    {
        $validate = Validator::make(request()->all(), [
            '_type' => 'required|alpha_dash'
        ]);
        if ($validate->fails()) {
            return response()->json(errorResponse('bad request!'), 200);
        }
        $httplink = config('app_handler.allowed_link');
        foreach ($httplink as $key => $value) {
            if (($value['auth'] == 'all') && ($value['type'] == request()->_type)) {
                $data[] = globalUrlAllowedMap($value);
            }
        }
        return response()->json(successResponse('token-auth', isset($data) ? $data : []), 200);
    }
}
