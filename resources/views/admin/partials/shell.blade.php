<div class="min-h-screen bg-[#edf3f0] text-slate-900">
    <div class="grid min-h-screen lg:grid-cols-[292px_minmax(0,1fr)]">
        @include('admin.partials.sidebar')

        <main class="min-w-0 p-4 sm:p-6">
            {{ $slot }}
        </main>
    </div>
</div>
