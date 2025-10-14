<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Response;

class AttachmentController extends Controller
{
    public function download($filename)
    {
        if (Storage::disk('public')->exists('attachments/' . $filename)) {
            return Storage::disk('public')->download('attachments/' . $filename);
        } else {
            abort(404, 'File not found.');
        }
    }
}

