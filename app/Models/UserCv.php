<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserCv extends Model
{
    use HasFactory;

    public function jobPoster()
    {
        return $this->belongsTo(JobsPoster::class, );
    }

    public function user()
    {
        return $this->hasMany(User::class, );
    }
}
