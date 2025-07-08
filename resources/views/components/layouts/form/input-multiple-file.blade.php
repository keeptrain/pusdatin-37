@props([
    'title' => null,
    'model' => null,
    'required' => false,
    'optional' => false,
    'template' => false,
    'filePath' => null,
    'multiple' => true,
    'accept' => '.pdf,.doc,.docx,.jpg,.jpeg,.png,.gif',
    'maxSize' => '5MB'
])

<div class="flex flex-1 p-4 bg-zinc-50 justify-between border-t-1 border-l-1 border-r-1 rounded-t-lg mt-6">
    <h4 class="text-sm font-medium text-gray-700">
        {{ $title }}
        @if ($required)
            <span class="ml-2 px-2 py-1 text-xs bg-red-100 text-red-700 rounded-md">Wajib</span>
        @endif
        @if ($optional)
            <span class="ml-2 px-2 py-1 text-xs bg-gray-200 text-gray-600 rounded-md">Boleh Menyusul</span>
        @endif
    </h4>
</div>

<div class="border-2 border-dashed hover:border-zinc-400 
    {{ $errors->has($model) ? 'border-red-500 ' : 'border-gray-300 ' }} cursor-pointer">
    <div x-data="{
        uploading: false,
        progress: 0,
        files: [],
        uploadedFiles: [],
        
        init() {
            // Initialize with existing files if any
            @if($multiple)
                this.uploadedFiles = @json($this->get($model, []));
            @else
                if (@json($this->get($model))) {
                    this.uploadedFiles = [@json($this->get($model))];
                }
            @endif
        },
        
        getFileIcon(fileName) {
            const ext = fileName.split('.').pop().toLowerCase();
            const icons = {
                pdf: 'document-text',
                doc: 'document-text',
                docx: 'document-text',
                jpg: 'photograph',
                jpeg: 'photograph',
                png: 'photograph',
                gif: 'photograph',
                zip: 'archive',
                rar: 'archive',
                txt: 'document-text',
                xls: 'table',
                xlsx: 'table',
                ppt: 'presentation-chart-bar',
                pptx: 'presentation-chart-bar'
            };
            return icons[ext] || 'document';
        },
        
        getFileColor(fileName) {
            const ext = fileName.split('.').pop().toLowerCase();
            const colors = {
                pdf: 'text-red-500',
                doc: 'text-blue-500',
                docx: 'text-blue-500',
                jpg: 'text-green-500',
                jpeg: 'text-green-500',
                png: 'text-green-500',
                gif: 'text-green-500',
                zip: 'text-yellow-500',
                rar: 'text-yellow-500',
                txt: 'text-gray-500',
                xls: 'text-green-600',
                xlsx: 'text-green-600',
                ppt: 'text-orange-500',
                pptx: 'text-orange-500'
            };
            return colors[ext] || 'text-gray-500';
        },
        
        formatFileSize(bytes) {
            if (bytes === 0) return '0 Bytes';
            const k = 1024;
            const sizes = ['Bytes', 'KB', 'MB', 'GB'];
            const i = Math.floor(Math.log(bytes) / Math.log(k));
            return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
        },
        
        handleDrop(e) {
            e.preventDefault();
            const droppedFiles = Array.from(e.dataTransfer.files);
            this.addFiles(droppedFiles);
        },
        
        handleFileSelect(e) {
            const selectedFiles = Array.from(e.target.files);
            this.addFiles(selectedFiles);
        },
        
        addFiles(newFiles) {
            @if($multiple)
                // Add to existing files array
                newFiles.forEach(file => {
                    this.files.push({
                        file: file,
                        name: file.name,
                        size: file.size,
                        uploading: false,
                        progress: 0,
                        id: Date.now() + Math.random()
                    });
                });
            @else
                // Replace existing file
                if (newFiles.length > 0) {
                    this.files = [{
                        file: newFiles[0],
                        name: newFiles[0].name,
                        size: newFiles[0].size,
                        uploading: false,
                        progress: 0,
                        id: Date.now()
                    }];
                }
            @endif
            
            // Update the file input
            this.$refs.fileInput.files = this.createFileList();
            this.$refs.fileInput.dispatchEvent(new Event('change'));
        },
        
        createFileList() {
            const dt = new DataTransfer();
            this.files.forEach(fileObj => {
                dt.items.add(fileObj.file);
            });
            return dt.files;
        },
        
        removeFile(index) {
            this.files.splice(index, 1);
            this.$refs.fileInput.files = this.createFileList();
            this.$refs.fileInput.dispatchEvent(new Event('change'));
        },
        
        removeUploadedFile(index) {
            this.uploadedFiles.splice(index, 1);
            $wire.set('{{ $model }}', @if($multiple) this.uploadedFiles @else this.uploadedFiles[0] || null @endif);
        },
        
        clearAllFiles() {
            this.files = [];
            this.uploadedFiles = [];
            this.$refs.fileInput.value = '';
            $wire.set('{{ $model }}', @if($multiple) [] @else null @endif);
        }
    }"
    x-on:livewire-upload-start="uploading = true"
    x-on:livewire-upload-finish.prevent="event => { 
        uploadedFiles = @if($multiple) event.detail.uploadedFiles || [] @else [event.detail.uploadedFile].filter(Boolean) @endif;
        uploading = false;
        files = [];
    }"
    x-on:livewire-upload-error="uploading = false"
    x-on:livewire-upload-progress="progress = $event.detail.progress"
    class="w-full">
        
        <!-- Hidden Input -->
        <input type="file" 
               wire:model="{{ $model }}" 
               wire:key="input-file-{{ $model }}" 
               class="hidden" 
               x-ref="fileInput"
               @if($multiple) multiple @endif
               accept="{{ $accept }}"
               x-on:change="handleFileSelect($event)">
        
        <!-- Drop Zone (show when no files or multiple allowed) -->
        <template x-if="files.length === 0 && uploadedFiles.length === 0 @if($multiple) || true @endif">
            <div class="flex items-center justify-center w-full px-4 py-8 text-center transition bg-white cursor-pointer focus:outline-none hover:bg-gray-50"
                x-on:dragover.prevent
                x-on:dragenter.prevent
                x-on:drop="handleDrop"
                x-on:click="$refs.fileInput.click()">
                <div class="space-y-3">
                    <div class="mx-auto w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center">
                        <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                        </svg>
                    </div>
                    <div>
                        <flux:button variant="outline" size="sm">
                            Klik untuk upload @if($multiple) file @else file @endif
                        </flux:button>
                        <p class="text-sm text-gray-500 mt-2">atau drag and drop</p>
                        <p class="text-xs text-gray-400 mt-1">{{ $accept }}, max {{ $maxSize }}</p>
                    </div>
                </div>
            </div>
        </template>
        
        <!-- Uploaded Files List -->
        <div class="space-y-2 p-4" x-show="uploadedFiles.length > 0">
            <template x-for="(file, index) in uploadedFiles" :key="'uploaded-' + index">
                <div class="flex items-center justify-between p-3 bg-green-50 border border-green-200 rounded-lg">
                    <div class="flex items-center gap-3 flex-1 min-w-0">
                        <div class="flex-shrink-0">
                            <svg class="w-6 h-6 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-green-800 truncate" x-text="file.name || file"></p>
                            <p class="text-xs text-green-600">Berhasil diupload</p>
                        </div>
                    </div>
                    <button type="button" 
                            x-on:click="removeUploadedFile(index)"
                            class="flex-shrink-0 p-1 text-green-600 hover:text-red-600 hover:bg-red-50 rounded-full transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            </template>
        </div>
        
        <!-- Pending Files List -->
        <div class="space-y-2 p-4" x-show="files.length > 0">
            <template x-for="(fileObj, index) in files" :key="fileObj.id">
                <div class="flex items-center justify-between p-3 bg-blue-50 border border-blue-200 rounded-lg">
                    <div class="flex items-center gap-3 flex-1 min-w-0">
                        <div class="flex-shrink-0">
                            <svg class="w-6 h-6" :class="getFileColor(fileObj.name)" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-blue-800 truncate" x-text="fileObj.name"></p>
                            <p class="text-xs text-blue-600" x-text="formatFileSize(fileObj.size)"></p>
                        </div>
                    </div>
                    <button type="button" 
                            x-on:click="removeFile(index)"
                            class="flex-shrink-0 p-1 text-blue-600 hover:text-red-600 hover:bg-red-50 rounded-full transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            </template>
        </div>
        
        <!-- Add More Files Button (for multiple files) -->
        @if($multiple)
        <div class="p-4 border-t border-gray-200 bg-gray-50" x-show="files.length > 0 || uploadedFiles.length > 0">
            <div class="flex items-center justify-between">
                <button type="button" 
                        x-on:click="$refs.fileInput.click()"
                        class="flex items-center gap-2 px-4 py-2 text-sm text-blue-600 hover:text-blue-700 hover:bg-blue-50 rounded-lg transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Tambah File Lain
                </button>
                
                <button type="button" 
                        x-on:click="clearAllFiles()"
                        class="flex items-center gap-2 px-4 py-2 text-sm text-red-600 hover:text-red-700 hover:bg-red-50 rounded-lg transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                    </svg>
                    Hapus Semua
                </button>
            </div>
        </div>
        @endif
        
        <!-- Progress Bar -->
        <template x-if="uploading">
            <div class="p-4 bg-blue-50 border-t border-blue-200">
                <div class="flex items-center gap-3 mb-2">
                    <svg class="w-5 h-5 text-blue-500 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                    </svg>
                    <span class="text-sm font-medium text-blue-700">Mengupload...</span>
                </div>
                <div class="w-full bg-blue-200 rounded-full h-2">
                    <div class="bg-blue-500 h-2 rounded-full transition-all duration-300" :style="'width: ' + progress + '%'"></div>
                </div>
                <div class="text-xs text-blue-600 mt-1 text-right" x-text="progress + '%'"></div>
            </div>
        </template>
        
        <!-- Error Message -->
        @error($model)
            <div class="p-4 bg-red-50 border-t border-red-200">
                <p class="text-sm text-red-600 flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    {{ $message }}
                </p>
            </div>
        @enderror
    </div>
</div>

{{ $slot }}