<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\LabelRequest;
use App\Models\Label;
use App\Repositories\LabelRepository;
use App\Repositories\UserRepository;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\JsonResponse;

class LabelController extends Controller
{
    public function __construct(protected LabelRepository $labelRepository, protected UserRepository $userRepository)
    {
    }

    public function listLabels(Request $request): JsonResponse
    {
        $user = $this->userRepository->getUserByTokenAndEmail($request->get('email'), $request->get('token'));
        if (!$user) {
            return response()->json(['error' => 'The user does not have permission for this action']);
        }

        return response()->json([
            'status' => '200',
            'labels' => $this->labelRepository->listLabelsByOwnerId($user->id)
        ]);
    }

    public function createLabel(LabelRequest $request): JsonResponse
    {
        try {
            $user = $this->userRepository->getUserByTokenAndEmail($request->get('email'), $request->get('token'));
            if (!$user) {
                throw new Exception("Invalid token");
            }

            DB::beginTransaction();
            $this->labelRepository->createLabel($request->get('label'), $user->id);
            DB::commit();
        } catch (Exception $exception) {
            DB::rollBack();

            return response()->json(['error message' => $exception->getMessage()]);
        }

        return response()->json(['status' => '200', 'message' => 'Label successfully created']);
    }

    public function updateLabel(Request $request, Label $label): JsonResponse
    {
        try {
            $user = $this->userRepository->getUserByTokenAndEmail($request->get('email'), $request->get('token'));
            if (!$user) {
                throw new Exception("Invalid token");
            } elseif ($label->created_by_user !== $user->id) {
                throw new Exception("The user does not have permission for this action");
            }

            DB::beginTransaction();
            $this->labelRepository->updateLabel($label, $request->get('label'));
            DB::commit();
        } catch (Exception $exception) {
            DB::rollBack();

            return response()->json(['error message' => $exception->getMessage()]);
        }

        return response()->json(['status' => '200', 'message' => 'Label successfully updated']);
    }

    public function deleteLabel(Request $request, Label $label): JsonResponse
    {
        try {
            $user = $this->userRepository->getUserByTokenAndEmail($request->get('email'), $request->get('token'));
            if (!$user) {
                throw new Exception("Invalid token");
            } elseif ($label->created_by_user !== $user->id) {
                throw new Exception("The user does not have permission for this action");
            }

            DB::beginTransaction();
            $this->labelRepository->deleteLabel($label);
            DB::commit();
        } catch (Exception $exception) {
            DB::rollBack();

            return response()->json(['error message' => $exception->getMessage()]);
        }

        return response()->json(['status' => '200', 'message' => 'Label successfully deleted']);
    }
}
