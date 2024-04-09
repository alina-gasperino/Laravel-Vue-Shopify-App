<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VisitorIp extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function visitor()
    {
        return $this->belongsTo(Visitor::class);
    }
}
