<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Document extends Model
{
    use softDeletes;
    use HasFactory;

    protected $fillable = [
        'name',
        'page_number',
        'issue_date',
        'post_date',
        'receive_date',
        'is_paid',
        'path',
        'type',
        'size',
        'user_id',
        'post_office_box_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function postOfficeBox()
    {
        return $this->belongsTo(PostOfficeBox::class);
    }

    public function activityLogs()
    {
        return $this->hasMany(ActivityLog::class);
    }

    public function isDownloadedOnce()
    {
        return $this->activityLogs()->where('activity_type', 'download')->exists();
    }
}
