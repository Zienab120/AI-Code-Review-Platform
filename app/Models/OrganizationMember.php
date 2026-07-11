<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrganizationMember extends Model
{
    protected $fillable = [
        'organization_id',
        'user_id',
        'role',
        'joined_at',
    ];

    public function organization()
    {
        return $this->belongsTo(Oragnization::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
