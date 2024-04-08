<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Document;
use Illuminate\Http\Request;

class DocumentsController extends Controller
{
    public function index()
    {
        $documents = Document::query()->with('activityLogs','user', 'postOfficeBox')->latest()->get();
        return view('admin.documents.index')->with(['documents' => $documents]);
    }

    public function delete($id)
    {
        Document::query()->findOrFail($id)->delete();
        return redirect()->route('admin.documents.index')->with('success', 'Document deleted successfully');
    }

    public function deleteAll()
    {
        Document::truncate();
        return redirect()->route('admin.documents.index')->with('success', 'All documents deleted successfully');
    }


}
