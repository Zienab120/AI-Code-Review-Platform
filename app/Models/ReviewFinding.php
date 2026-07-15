<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReviewFinding extends Model
{
    protected $fillabe = [
        'review_id',
        'type',
        'severity',
        'file',
        'line',
        'title',
        'description',
        'suggestion',
        'rule',
        'category',
    ];
}
