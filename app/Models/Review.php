<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    protected $fillable = [
        'review_job_id',
        'pull_request_id',
        'score',
        'status',
        'summary',
        'ai_model',
        'tokens_used',
        'review_duration',
    ];
}
