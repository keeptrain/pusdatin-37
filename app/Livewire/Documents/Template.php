<?php

namespace App\Livewire\Documents;

use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\Locked;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Models\Template as ModelsTemplate;

class Template extends Component
{
    use WithFileUploads;

    #[Locked]
    public $selectedTemplateId = null;

    public $templates;

    public $name = '';

    public $isActive;

    public $partNumber = '';

    public $file;

    public $updateName = '';
    public bool $updateIsActive;
    public $updateFile;
    private $updatePartNumber;

    public function mount()
    {
        $this->templates = ModelsTemplate::filterByRole(auth()->user())->get();
    }

    public function edit($id)
    {
        $template = ModelsTemplate::findOrFail($id);
        $this->selectedTemplateId = $template->id;
        $this->updateName = $template->name;
        $this->updateIsActive = $template->is_active;
        $this->updatePartNumber = $template->part_number;
    }

    public function update()
    {
        $this->validate([
            'updateName' => 'required',
            'updateIsActive' => 'required|boolean',
            'updateFile' => 'nullable|file',
        ]);

        if (!$this->updateIsActive) {
            $activeTemplates = ModelsTemplate::where('part_number', $this->updatePartNumber)
                ->where('id', '!=', $this->updatePartNumber) // Jangan termasuk diri sendiri
                ->where('is_active', true)
                ->exists();

            // Jika tidak ada template aktif lainnya, tambahkan error
            if (!$activeTemplates) {
                $this->addError('updateIsActive', 'Setidaknya satu template dengan part number ini harus aktif.');
                return;
            }
        }

        $selectedTemplateId = $this->selectedTemplateId;

        DB::transaction(function () use ($selectedTemplateId) {
            $template = ModelsTemplate::findOrFail($selectedTemplateId);

            if ($this->updateIsActive) {
                ModelsTemplate::where('part_number', $template->part_number)
                    ->where('id', '!=', $selectedTemplateId) // Jangan nonaktifkan diri sendiri
                    ->update(['is_active' => false]);
            }

            // Update data template
            $template->update([
                'name' => $this->updateName,
                'is_active' => $this->updateIsActive,
            ]);

            // Simpan file ke storage dan database jika ada file baru
            if ($this->updateFile) {
                $fileName = $this->updateFile->getClientOriginalName();
                $filePath = Storage::disk('public')->putFileAs('templates', $this->updatefile, $fileName);

                // Update path file di database
                $template->update([
                    'file_path' => $filePath,
                ]);
            }

            session()->flash('status', [
                'variant' => 'success',
                'message' => 'Berhasil melakukan update!',
            ]);

            return $this->redirect("/system/templates", navigate: true);
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

    public function delete($id)
    {
        DB::transaction(function () use ($id) {
            $template = ModelsTemplate::findOrFail($id);

            // Pengecekan: Apakah ini satu-satunya template dengan part_number yang sama?
            $otherTemplates = ModelsTemplate::where('part_number', $template->part_number)
                ->where('id', '!=', $id) // Jangan termasuk diri sendiri
                ->exists();

            if (!$otherTemplates) {
                // Tambahkan pesan error ke session
                session()->flash('status', [
                    'variant' => 'error',
                    'message' => 'Tidak dapat menghapus template ini karena tidak ada template lain dengan part number yang sama.',
                ]);

                return $this->redirect("/system/templates", navigate: true);
            }
            
            $template->delete();
            Storage::disk('public')->delete($template->file_path);

            session()->flash('status', [
                'variant' => 'success',
                'message' => 'Berhasil menghapus template',
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
