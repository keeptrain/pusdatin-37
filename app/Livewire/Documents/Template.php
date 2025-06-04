<?php

namespace App\Livewire\Documents;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Models\Template as ModelsTemplate;
use Livewire\WithFileUploads;

class Template extends Component
{
    use WithFileUploads;

    public $templates;

    public $name = '';

    public $isActive;

    public $partNumber;

    public $file;


    public function mount()
    {
        $this->templates = ModelsTemplate::filterByRole(auth()->user())->get();
    }

    public function update($id)
    {
        $this->validate([
            'name' => 'required',
            'file' => 'nullable|file',
        ]);

        DB::transaction(function () use ($id) {
            $template = ModelsTemplate::findOrFail($id);

            $template->update([
                'name' => $this->name,
            ]);

            // Simpan file ke storage dan database
            if ($this->file) {
                $filePath = $this->file->store('templates', 'public');

                // Update path file di database
                $template->update([
                    'file_path' => $filePath,
                ]);
            }

            session()->flash('status', [
                'variant' => 'success',
                'message' => 'Berhasil melakukan update!',
            ]);

            return redirect()->route('manage.templates');
        });
    }

    public function save()
    {
        $this->validate(
            [
                'name' => 'required|string',
                'partNumber' => 'required|numeric',
                'file' => 'required|file',
                // 'isActive' => 'required|boolean',
            ]
        );

        // $existingActiveTemplate = ModelsTemplate::where('part_number', $this->partNumber)
        //     ->where('is_active', true)
        //     ->exists();

        // if ($existingActiveTemplate && $this->isActive) {
        //     $this->addError('isActive', 'Part number ini sudah digunakan oleh template yang aktif.');
        //     return;
        // }

        DB::transaction(function () {
            $fileName = $this->file->getClientOriginalName();
            $filePath = Storage::disk('public')->putFileAs('templates', $this->file, $fileName);

            // Simpan data ke database
            ModelsTemplate::create([
                'name' => $this->name,
                'part_number' => $this->partNumber,
                'file_path' => $filePath,
                'is_active' => false,
            ]);

            session()->flash('status', [
                'variant' => 'success',
                'message' => 'Berhasil membuat template baru',
            ]);

            return $this->redirect("/system/templates", navigate: true);
        });
    }

    public function download(int $id)
    {
        $template = ModelsTemplate::findOrFail($id);

        if (!$template) {
            abort(404, 'Template not found.');
        }

        if (!Storage::disk('public')->exists($template->file_path)) {
            abort(404, 'File not found in storage.');
        }

        return response()->download(Storage::disk('public')->path($template->file_path));
    }
}
