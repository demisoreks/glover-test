<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MainController extends Controller
{

    public function unauthorized() {
        return response()->json([
            'response' => [
                'code' => '03',
                'message' => 'Unauthorized'
            ]
        ], 401);
    }

}
