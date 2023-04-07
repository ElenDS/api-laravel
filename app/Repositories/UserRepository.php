<?php

namespace App\Repositories;

use App\Models\User;

class UserRepository
{
    public function createUsers($data)
    {
       User::insert($data);
    }
    public function findUserByEmail($email)
    {
        return User::where(['email' => $email])->first();
    }
    public function findUser($id)
    {
        return User::find($id);
    }

    public function updateUser($data)
    {
        // TODO: Implement updateUser() method.
    }
}
