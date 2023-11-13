<?php

namespace App\Repositories;

use App\Repositories\Repository;
use App\Models\User;

class UserRepository extends Repository 
{
    public function __construct(private User $user)
    {
    }

    public function UserEmailVerified($param)
    {
        $return = $this->user->where('email', $param)->first();

        if(empty($return->confirmation_token)){
            return true;
        }else{
            return false;
        }

    }

}