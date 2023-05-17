<?php

declare(strict_types=1);

namespace App\Services;

use App\DTO\ProjectsDTO;
use App\Repositories\LabelRepository;
use App\Repositories\UserRepository;

class ProjectsEntryDataService
{
    public function __construct(protected UserRepository $userRepository, protected LabelRepository $labelRepository)
    {
    }

    public function createEntryData(ProjectsDTO $projectsDTO): array
    {
        $entryData = [];

        foreach ($projectsDTO->getProjects() as $project) {
            $members = array_reduce($project['members'], function ($members, $member) {
                $members[] = $this->userRepository->findUserByEmail($member['email'])->id;

                return $members;
            }, []);

            $labels = array_reduce($project['labels'], function ($labels, $label) {
                $labels[] = $this->labelRepository->findLabelByName($label['name'])->id;

                return $labels;
            }, []);

            $entryData[] = [
                'name' => $project['name'],
                'created_by_user' => $projectsDTO->getUserId(),
                'members' => $members,
                'labels' => $labels,
            ];
        }

        return $entryData;
    }
}
