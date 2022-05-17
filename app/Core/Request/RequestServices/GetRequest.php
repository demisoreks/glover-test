<?php

namespace App\Core\Request\RequestServices;

use App\Models\Request;

trait GetRequest {

    public function getRequest($request_id) {
        return Request::find($request_id);
    }

}
