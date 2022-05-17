<?php

namespace App\Core\Administrator\AdministratorServices;

use App\Models\Administrator;
use Illuminate\Support\Facades\Hash;

trait CreateAdministrator {

    public function createAdministrator($request) {
        return Administrator::create([
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);
    }

}
