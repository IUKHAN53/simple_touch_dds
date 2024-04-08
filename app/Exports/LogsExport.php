<?php

namespace App\Exports;

use App\Models\ActivityLog;
use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class LogsExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        $logs = ActivityLog::with('user')->latest()->get();
        return $logs->map(function ($log) {
            return [
                'created_at' => $log->created_at,
                'user_id' => $log->user->name,
                'activity_type' => ucfirst($log->activity_type) . 'ed',
                'description' => optional($log->document)->name
            ];
        });
    }

    public function headings(): array
    {
        return ["Time", "User", "Activity", "Description"];
    }
}
