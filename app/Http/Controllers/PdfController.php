<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PdfController extends Controller
{
    public function show($path)
    {
        $fullPath = storage_path('app/public/' . $path);

        if (!Storage::exists($fullPath)) {
            abort(404);
        }
    
        return response()->file($fullPath);
    }
}
