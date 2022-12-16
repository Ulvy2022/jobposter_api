<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Dispatchable, InteractsWithSockets;

class JobsPoster extends Model
{
    use HasFactory;

    public function User()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function cv()
    {
        return $this->hasMany(UserCv::class);
    }

    protected $fillable = [
        'job_title',
        'company_location',
        'job_type',
        'job_closedate',
        'company_name',
        'salary',
        'contact_name',
        'contact_email',
        'job_description',
        'job_address',
        'job_requirement',
        'expired_at',
        'post_at',
        'activate'
    ];


}
