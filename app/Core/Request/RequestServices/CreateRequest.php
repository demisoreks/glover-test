<?php

namespace App\Core\Request\RequestServices;

use App\Models\Request;

trait CreateRequest {

    public function createRequest($data) {
        return Request::create($data);
    }

}
