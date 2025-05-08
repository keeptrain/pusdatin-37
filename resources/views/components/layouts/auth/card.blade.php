<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">

<head>
    @include('partials.head')
</head>

<body class="min-h-screen antialiased dark:bg-linear-to-b dark:from-neutral-950 dark:to-neutral-900">
    <nav id="navbar" class="fixed top-0 left-0 right-0 z-50 transition-all duration-300 bg-transparent ">
        <div class="container mx-auto px-4 sm:px-6 lg:px-10">
            <div class="flex justify-between items-center py-4">
                <div class="flex items-center">
                    <a href="/" class="flex items-center">
                        <span
                            class="text-2xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-[#364872] to-[#697AA4]">Pusdatin</span>
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <div class="bg-muted flex min-h-svh flex-col items-center justify-center gap-6 p-6 md:p-10">
        <div class="flex w-full max-w-md flex-col gap-6">
            <div class="flex flex-col gap-6">
                <div class="rounded-xl bg-white dark:bg-stone-950 dark:border-stone-800 text-stone-800 shadow-xs">
                    <div class="px-10 py-8">{{ $slot }}</div>
                    <div
                        class="absolute -top-10 -left-10 w-100 h-20 bg-gradient-to-br from-indigo-300 to-indigo-500 rounded-full opacity-20 blur-3xl">
                    </div>
                </div>
            </div>
        </div>
    </div>
    @fluxScripts
</body>

</html>