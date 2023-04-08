<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Label;
use Illuminate\Database\Eloquent\Collection;

class LabelRepository
{
    public function createLabel(array $label, int $user_id): void
    {
        $newLabel = new Label();
        $newLabel->name = $label['name'];
        $newLabel->created_by_user = $user_id;
        $newLabel->save();
    }

    public function updateLabel(Label $label, array $newLabel): void
    {
        $label->name = $newLabel['name'];
        $label->save();
    }

    public function findLabelByName(string $name): Label
    {
        return Label::where(['name' => $name])->first();
    }

    public function listLabelsByOwnerId(int $id): Collection
    {
        return Label::where(['created_by_user' => $id])->get();
    }

    public function deleteLabel(Label $label): void
    {
        $label->delete();
    }
}
