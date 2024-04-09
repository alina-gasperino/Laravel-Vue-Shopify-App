<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Visitor extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function ips()
    {
        return $this->hasMany(VisitorIp::class, 'visitor_id');
    }
}
