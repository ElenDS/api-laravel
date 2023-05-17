<?php

declare(strict_types=1);

namespace App\DTO;

class ProjectsDTO
{
    public function __construct(protected array $projects, protected int $userId)
    {
    }


    /**
     * @return array
     */
    public function getProjects(): array
    {
        return $this->projects;
    }

    /**
     * @return int
     */
    public function getUserId(): int
    {
        return $this->userId;
    }
}
