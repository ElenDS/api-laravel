<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Project;
use Illuminate\Database\Eloquent\Collection;

class ProjectRepository
{
    public function createProject(array $data): void
    {
        $project = new Project();
        $project->name = $data['name'];
        $project->created_by_user = $data['created_by_user'];
        $project->save();
        $project->members()->attach($data['members']);
        $project->labels()->attach($data['labels']);
    }

    public function updateProject(Project $project, array $data): void
    {
        $project->name = $data['name'];
        $project->save();
        $project->members()->sync($data['members']);
        $project->labels()->sync($data['labels']);
    }

    public function deleteProject(Project $project): void
    {
        $project->delete();
    }

    public function listProjectsByOwnerId(int $id): Collection
    {
        return Project::where(['created_by_user' => $id])->get();
    }

}
