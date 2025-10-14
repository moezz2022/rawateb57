<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class FileController extends Controller
{  
    public function upload(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'file' => 'required|file|mimes:zip,rar,pdf,doc,docx,xlsx,xlsm,xls,csv,png,jpg,jpeg|max:30720'
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => 'File validation failed', 'messages' => $validator->errors()], 400);
        }
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $filename = time() . '-' . $file->getClientOriginalName();
            $filePath = $file->storeAs('uploads', $filename, 'public');
            return response()->json(['success' => 'File uploaded successfully', 'filename' => $filename, 'path' => $filePath]);
        }
    
        return response()->json(['error' => 'No file uploaded'], 400);
    }
}
