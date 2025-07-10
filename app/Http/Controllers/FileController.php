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
            $attachment = DiscussionAttachment::findOrFail($fileId);

            if (!Storage::exists($attachment->path) && !$attachment->isImage()) {
                return abort(404);
            }

            return response()->file($attachment->getUrl());
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
