<?php

namespace App\Core\User\UserServices;

use App\Models\User;

trait UpdateUser {

    public function updateUser($user_id, $data) {
        $user = User::find($user_id);

        $user->update($data);

        return $user;
    }

}
