<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Jobs\SendEmailVerificationJob;
use App\Models\Link;
use App\Repositories\CountryRepository;
use App\Repositories\UserRepository;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class UserController
{
    public function createUsers(
        UserRequest $request,
        UserRepository $userRepository,
        CountryRepository $countryRepository
    ) {
        $dataUsers = [];
        foreach ($request['users'] as $user) {
            $dataUsers[] = [
                'name' => $user['username'],
                'email' => $user['email'],
                'country_id' => $countryRepository->findByCountryCode($user['country_code'])
            ];
        }
        try {
            DB::beginTransaction();
            $userRepository->createUsers($dataUsers);
            DB::commit();
        } catch (Exception $exception) {
            DB::rollBack();

            return response('Invalid data')->header('error message', $exception->getMessage());
        }

        foreach ($dataUsers as $user) {
            $this->sendVerificationMail($user['email']);
        }

        return response('Users created');
    }

    public function sendVerificationMail(string $email): void
    {
        $uniqId = uniqid();
        $link = new Link();
        $link->email = $email;
        $link->uniqid = $uniqId;
        $link->save();

        $verifLink = 'http://api.localhost/' . $email . '/' . $uniqId;

        SendEmailVerificationJob::dispatch($verifLink, $email);
    }

    public function verifyUser(string $email, string $uniqid, UserRepository $userRepository): JsonResponse
    {
        $link = Link::where([
            'uniqid' => $uniqid,
            'email' => $email
        ])->first();
        if (!$link) {
            return response()->json(['error' => 'Invalid URL']);
        }

        $user = $userRepository->findUserByEmail($email);
        $token = uniqid(strval(rand())) . hash('ripemd160', strval(rand()));
        $user->remember_token = $token;
        $user->email_verified_at = time();
        $user->save();
        $link->delete();

        return response()->json(['status' => '200', 'token' => $token]);
    }
}
