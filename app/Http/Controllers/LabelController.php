<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\LabelRequest;
use App\Repositories\LabelRepository;
use App\Repositories\ProjectRepository;
use App\Repositories\UserRepository;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\JsonResponse;

class LabelController extends Controller
{
    public function __construct(
        protected LabelRepository $labelRepository,
        protected UserRepository $userRepository,
        protected ProjectRepository $projectRepository
    ) {
    }

    public function listLabels(Request $request): JsonResponse
    {
        $user = $request->user;

        return response()->json([
            'status' => '200',
            'labels' => $this->labelRepository->listLabelsByOwnerId($user->id)
        ]);
    }

    public function createLabels(LabelRequest $request): JsonResponse
    {
        try {
            $user = $request->user;

            DB::beginTransaction();
            foreach ($request->get('labels') as $label) {
                $this->labelRepository->createLabel($label, $user->id);
            }
            DB::commit();
        } catch (Exception $exception) {
            DB::rollBack();

            return response()->json(['error message' => $exception->getMessage()]);
        }

        return response()->json(['status' => '200', 'message' => 'Labels successfully created']);
    }

    public function linkLabelsToProjects(Request $request): JsonResponse
    {
        try {
            $user = $request->user;

            DB::beginTransaction();
            foreach ($request->get('labels') as $labelData) {
                $label = $this->labelRepository->findLabelByName($labelData['name']);
                if ($label->created_by_user !== $user->id) {
                    throw new Exception("The user does not have permission for this action");
                }

                array_walk($labelData['projects'], function ($project, $key, $labelID) {
                    $this->projectRepository->linkLabelToProject($project, $labelID);
                }, $label->id);
            }
            DB::commit();
        } catch (Exception $exception) {
            DB::rollBack();

            return response()->json(['error message' => $exception->getMessage()]);
        }

        return response()->json(['status' => '200', 'message' => 'Labels successfully linked to projects']);
    }

    public function deleteLabels(Request $request): JsonResponse
    {
        try {
            $user = $request->user;

            DB::beginTransaction();
            foreach ($request->get('labels') as $label) {
                $label = $this->labelRepository->findLabelByName($label['name']);
                if ($label->created_by_user !== $user->id) {
                    throw new Exception("The user does not have permission for this action");
                }
                $this->labelRepository->deleteLabel($label);
            }
            DB::commit();
        } catch (Exception $exception) {
            DB::rollBack();

            return response()->json(['error message' => $exception->getMessage()]);
        }

        return response()->json(['status' => '200', 'message' => 'Labels successfully deleted']);
    }
}
