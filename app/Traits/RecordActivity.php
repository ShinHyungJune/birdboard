<?php

namespace App\Traits;

trait RecordActivity
{
    public function recordActivity($description)
    {
        $this->activities()->create([
            "description" => $description,
            "project_id" => class_basename($this) === 'Project' ? $this->id : $this->project->id,
        ]);
    }
}