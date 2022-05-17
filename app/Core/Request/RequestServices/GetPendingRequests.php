<?php

namespace App\Core\Request\RequestServices;

use App\Models\Request;

trait GetPendingRequests {

    public function getPendingRequests() {
        return Request::where('approved', false)->get();
    }

}
