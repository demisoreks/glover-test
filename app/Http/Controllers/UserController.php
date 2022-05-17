<?php

namespace App\Http\Controllers;

use App\Core\Request\RequestHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{

    private $requestHelper;

    public function __construct(RequestHelper $requestHelper)
    {
        $this->middleware('auth:api');

        $this->requestHelper = $requestHelper;
    }

    public function create(Request $request) {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'email' => 'required|string|email|max:100|unique:users'
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
            $data = [
                'details' => json_encode($request->input()),
                'type' => 'create',
                'inserted_by' => auth()->user()->id,
                'inserted_at' => now(),
                'approved_by' => 0
            ];

            $response = $this->createRequest($data);

            if ($response['code'] == "00") {
                $http_code = 201;
            } else {
                $http_code = 500;
            }
        }

        return response()->json(['response' => $response], $http_code);
    }

    public function update(Request $request, $user_id) {
        $validator = Validator::make($request->all(), [
            'first_name' => 'string|max:100',
            'last_name' => 'string|max:100'
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
            $data = [
                'user_id' => $user_id,
                'details' => json_encode($request->input()),
                'type' => 'update',
                'inserted_by' => auth()->user()->id,
                'inserted_at' => now(),
                'approved_by' => 0
            ];

            $response = $this->createRequest($data);

            if ($response['code'] == "00") {
                $http_code = 201;
            } else {
                $http_code = 500;
            }
        }

        return response()->json(['response' => $response], $http_code);
    }

    public function delete(Request $request, $user_id) {
        $data = [
            'user_id' => $user_id,
            'details' => '',
            'type' => 'delete',
            'inserted_by' => auth()->user()->id,
            'inserted_at' => now(),
            'approved_by' => 0
        ];

        $response = $this->createRequest($data);

        if ($response['code'] == "00") {
            $http_code = 201;
        } else {
            $http_code = 500;
        }

        return response()->json(['response' => $response], $http_code);
    }

    private function createRequest($data) {
        try {
            if ($this->requestHelper->createRequest($data)) {
                $response = [
                    'code' => '00',
                    'message' => 'Success'
                ];
            } else {
                $response = [
                    'code' => '99',
                    'message' => 'Unable to create request'
                ];
            }
        } catch (\Throwable $th) {
            $response = [
                'code' => '98',
                'message' => 'Exception: '.$th->getMessage()
            ];
        }

        return $response;
    }

}
