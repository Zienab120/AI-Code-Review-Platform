<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Oragnization extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'logo',
        'description',
        'owner_id',
    ];

    public function owner()
    {
        return $this->belongsTo(User::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    public function repositories()
    {
        return $this->hasMany(Repository::class);
    }
}
