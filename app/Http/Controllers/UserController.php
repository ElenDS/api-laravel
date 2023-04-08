<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Repositories\UserRepository;
use App\Services\CreateUserDataService;
use App\Services\SendVerifyLinkService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class UserController
{
    public function __construct(protected UserRepository $userRepository)
    {
    }

    public function createUsers(
        UserRequest $request,
        CreateUserDataService $dataService,
        SendVerifyLinkService $linkService
    ): JsonResponse {
        $dataUsers = $dataService->createDataUser($request->get('users'));

        try {
            DB::beginTransaction();
            $this->userRepository->createUsers($dataUsers);
            DB::commit();
        } catch (Exception $exception) {
            DB::rollBack();

            return response()->json(['error message' => $exception->getMessage()]);
        }

        foreach ($dataUsers as $user) {
            $id = $this->userRepository->findUserByEmail($user['email'])->id;
            $linkService->sendVerificationMail($id, $user['email']);
        }

        return response()->json(['status' => '200', 'message' => 'Users successfully registered']);
    }

    public function verifyUser(
        string $id,
        string $hash,
        SendVerifyLinkService $linkService
    ): JsonResponse {
        $email = $this->userRepository->findUserById($id)->email;

        if (!$linkService->findVerifyLink($email, $hash)) {
            return response()->json(['error' => 'Invalid URL']);
        }

        $token = $this->userRepository->createToken($email);
        $linkService->deleteLink($email, $hash);

        return response()->json(['status' => '200', 'token' => $token]);
    }

    public function updateUser(
        UserRequest $request,
        CreateUserDataService $dataService,
    ): JsonResponse {
        try {
            $user = $this->userRepository->getUserByTokenAndEmail($request->get('email'), $request->get('token'));
            if (!$user) {
                throw new Exception("Invalid token");
            }

            $dataUsers = $dataService->createDataUser($request->get('users'));

            DB::beginTransaction();
            foreach ($dataUsers as $data) {
                $this->userRepository->updateUser($data);
            }
            DB::commit();
        } catch (Exception $exception) {
            DB::rollBack();

            return response()->json(['error message' => $exception->getMessage()]);
        }

        return response()->json(['status' => '200', 'message' => 'User successfully updated']);
    }

    public function listUsers(): JsonResponse
    {
        return response()->json([
            'status' => '200',
            'users' => $this->userRepository->allUsers()
        ]);
    }
}
