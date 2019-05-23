<?php

namespace App;

use App\Traits\RecordActivity;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use RecordActivity;

    protected $fillable = ["title", "body", "user_id"];

    public function path()
    {
        return "/projects/{$this->id}";
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    public function activities()
    {
        return $this->hasMany(Activity::class);
    }

    public function members()
    {
        return $this->belongsToMany(User::class, 'project_members');
    }
}
