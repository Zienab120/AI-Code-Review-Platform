<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReviewComment extends Model
{
    protected $fillable = [
        'comment',
        'status',
        'file',
        'line',
        'review_id',
    ];
}
