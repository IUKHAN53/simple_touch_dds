<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PostOfficeBox extends Model
{
    use softDeletes;
    use HasFactory;

    protected $fillable = [
        'user_id',
        'is_active',
        'box_type'
    ];
}
