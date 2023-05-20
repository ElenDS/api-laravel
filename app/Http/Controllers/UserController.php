<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\DeleteUserRequest;
use App\Http\Requests\UserRequest;
use App\Repositories\UserRepository;
use App\Services\CreateUserDataService;
use App\Services\SendVerifyLinkService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
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
        $dataUsers = $dataService->createDataUsers($request->get('users'));

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
        SendVerifyLinkService $emailVerification
    ): JsonResponse {
        $email = $this->userRepository->findUserById(intval($id))->email;

        if (!$emailVerification->findVerifyLink($email, $hash)) {
            return response()->json(['error' => 'Invalid URL']);
        }

        $token = $this->userRepository->createToken($email);
        $emailVerification->deleteLink($email, $hash);

        return response()->json(['status' => '200', 'token' => $token]);
    }

    public function updateUsers(
        UserRequest $request,
        CreateUserDataService $dataService,
    ): JsonResponse {
        try {
            $dataUsers = $dataService->createDataUsers($request->get('users'));

            DB::beginTransaction();
            foreach ($dataUsers as $data) {
                $this->userRepository->updateUser($data);
            }
            DB::commit();
        } catch (Exception $exception) {
            DB::rollBack();

            return response()->json(['error message' => $exception->getMessage()]);
        }

        return response()->json(['status' => '200', 'message' => 'Users successfully updated']);
    }

    public function listUsers(): JsonResponse
    {
        return response()->json([
            'status' => '200',
            'users' => $this->userRepository->allUsers()
        ]);
    }

    public function deleteUsers(DeleteUserRequest $request): JsonResponse
    {
        try {
            DB::beginTransaction();
            foreach ($request->get('users') as $user) {
                $this->userRepository->deleteUser($user);
            }
            DB::commit();
        } catch (Exception $exception) {
            DB::rollBack();

            return response()->json(['error message' => $exception->getMessage()]);
        }

        return response()->json(['status' => '200', 'message' => 'Users successfully deleted']);
    }
}
