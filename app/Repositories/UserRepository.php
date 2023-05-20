<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\User;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Database\Eloquent\Collection;
use mysql_xdevapi\Exception;

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

    public function findUserById(int $id): User
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

        $secretKey = config('app.jwt_secret');
        $payload = [
            'user_id' => $user->id
        ];

        $token = JWT::encode($payload, $secretKey, 'HS256');

        $user->remember_token = $token;
        $user->email_verified_at = time();
        $user->save();

        return $token;
    }

    public function getUserByToken(string $token): User|string
    {
        $secretKey = config('app.jwt_secret');
        try {
            $decoded = JWT::decode($token, new Key($secretKey, 'HS256'));
            $userId = $decoded->user_id;
            $user = $this->findUserById($userId);
        } catch (Exception $exception) {
            return $exception->getMessage();
        }

        return $user;
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
