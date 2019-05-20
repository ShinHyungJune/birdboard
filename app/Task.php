<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable = ['project_id','body', 'completed'];

    protected $touches = ['project'];
    // project의 자식인 task를 수정하거나 생성하면, project의 updated_at이 갱신됨.

    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}
