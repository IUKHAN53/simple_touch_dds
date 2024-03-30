<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Document;
use App\Models\User;
use Illuminate\Http\Request;

class ActivityLogsController extends Controller
{
    public function index()
    {
        if (auth()->user()->role == User::ROLE_USER) {
            $logs = ActivityLog::query()->where('user_id', auth()->id())->with('document')->latest()->get();
            return view('admin.activity_logs.index')->with(['logs' => $logs]);
        }else{
            $logs = ActivityLog::query()->with('document')->latest()->get();
            return view('admin.activity_logs.index')->with(['logs' => $logs]);
        }
    }

    public function delete($id)
    {

        ActivityLog::findOrFail($id)->delete();
        return redirect()->route('admin.activity-logs.index')->with('success', 'Activity log deleted successfully.');
    }

    public function download($id)
    {
        $log = ActivityLog::findOrFail($id);
        $activityLog = new ActivityLog();
        $activityLog->user_id = auth()->id();
        $activityLog->activity_type = 'download';
        $activityLog->description = 'Document downloaded: ' . $log->document->name;
        $activityLog->document_id = $log->document->id;
        $activityLog->ip_address = request()->ip();
        $activityLog->save();

        return response()->download(storage_path('app/' . $log->document->path));
    }

}
