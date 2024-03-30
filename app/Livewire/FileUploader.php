<?php

namespace App\Livewire;

use App\Models\ActivityLog;
use App\Models\Document;
use App\Models\PostOfficeBox;
use App\Models\User;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Livewire\WithFileUploads;

class FileUploader extends Component
{
    use WithFileUploads;

    public $uploaded_file;
    public $name;
    public $amount;
    public $post_office_box_id;
    public $page_number;
    public $is_paid;
    public $logs;

    protected $admin_rules = [
        'name' => 'required',
        'amount' => 'required|numeric',
        'post_office_box_id' => 'required',
        'page_number' => 'required|numeric',
        'is_paid' => 'required',
        'uploaded_file' => 'required|file|max:10240'
    ];

    protected $user_rules = [
        'name' => 'required',
        'page_number' => 'required|numeric',
        'uploaded_file' => 'required|file|max:10240'
    ];

    public function finishUpload($name, $tmpPath)
    {
        $this->cleanupOldUploads();

        // Instead of mapping through an array, create a single TemporaryUploadedFile
        $this->uploaded_file = TemporaryUploadedFile::createFromLivewire($tmpPath);

        $this->emitSelf('upload:finished', $name, $this->uploaded_file->getFilename());

        $this->syncInput($name, $this->uploaded_file);
    }


    public function render()
    {
        $poBox = auth()->user()->postOfficeBoxes()->first();
        if (auth()->user()->role == User::ROLE_USER) {
            $poBox = auth()->user()->postOfficeBoxes()->first();
            $files = $poBox->documents()->latest()->get();
        } else {
            $files = Document::latest()->get();
        }
        return view('livewire.file-uploader')->with([
            'userFiles' => $files,
            'pobs' => $this->getPOBs()
        ]);
    }

    public function saveDocument()
    {
        if (auth()->user()->role == User::ROLE_USER) {
            $this->post_office_box_id = auth()->user()->postOfficeBoxes()->first()->id;
            $this->validate($this->admin_rules);
        }else{
            $this->validate($this->user_rules);
        }
        $originalName = $this->uploaded_file->getClientOriginalName();
        $basename = pathinfo($originalName, PATHINFO_FILENAME);
        $extension = $this->uploaded_file->getClientOriginalExtension();
        $uniqueName = $basename . '_' . time() . '_' . auth()->id() . '.' . $extension;

        $document = new Document();
        $document->name = $this->name;
        $document->file_name =  $this->uploaded_file->getClientOriginalName();
        $document->path = $this->uploaded_file->storeAs('documents', $uniqueName);
        $document->type = $this->uploaded_file->getMimeType();
        $document->size = $this->uploaded_file->getSize();
        $document->amount = $this->amount ?? 0;
        $document->is_paid = $this->is_paid ?? null;
        $document->page_number = $this->page_number;
        $document->post_office_box_id = $this->post_office_box_id;
        $document->user_id = auth()->id();
        $document->issue_date = now();
        $document->post_date = now();
        $document->receive_date = now();
        $document->save();

        // Create a new activity log
        $activityLog = new ActivityLog();
        $activityLog->user_id = auth()->id();
        $activityLog->activity_type = 'upload';
        $activityLog->description = 'Document uploaded: ' . $document->name;
        $activityLog->document_id = $document->id;
        $activityLog->post_office_box_id = $document->post_office_box_id;
        $activityLog->ip_address = request()->ip();
        $activityLog->save();

        $this->reset('uploaded_file', 'name', 'amount', 'post_office_box_id', 'page_number', 'is_paid');
    }

    public function downloadDocument(Document $document)
    {
        $activityLog = new ActivityLog();
        $activityLog->user_id = auth()->id();
        $activityLog->activity_type = 'download';
        $activityLog->description = 'Document downloaded: ' . $document->name;
        $activityLog->document_id = $document->id;
        $activityLog->ip_address = request()->ip();
        $activityLog->save();
        $this->reset('uploaded_file', 'name', 'amount', 'post_office_box_id', 'page_number', 'is_paid');
        return response()->download(storage_path('app/' . $document->path));
    }

    public function viewActivity(Document $document)
    {
        $this->logs = ActivityLog::where('document_id', $document->id)->get();
    }

    private function getPOBs()
    {
        if (auth()->user()->role == User::ROLE_ADMIN) {
            return PostOfficeBox::all()->pluck('box_type', 'id');
        }
    }

    public function removeUploadedFile()
    {
        $this->cleanupOldUploads();
        $this->reset('uploaded_file');
    }

    public function updatedPageNumber($value)
    {
        if ($value < 0) {
            $this->page_number = 0;
        }
    }

    public function updatedAmount($value)
    {
        if ($value < 0) {
            $this->amount = 0;
        }
    }


}
