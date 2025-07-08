<?php

namespace App\Livewire\Documents;

use App\Models\Template;
use GuzzleHttp\Psr7\MimeType;
use Livewire\Component;
use Livewire\Attributes\Title;
use Livewire\WithFileUploads;
use Livewire\Attributes\Locked;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Services\FileUploadServices;

#[Title('Atur Template')]
class ManageTemplate extends Component
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
        $this->templates = Template::filterByRole(auth()->user())->get();
    }

    public function save(FileUploadServices $services)
    {
        if (auth()->user()->hasRole('pr_verifier')) {
            $this->partNumber = 6;
        }

        $this->validate(
            [
                'name' => 'required|string',
                'partNumber' => 'required|numeric',
                'file' => 'required|file|mimes:doc,docx',
            ]
        );

        DB::transaction(function () use ($services) {
            $file = $this->file;
            $fileName = $services->generateFileName($file, $file->extension());
            $filePath = Storage::disk('local')->putFileAs('templates', $file, $fileName);

            Template::create([
                'name' => $this->name,
                'part_number' => $this->partNumber,
                'file_path' => $filePath,
                'mime_type' => MimeType::fromFilename($filePath),
                'is_active' => false,
            ]);

            session()->flash('status', [
                'variant' => 'success',
                'message' => 'Berhasil membuat template baru',
            ]);
        });

        $this->redirectRoute("manage.templates", navigate: true);
    }

    public function edit($id)
    {
        $template = Template::findOrFail($id);
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
            $activeTemplates = Template::where('part_number', $this->updatePartNumber)
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
            $template = Template::findOrFail($selectedTemplateId);

            if ($this->updateIsActive) {
                Template::where('part_number', $template->part_number)
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

    public function delete($id)
    {
        DB::transaction(function () use ($id) {
            $template = Template::findOrFail($id);

            // Check if there are other templates with the same part number
            $otherTemplates = Template::where('part_number', $template->part_number)
                ->where('id', '!=', $id)
                ->exists();

            if (!$otherTemplates) {
                session()->flash('status', [
                    'variant' => 'error',
                    'message' => 'Tidak dapat menghapus template ini karena tidak ada template lain dengan bagian template yang sama.',
                ]);

                return $this->redirectRoute("manage.templates", navigate: true);
            }

            $template->delete();
            Storage::disk('local')->delete($template->file_path);

            session()->flash('status', [
                'variant' => 'success',
                'message' => 'Berhasil menghapus template',
            ]);

        });

        $this->redirectRoute('manage.templates', navigate: true);
    }

    public function download(int $id)
    {
        $template = Template::select('file_path')->findOrFail($id);

        // Validation file
        if (!Storage::disk('local')->exists($template->file_path)) {
            abort(404, 'File not found in storage.');
        }

        $filePath = Storage::disk('local')->path($template->file_path);
        return response()->download($filePath);
    }
}
