<?php

namespace App\Core\User\UserServices;

use App\Models\User;

trait CreateUser {

    public function createUser($data) {
        return User::create($data);
    }

}
