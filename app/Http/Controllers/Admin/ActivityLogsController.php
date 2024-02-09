<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Document;
use Illuminate\Http\Request;

class ActivityLogsController extends Controller
{
    public function index()
    {
        $logs = ActivityLog::query()->with('document')->latest()->get();
        return view('admin.activity_logs.index')->with(['logs' => $logs]);
    }

    public function delete($id)
    {
        ActivityLog::findOrFail($id)->delete();
        return redirect()->route('admin.activity-logs.index')->with('success', 'Activity log deleted successfully.');
    }
}
