<?php

namespace App\Http\Controllers\Admin;

use App\Models\Template;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class TemplateController extends Controller
{

    public function index()
    {
        $user = auth()->user();

        $templates = Template::filterByRole($user)->get();

        return view('admin.template.index', compact('templates'));
    }

    public function create()
    {
        return view('admin.template.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required'
        ]);

        Template::create(
            [
                'name' => $request->name,
                'part_number' => $request->part_number,
                'file_path' => $request->file('file_path')->store('templates', 'public')
            ]
        );

        return redirect()->route('manage.templates');
    }

    public function download(int $id)
    {
        $template = Template::findOrFail($id);

        if (!$template) {
            abort(404, 'Template not found.');
        }

        if (!Storage::disk('public')->exists($template->file_path)) {
            abort(404, 'File not found in storage.');
        }

        return response()->download(Storage::disk('public')->path($template->file_path));
    }
}
