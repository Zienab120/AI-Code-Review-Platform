<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PullRequest extends Model
{
    protected $fillabe = [
        'title',
        'description',
        'repository_id',
        'github_pr_number',
        'author',
        'branch',
        'base_branch',
        'head_commit',
        'status',
        'opened_at',
        'closed_at',
        'merged_at',
    ];

    protected $casts = [
        'opened_at' => 'datetime',
        'closed_at' => 'datetime',
        'merged_at' => 'datetime',
    ];

    public function repository()
    {
        return $this->belongsTo(Repository::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }
}
