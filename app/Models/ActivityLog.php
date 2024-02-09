<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ActivityLog extends Model
{
    use softDeletes;
    use HasFactory;

    protected $fillable = [
        'user_id',
        'activity_type',
        'description',
        'document_id',
        'post_office_box_id',
        'ip_address'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function document()
    {
        return $this->belongsTo(Document::class);
    }

    public function postOfficeBox()
    {
        return $this->belongsTo(PostOfficeBox::class);
    }
}
