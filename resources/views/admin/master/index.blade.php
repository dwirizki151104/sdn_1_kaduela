@extends('layouts.admin')

@section('title', $config['title'] . ' - Admin')

@section('content')
    @component('admin.partials.shell')
        <div class="mb-5 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="text-xs font-black uppercase tracking-widest text-teal-700">Data Master</p>
                <h1 class="mt-1 text-2xl font-black tracking-normal text-slate-950 sm:text-3xl">{{ $config['title'] }}</h1>
                <p class="mt-1 text-sm font-semibold text-slate-500">Kelola {{ strtolower($config['title']) }} untuk kebutuhan akademik dan e-learning.</p>
            </div>

            <a class="inline-flex min-h-11 items-center justify-center rounded-lg bg-[#0f5a45] px-4 text-sm font-black text-white shadow-lg shadow-emerald-900/10 transition hover:bg-[#103f35]" href="{{ route('admin.master.create', $resource) }}">
                Tambah Data
            </a>
        </div>

        @if (session('success'))
            <div class="mb-4 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-bold text-emerald-800">
                {{ session('success') }}
            </div>
        @endif

        <section class="overflow-hidden rounded-lg border border-slate-200 bg-white shadow-xl shadow-slate-900/5">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-100 text-left">
                    <thead class="bg-slate-50">
                        <tr>
                            @foreach ($config['columns'] as $label)
                                <th class="px-4 py-3 text-xs font-black uppercase tracking-wide text-slate-500">{{ $label }}</th>
                            @endforeach
                            <th class="px-4 py-3 text-right text-xs font-black uppercase tracking-wide text-slate-500">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($records as $record)
                            <tr class="transition hover:bg-emerald-50/45">
                                @foreach (array_keys($config['columns']) as $column)
                                    <td class="whitespace-nowrap px-4 py-3 text-sm font-semibold text-slate-700">
                                        {{ \App\Http\Controllers\Admin\MasterDataController::value($record, $column) }}
                                    </td>
                                @endforeach
                                <td class="px-4 py-3">
                                    <div class="flex justify-end gap-2">
                                        <a class="inline-flex min-h-9 items-center rounded-lg border border-slate-200 px-3 text-xs font-black text-slate-700 transition hover:border-blue-200 hover:bg-blue-50 hover:text-blue-700" href="{{ route('admin.master.edit', [$resource, $record->getKey()]) }}">
                                            Edit
                                        </a>
                                        <form action="{{ route('admin.master.destroy', [$resource, $record->getKey()]) }}" method="post" onsubmit="return confirm('Hapus data ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button class="inline-flex min-h-9 items-center rounded-lg border border-rose-200 px-3 text-xs font-black text-rose-700 transition hover:bg-rose-50" type="submit">
                                                Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td class="px-4 py-8 text-center text-sm font-semibold text-slate-500" colspan="{{ count($config['columns']) + 1 }}">
                                    Belum ada data.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="border-t border-slate-100 px-4 py-3">
                {{ $records->links() }}
            </div>
        </section>
    @endcomponent
@endsection
