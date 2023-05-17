<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

class UserRepository
{
    public function createUsers(array $data): void
    {
        User::insert($data);
    }

    public function allUsers(): Collection
    {
        return User::all();
    }

    public function findUserByEmail(string $email): User
    {
        return User::where(['email' => $email])->first();
    }

    public function findUserById(string $id): User
    {
        return User::find($id);
    }

    public function updateUser(array $data): void
    {
        $user = $this->findUserByEmail($data['email']);
        $user->name = $data['name'];
        $user->country_id = $data['country_id'];
        $user->save();
    }

    public function createToken(string $email): string
    {
        $user = $this->findUserByEmail($email);
        $token = uniqid(strval(rand())) . hash('ripemd160', strval(rand()));
        $user->remember_token = $token;
        $user->email_verified_at = time();
        $user->save();

        return $token;
    }

    public function getUserByTokenAndEmail(string $email, string $token): User|bool
    {
        $user = $this->findUserByEmail($email);
        if ($token === $user->remember_token) {
            return $user;
        }

        return false;
    }

    public function listProjectsUserLinkedTo(int $id): Collection
    {
        return User::find($id)->projects()->where(['user_id' => $id])->get();
    }

    public function deleteUser(array $user): void
    {
        $user = $this->findUserByEmail($user['email']);
        $user->delete();
    }
}
