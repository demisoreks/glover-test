<?php

namespace App\Core\Request\RequestServices;

use App\Models\Request;

trait UpdateRequest {

    public function updateRequest($request_id, $data) {
        $request = Request::find($request_id);

        $request->update($data);

        return $request;
    }

}
