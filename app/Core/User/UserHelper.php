<?php

namespace App\Core\User;

use App\Core\User\UserServices\CreateUser;
use App\Core\User\UserServices\DeleteUser;
use App\Core\User\UserServices\UpdateUser;

class UserHelper {

    use CreateUser, UpdateUser, DeleteUser;

}
