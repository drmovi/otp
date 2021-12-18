<?php

namespace App\Http\Controllers;

use App\Services\PinService;
use Illuminate\Http\Request;

class UserPinController extends Controller
{


    public function pins(Request $request, PinService $service)
    {
        return [
            'pins' => $service->generate($request->count ?? 1)
        ];
    }


}
