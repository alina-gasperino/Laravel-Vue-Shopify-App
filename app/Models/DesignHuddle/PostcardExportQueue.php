<?php

namespace App\Models\DesignHuddle;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PostcardExportQueue extends Model
{
    use HasFactory;

    protected $table = 'design_huddle_postcard_export_queue';

    protected $guarded = [];
}
