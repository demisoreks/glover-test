<?php

namespace App\Http\Controllers;

use App\Core\Request\RequestHelper;
use App\Core\User\UserHelper;
use Illuminate\Http\Request;

class RequestController extends Controller
{

    private $requestHelper;
    private $userHelper;

    public function __construct(RequestHelper $requestHelper, UserHelper $userHelper)
    {
        $this->middleware('auth:api');

        $this->requestHelper = $requestHelper;
        $this->userHelper = $userHelper;
    }

    public function get($request_id) {
        $request = $this->requestHelper->getRequest($request_id);

        if ($request) {
            $response = [
                'code' => '00',
                'message' => 'Success',
                'details' => [
                    'request' => $request
                ]
            ];
            $http_code = 200;
        } else {
            $response = [
                'code' => '04',
                'message' => 'Not found'
            ];
            $http_code = 404;
        }

        return response()->json(['response' => $response], $http_code);
    }

    public function pending() {
        $requests = $this->requestHelper->getPendingRequests();

        if ($requests->count() > 0) {
            $response = [
                'code' => '00',
                'message' => 'Success',
                'details' => [
                    'request' => $requests
                ]
            ];
            $http_code = 200;
        } else {
            $response = [
                'code' => '04',
                'message' => 'Not found'
            ];
            $http_code = 404;
        }

        return response()->json(['response' => $response], $http_code);
    }

    public function approve($request_id) {
        $request = $this->requestHelper->getRequest($request_id);

        if (!$request) {
            $response = [
                'code' => '04',
                'message' => 'Not found'
            ];
            $http_code = 404;
        } else {
            if ($request->inserted_by == auth()->user()->id) {
                $response = [
                    'code' => '05',
                    'message' => 'You cannot approve a request you initiated'
                ];
                $http_code = 401;
            } else {
                try {
                    if ($request->type == "create") {
                        $this->userHelper->createUser(json_decode($request->details, true));
                    } else if ($request->type == "update") {
                        $this->userHelper->updateUser($request->user_id, json_decode($request->details, true));
                    } else if ($request->type == "delete") {
                        $this->userHelper->deleteUser($request->user_id);
                    }

                    $this->requestHelper->updateRequest($request_id, [
                        'approved' => true,
                        'approved_by' => auth()->user()->id,
                        'approved_at' => now(),
                        'success' => true,
                        'remark' => ''
                    ]);

                    $response = [
                        'code' => '00',
                        'message' => 'Success'
                    ];
                    $http_code = 200;
                } catch (\Throwable $th) {
                    $this->requestHelper->updateRequest($request_id, [
                        'approved' => true,
                        'approved_by' => auth()->user()->id,
                        'approved_at' => now(),
                        'success' => false,
                        'remark' => $th->getMessage()
                    ]);

                    $response = [
                        'code' => '98',
                        'message' => 'Exception: '.$th->getMessage()
                    ];
                    $http_code = 500;
                }
            }
        }

        return response()->json(['response' => $response], $http_code);
    }

    public function decline($request_id) {
        $request = $this->requestHelper->getRequest($request_id);

        if (!$request) {
            $response = [
                'code' => '04',
                'message' => 'Not found'
            ];
            $http_code = 404;
        } else {
            if ($request->inserted_by == auth()->user()->id) {
                $response = [
                    'code' => '05',
                    'message' => 'You cannot approve a request you initiated'
                ];
                $http_code = 401;
            } else {
                try {
                    $this->requestHelper->updateRequest($request_id, [
                        'approved' => true,
                        'approved_by' => auth()->user()->id,
                        'approved_at' => now(),
                        'success' => false,
                        'remark' => 'DECLINED'
                    ]);

                    $response = [
                        'code' => '00',
                        'message' => 'Success'
                    ];
                    $http_code = 200;
                } catch (\Throwable $th) {
                    $this->requestHelper->updateRequest($request_id, [
                        'approved' => true,
                        'approved_by' => auth()->user()->id,
                        'approved_at' => now(),
                        'success' => false,
                        'remark' => $th->getMessage()
                    ]);

                    $response = [
                        'code' => '98',
                        'message' => 'Exception: '.$th->getMessage()
                    ];
                    $http_code = 500;
                }
            }
        }

        return response()->json(['response' => $response], $http_code);
    }

}
