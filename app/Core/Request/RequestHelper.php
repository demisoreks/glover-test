<?php

namespace App\Core\Request;

use App\Core\Request\RequestServices\CreateRequest;
use App\Core\Request\RequestServices\GetPendingRequests;
use App\Core\Request\RequestServices\GetRequest;
use App\Core\Request\RequestServices\UpdateRequest;

class RequestHelper {

    use CreateRequest, GetRequest, GetPendingRequests, UpdateRequest;

}
