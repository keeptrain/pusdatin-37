<?php

namespace App\Http\Controllers;

use App\Models\DiscussionAttachment;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Storage;

class FileController extends Controller
{
    use AuthorizesRequests;

    public function show($fileId, Request $request)
    {
        // Missing authorization

        try {
            $document = DiscussionAttachment::findOrFail($fileId);
            $path = $document->path;
            $disk = Storage::disk('local');
            if (!$disk->exists($path)) {
                abort(404);
            }

            // return Storage::disk('local')->response($path);

            // Using response to open in new tab
            return response()->file($disk->path($path));
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
