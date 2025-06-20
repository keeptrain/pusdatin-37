<!-- Service Cards -->
<div class="grid md:grid-cols-2 gap-6 mt-6">
    <!-- Information & Data Service -->
    <div class="bg-white rounded-lg p-3 shadow-xs border-slate-200 hover:shadow-lg transition-all duration-300 group">
        <div class="flex items-start justify-between mb-4">
            <div
                class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center group-hover:bg-blue-200 transition-colors">
                <x-lucide-code class="w-6 text-blue-700" />
            </div>

        </div>
        <h3 class="text-xl font-medium text-slate-500 mb-2">Layanan Sistem Informasi & Data</h3>
        <p class="text-slate-600 mb-4">Pengajuan permohonan layanan sistem informasi dan data</p>
        <button @click="handleSIDataRequest()"
            class="w-full mt-4 inline-block border bg-zinc-50 text-slate-600 text-sm font-bold px-4 py-2 rounded-md hover:bg-blue-50 transition cursor-pointer">
            Ajukan
        </button>
    </div>

    <!-- Public Relations Service -->
    <div class="bg-white rounded-lg p-3 shadow-xs border-slate-200 hover:shadow-lg transition-all duration-300 group">
        <div class="flex items-start justify-between mb-4">
            <div
                class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center group-hover:bg-purple-200 transition-colors">
                <x-lucide-file-video class="w-6 text-purple-700" />
            </div>
        </div>
        <h3 class="text-xl font-meidum text-slate-500 mb-2">Layanan Kehumasan</h3>
        <p class="text-slate-600 mb-4">Pengajuan permohonan layanan kehumasan</p>
        <a href="{{ route('pr.form') }}"
            class="w-full mt-4 text-center inline-block border bg-zinc-50 text-slate-600 text-sm font-bold px-4 py-2 rounded-md hover:bg-purple-50 transition">
            Ajukan
        </a>
    </div>
</div>