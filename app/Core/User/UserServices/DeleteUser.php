<?php

namespace App\Core\User\UserServices;

use App\Models\User;

trait DeleteUser {

    public function deleteUser($user_id) {
        $user = User::find($user_id);

        $user->delete();
    }

}
