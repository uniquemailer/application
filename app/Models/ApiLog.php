<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class ApiLog extends Model
{
    use HasFactory;

    protected $guarded = ['transaction_id', 'request', 'user_id', 'service_id', 'status'];  
 
    protected $casts = [
        'request' => 'array'
    ];

    protected $table = 'api_audits';

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getUserNameAttribute()
    {
        return $this->user?->name;
    }

    public function service()
    {
        return $this->belongsTo(Service::class);
    }
    
    public function getServiceNameAttribute()
    {
        return $this->service?->name;
    }
}
