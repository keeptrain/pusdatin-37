<?php

namespace App\Http\Controllers\Admin;

use App\Models\Template;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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

    public function update(Request $request, $id)
    {
        // Validasi input
        $request->validate([
            'name' => 'required',
            'file' => 'required|file',
        ]);

        try {
            DB::transaction(function () use ($request, $id) {
                $template = Template::findOrFail($id);

                $template->update([
                    'name' => $request->name,
                ]);

                // Simpan file ke storage dan database
                if ($request->hasFile('file')) {
                    $file = $request->file('file');
                    $filePath = $file->store('templates', 'public'); // Simpan file di folder "templates"

                    // Update path file di database
                    $template->update([
                        'file_path' => $filePath,
                    ]);
                }

                session()->flash('status', [
                    'variant' => 'success',
                    'message' => 'Berhasil melakukan update!',
                ]);
            });

            return redirect()->route('manage.templates');
        } catch (\Exception $e) {
            session()->flash('status', [
                'variant' => 'danger',
                'message' => 'Terjadi kesalahan saat melakukan update.',
            ]);

            return back();
        }
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
