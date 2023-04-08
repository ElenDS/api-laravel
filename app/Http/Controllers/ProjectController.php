<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\ProjectRequest;
use App\Models\Project;
use App\Repositories\LabelRepository;
use App\Repositories\ProjectRepository;
use App\Repositories\UserRepository;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProjectController extends Controller
{
    public function __construct(
        protected ProjectRepository $projectRepository,
        protected UserRepository $userRepository,
        protected LabelRepository $labelRepository
    ) {
    }

    public function listProjects(Request $request): JsonResponse
    {
        $user = $this->userRepository->getUserByTokenAndEmail($request->get('email'), $request->get('token'));
        if (!$user) {
            return response()->json(['error' => 'The user does not have permission for this action']);
        }

        return response()->json([
            'status' => '200',
            'projects_created_by_user' => $this->projectRepository->listProjectsByOwnerId($user->id),
            'projects_user_linked_to' => $this->userRepository->listProjectsUserLinkedTo($user->id)
        ]);
    }

    public function createProject(ProjectRequest $request): JsonResponse
    {
        try {
            $user = $this->userRepository->getUserByTokenAndEmail($request->get('email'), $request->get('token'));
            if (!$user) {
                throw new Exception("Invalid token");
            }
            $newProjectData = $this->getProjectData($request, $user->id);

            DB::beginTransaction();
            $this->projectRepository->createProject($newProjectData);
            DB::commit();
        } catch (Exception $exception) {
            DB::rollBack();

            return response()->json(['error message' => $exception->getMessage()]);
        }

        return response()->json(['status' => '200', 'message' => 'Project successfully created']);
    }

    public function getProjectData(Request $request, int $id): array
    {
        $members = [];
        foreach ($request->get('members') as $member) {
            $members[] = $this->userRepository->findUserByEmail($member['email'])->id;
        }

        $labels = [];
        foreach ($request->get('labels') as $label) {
            $labels[] = $this->labelRepository->findLabelByName($label['name'])->id;
        }
        return [
            'name' => $request->get('project')['name'],
            'created_by_user' => $id,
            'members' => $members,
            'labels' => $labels,
        ];
    }

    public function updateProject(ProjectRequest $request, Project $project): JsonResponse
    {
        try {
            $user = $this->userRepository->getUserByTokenAndEmail($request->get('email'), $request->get('token'));
            if (!$user) {
                throw new Exception("Invalid token");
            } elseif ($project->created_by_user !== $user->id) {
                throw new Exception("The user does not have permission for this action");
            }

            $projectData = $this->getProjectData($request, $user->id);

            DB::beginTransaction();
            $this->projectRepository->updateProject($project, $projectData);
            DB::commit();
        } catch (Exception $exception) {
            DB::rollBack();

            return response()->json(['error message' => $exception->getMessage()]);
        }

        return response()->json(['status' => '200', 'message' => 'Project successfully updated']);
    }

    public function deleteProject(Request $request, Project $project): JsonResponse
    {
        try {
            $user = $this->userRepository->getUserByTokenAndEmail($request->get('email'), $request->get('token'));
            if (!$user) {
                throw new Exception("Invalid token");
            } elseif ($project->created_by_user !== $user->id) {
                throw new Exception("The user does not have permission for this action");
            }

            DB::beginTransaction();
            $this->projectRepository->deleteProject($project);
            DB::commit();
        } catch (Exception $exception) {
            DB::rollBack();

            return response()->json(['error message' => $exception->getMessage()]);
        }

        return response()->json(['status' => '200', 'message' => 'Project successfully deleted']);
    }
}
