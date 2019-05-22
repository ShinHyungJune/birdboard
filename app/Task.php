<?php

namespace App;

use App\Traits\RecordActivity;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use RecordActivity;

    protected $fillable = ['project_id','body', 'completed'];

    protected $touches = ['project'];

    protected $casts = ["completed" => "boolean"];
    // project의 자식인 task를 수정하거나 생성하면, project의 updated_at이 갱신됨.

    public function path()
    {
        return "/tasks/".$this->id;
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function activities()
    {
        return $this->morphMany(Activity::class, 'subject');
    }
}
