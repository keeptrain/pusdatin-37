<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Template;

class TemplateController extends Controller
{
    public function index()
    {
        $templates = Template::all();
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
}
