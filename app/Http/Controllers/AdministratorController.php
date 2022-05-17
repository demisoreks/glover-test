<?php

namespace App\Http\Controllers;

use App\Core\Administrator\AdministratorHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AdministratorController extends Controller
{

    private $administratorHelper;

    public function __construct(AdministratorHelper $administratorHelper) {
        $this->middleware('auth:api', ['except' => ['login', 'register']]);

        $this->administratorHelper = $administratorHelper;
    }

    public function register(Request $request) {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|max:100|unique:administrators',
            'password' => 'required|string|min:8'
        ]);

        if ($validator->fails()) {
            $response = [
                'code' => '01',
                'message' => 'Validation error',
                'details' => [
                    'errors' => $validator->errors()
                ]
            ];
            $http_code = 400;
        } else {
            try {
                $administrator = $this->administratorHelper->createAdministrator($request);

                if ($administrator) {
                    $response = [
                        'code' => '00',
                        'message' => 'Success'
                    ];
                    $http_code = 201;
                } else {
                    $response = [
                        'code' => '99',
                        'message' => 'Unable to create administrator'
                    ];
                    $http_code = 500;
                }
            } catch (\Throwable $th) {
                $response = [
                    'code' => '98',
                    'message' => 'Exception: '.$th->getMessage()
                ];
                $http_code = 500;
            }
        }

        return response()->json(['response' => $response], $http_code);
    }

    public function login(Request $request) {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email',
            'password' => 'required|string'
        ]);

        if ($validator->fails()) {
            $response = [
                'code' => '01',
                'message' => 'Validation error',
                'details' => [
                    'errors' => $validator->errors()
                ]
            ];
            $http_code = 400;
        } else {
            if ($token = auth()->attempt($validator->validated())) {
                $response = [
                    'code' => '00',
                    'message' => 'Success',
                    'token' => [
                        'access_token' => $token,
                        'token_type' => 'bearer'
                    ]
                ];
                $http_code = 200;
            } else {
                $response = [
                    'code' => '02',
                    'message' => 'Authentication failed'
                ];
                $http_code = 401;
            }
        }

        return response()->json(['response' => $response], $http_code);
    }

    public function logout() {
        auth()->logout();

        $response = [
            'code' => '00',
            'message' => 'Success'
        ];
        $http_code = 200;

        return response()->json(['response' => $response], $http_code);
    }

    public function getEmail() {
        $response = [
            'code' => '00',
            'message' => 'Success',
            'details' => [
                'email' => auth()->user()->email
            ]
        ];
        $http_code = 200;

        return response()->json(['response' => $response], $http_code);
    }

}
