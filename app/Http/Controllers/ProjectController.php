<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\ProjectRequest;
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

    public function createProjects(ProjectRequest $request): JsonResponse
    {
        try {
            $user = $this->userRepository->getUserByTokenAndEmail($request->get('email'), $request->get('token'));
            if (!$user) {
                throw new Exception("Invalid token");
            }

            $projectsData = $this->getProjectData($request, $user->id);

            DB::beginTransaction();
            foreach ($projectsData as $project) {
                $this->projectRepository->createProject($project);
            }
            DB::commit();
        } catch (Exception $exception) {
            DB::rollBack();

            return response()->json(['error message' => $exception->getMessage()]);
        }

        return response()->json(['status' => '200', 'message' => 'Projects successfully created']);
    }

    public function getProjectData(ProjectRequest $request, int $id): array
    {
        $data = [];
        foreach ($request->get('projects') as $project) {
            $members = array_reduce($project['members'], function ($members, $member) {
                $members[] = $this->userRepository->findUserByEmail($member['email'])->id;

                return $members;
            }, []);

            $labels = array_reduce($project['labels'], function ($labels, $label) {
                $labels[] = $this->labelRepository->findLabelByName($label['name'])->id;

                return $labels;
            }, []);

            $data[] = [
                'name' => $project['name'],
                'created_by_user' => $id,
                'members' => $members,
                'labels' => $labels,
            ];
        }

        return $data;
    }

    public function updateProjects(ProjectRequest $request): JsonResponse
    {
        try {
            $user = $this->userRepository->getUserByTokenAndEmail($request->get('email'), $request->get('token'));
            if (!$user) {
                throw new Exception("Invalid token");
            }

            $projectData = $this->getProjectData($request, $user->id);

            DB::beginTransaction();
            foreach ($projectData as $project) {
                $projectUserID = $this->projectRepository->findProjectByName($project['name'])->created_by_user;
                if ($projectUserID !== $user->id) {
                    throw new Exception("The user does not have permission for this action");
                }
                $this->projectRepository->updateProject($project);
            }
            DB::commit();
        } catch (Exception $exception) {
            DB::rollBack();

            return response()->json(['error message' => $exception->getMessage()]);
        }

        return response()->json(['status' => '200', 'message' => 'Projects successfully updated']);
    }
    public function linkProjectsToUsers(Request $request): JsonResponse
    {
        try {
            $user = $this->userRepository->getUserByTokenAndEmail($request->get('email'), $request->get('token'));
            if (!$user) {
                throw new Exception("Invalid token");
            }

            DB::beginTransaction();
            foreach ($request->get('projects') as $projectData) {
                $project = $this->projectRepository->findProjectByName($projectData['name']);
                if ($project->created_by_user !== $user->id) {
                    throw new Exception("The user does not have permission for this action");
                }

                array_walk($projectData['users'], function($user, $key, $project){
                    $user = $this->userRepository->findUserByEmail($user['email']);
                    $this->projectRepository->linkMemberToProject($project, $user->id);
                }, $project);

            }
            DB::commit();
        } catch (Exception $exception) {
            DB::rollBack();

            return response()->json(['error message' => $exception->getMessage()]);
        }

        return response()->json(['status' => '200', 'message' => 'Projects successfully linked to users']);
        }

    public function deleteProjects(Request $request): JsonResponse
    {
        try {
            $user = $this->userRepository->getUserByTokenAndEmail($request->get('email'), $request->get('token'));
            if (!$user) {
                throw new Exception("Invalid token");
            }

            DB::beginTransaction();
            foreach ($request->get('projects') as $project) {
                $project = $this->projectRepository->findProjectByName($project['name']);
                if ($project->created_by_user !== $user->id) {
                    throw new Exception("The user does not have permission for this action");
                }
                $this->projectRepository->deleteProject($project);
            }
            DB::commit();
        } catch (Exception $exception) {
            DB::rollBack();

            return response()->json(['error message' => $exception->getMessage()]);
        }

        return response()->json(['status' => '200', 'message' => 'Projects successfully deleted']);
    }
}
