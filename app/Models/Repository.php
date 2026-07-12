<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Repository extends Model
{
    protected $fillable = [
        'organization_id',
        'name',
        'slug',
        'description',
        'visibility',
        'default_branch',
        'github_repo_id',
        'clone_url',
        'ssh_url',
        'is_active',
        'last_synced_at',
    ];

    public function organization()
    {
        return $this->belongsTo(Oragnization::class);
    }

    public function PullRequests()
    {
        return $this->hasMany(PullRequest::class);
    }

    public function webhooks()
    {
        return $this->hasMany(Webhook::class);
    }
}
