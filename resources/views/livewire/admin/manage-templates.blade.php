<div class="lg:p-3">
    <!-- Main Content Area -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h3 class="text-xl font-semibold text-gray-800">Manage Document Templates</h3>
            <p class="text-gray-600 text-sm">Create and manage templates displayed on the landing page</p>
        </div>
    </div>

    <!-- Filter Controls -->
    <div class="bg-white rounded-lg shadow-sm p-4 mb-6">
        <div class="flex flex-wrap items-center justify-between">
            <div class="w-full md:w-auto flex items-center mb-4 md:mb-0">
               
            </div>
            <div class="w-full md:w-auto flex items-center">

            </div>
        </div>
    </div>

    <!-- Templates Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <!-- Template Card - Active -->
        @for ($x = 0 ; $x <= 3 ; $x++)
        <div class="bg-white rounded-lg shadow-sm overflow-hidden border border-gray-200 hover:bg-zinc-50 cursor-pointer">
            <div class="relative">
                <img src="https://cdn.prod.website-files.com/5fda3048302e579473bfb454/604127094e44c9854095cad4_Project%20Documentation%20Template.webp" alt="Invoice Template" class="w-full h-48 object-cover">
                <div class="absolute top-3 right-3">
                    <span class="bg-green-500 text-white text-xs px-2 py-1 rounded-full">Published</span>
                </div>
            </div>
            <div class="p-4">
                <div class="flex justify-between items-center mb-2">
                    <h4 class="text-lg font-semibold">Invoice Template</h4>
                    <span class="text-sm text-gray-500">PDF</span>
                </div>
                <p class="text-gray-600 text-sm mb-4">Standard invoice template with company branding and itemized
                    billing sections.</p>
                <div class="flex items-center text-sm text-gray-500 mb-4">
                    <span class="flex items-center mr-4">
                        <i class="fas fa-calendar-alt mr-1"></i> Updated: May 1, 2025
                    </span>
                    <span class="flex items-center">
                        <i class="fas fa-download mr-1"></i> 324 uses
                    </span>
                </div>
                <div class="flex justify-between">
                    <div class="flex space-x-2">
                        <button class="text-blue-600 hover:bg-blue-50 p-2 rounded-lg" title="Edit">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="text-gray-600 hover:bg-gray-50 p-2 rounded-lg" title="Preview">
                            <i class="fas fa-eye"></i>
                        </button>
                        <button class="text-gray-600 hover:bg-gray-50 p-2 rounded-lg" title="Duplicate">
                            <i class="fas fa-copy"></i>
                        </button>
                    </div>
                    <div class="flex space-x-2">
                        <button class="text-red-600 hover:bg-red-50 p-2 rounded-lg" title="Archive">
                            <i class="fas fa-archive"></i>
                        </button>
                        <button class="text-green-600 hover:bg-green-50 p-2 rounded-lg" title="Published">
                            <i class="fas fa-toggle-on"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        @endfor

        <!-- Add Template Card -->
        <div class="bg-gray-50 rounded-lg border border-dashed border-gray-300 flex items-center justify-center h-96">
            <div class="text-center p-6">
                <div
                    class="w-16 h-16 bg-blue-100 text-blue-500 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-plus text-2xl"></i>
                </div>
                <h4 class="text-lg font-medium text-gray-800 mb-2">Create New Template</h4>
                <p class="text-gray-500 mb-4">Upload a new PDF template or create one from scratch</p>
                <button class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">
                    Create Template
                </button>
            </div>
        </div>
    </div>


</div>