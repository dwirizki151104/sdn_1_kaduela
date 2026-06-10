@extends('layouts.admin')

@section('title', ($record->exists ? 'Edit ' : 'Tambah ') . $config['title'])

@section('content')
    @component('admin.partials.shell')
        <div class="mb-5 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="text-xs font-black uppercase tracking-widest text-teal-700">Form Data Master</p>
                <h1 class="mt-1 text-2xl font-black tracking-normal text-slate-950 sm:text-3xl">{{ $record->exists ? 'Edit' : 'Tambah' }} {{ $config['title'] }}</h1>
                <p class="mt-1 text-sm font-semibold text-slate-500">Isi data dengan benar agar relasi akademik tetap rapi.</p>
            </div>

            <a class="inline-flex min-h-11 items-center justify-center rounded-lg border border-slate-200 bg-white px-4 text-sm font-black text-slate-700 shadow-sm transition hover:bg-slate-50" href="{{ route('admin.master.index', $resource) }}">
                Kembali
            </a>
        </div>

        <form class="rounded-lg border border-slate-200 bg-white p-5 shadow-xl shadow-slate-900/5" action="{{ $record->exists ? route('admin.master.update', [$resource, $record->getKey()]) : route('admin.master.store', $resource) }}" method="post" enctype="multipart/form-data">
            @csrf
            @if ($record->exists)
                @method('PUT')
            @endif

            <div class="grid gap-4 md:grid-cols-2">
                @foreach ($config['fields'] as $field)
                    @php
                        $name = $field['name'];
                        $value = old($name, $record->{$name});
                        $type = $field['type'];
                        if (! old($name) && $value instanceof \Carbon\CarbonInterface) {
                            $value = $type === 'datetime-local' ? $value->format('Y-m-d\TH:i') : $value->format('Y-m-d');
                        }
                    @endphp

                    <div class="{{ in_array($type, ['textarea', 'file'], true) ? 'md:col-span-2' : '' }}">
                        <label class="mb-1.5 block text-xs font-black uppercase tracking-wide text-slate-600" for="{{ $name }}">{{ $field['label'] }}</label>

                        @if ($type === 'select')
                            <select class="min-h-11 w-full rounded-lg border border-slate-200 bg-slate-50 px-3 text-sm font-semibold text-slate-800 outline-none transition focus:border-emerald-500 focus:bg-white focus:ring-4 focus:ring-emerald-100" id="{{ $name }}" name="{{ $name }}">
                                <option value="">Pilih {{ $field['label'] }}</option>
                                @foreach ($field['choices'] as $choiceValue => $choiceLabel)
                                    <option value="{{ $choiceValue }}" @selected((string) $value === (string) $choiceValue)>{{ $choiceLabel }}</option>
                                @endforeach
                            </select>
                        @elseif ($type === 'relation')
                            <select class="min-h-11 w-full rounded-lg border border-slate-200 bg-slate-50 px-3 text-sm font-semibold text-slate-800 outline-none transition focus:border-emerald-500 focus:bg-white focus:ring-4 focus:ring-emerald-100" id="{{ $name }}" name="{{ $name }}">
                                <option value="">Pilih {{ $field['label'] }}</option>
                                @foreach ($options[$field['options']] as $optionValue => $optionLabel)
                                    <option value="{{ $optionValue }}" @selected((string) $value === (string) $optionValue)>{{ $optionLabel }}</option>
                                @endforeach
                            </select>
                        @elseif ($type === 'textarea')
                            <textarea class="min-h-28 w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-sm font-semibold text-slate-800 outline-none transition focus:border-emerald-500 focus:bg-white focus:ring-4 focus:ring-emerald-100" id="{{ $name }}" name="{{ $name }}">{{ $value }}</textarea>
                        @elseif ($type === 'file')
                            <label class="group grid min-h-36 cursor-pointer place-items-center rounded-lg border-2 border-dashed border-emerald-200 bg-emerald-50/45 px-4 py-6 text-center transition hover:border-emerald-400 hover:bg-emerald-50" data-file-drop for="{{ $name }}">
                                <input class="sr-only" id="{{ $name }}" name="{{ $name }}" type="file" accept=".pdf,.doc,.docx,.ppt,.pptx,.zip,.jpg,.jpeg,.png" data-file-input>
                                <span class="grid size-12 place-items-center rounded-lg bg-white text-emerald-700 shadow-sm">
                                    <svg class="size-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.1" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" />
                                        <path d="m17 8-5-5-5 5" />
                                        <path d="M12 3v12" />
                                    </svg>
                                </span>
                                <span class="mt-3 block text-sm font-black text-slate-800" data-file-name>Seret file ke sini atau klik untuk memilih</span>
                                @if ($record->exists && filled($record->{$name}))
                                    <span class="mt-1 block text-xs font-bold text-emerald-700">File saat ini: {{ basename($record->{$name}) }}</span>
                                @endif
                            </label>
                        @else
                            <input class="min-h-11 w-full rounded-lg border border-slate-200 bg-slate-50 px-3 text-sm font-semibold text-slate-800 outline-none transition focus:border-emerald-500 focus:bg-white focus:ring-4 focus:ring-emerald-100" id="{{ $name }}" name="{{ $name }}" type="{{ $type }}" value="{{ $type === 'password' ? '' : $value }}">
                        @endif

                        @if (! empty($field['help']))
                            <p class="mt-1 text-xs font-semibold text-slate-500">{{ $field['help'] }}</p>
                        @endif

                        @error($name)
                            <p class="mt-1 text-xs font-bold text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>
                @endforeach
            </div>

            <div class="mt-5 flex justify-end gap-2 border-t border-slate-100 pt-5">
                <a class="inline-flex min-h-11 items-center rounded-lg border border-slate-200 px-4 text-sm font-black text-slate-700 transition hover:bg-slate-50" href="{{ route('admin.master.index', $resource) }}">Batal</a>
                <button class="inline-flex min-h-11 items-center rounded-lg bg-[#0f5a45] px-5 text-sm font-black text-white shadow-lg shadow-emerald-900/10 transition hover:bg-[#103f35]" type="submit">
                    Simpan
                </button>
            </div>
        </form>
    @endcomponent

    <script>
        document.querySelectorAll('[data-file-drop]').forEach((dropZone) => {
            const input = dropZone.querySelector('[data-file-input]');
            const fileName = dropZone.querySelector('[data-file-name]');

            const showFile = () => {
                if (input.files.length > 0) {
                    fileName.textContent = input.files[0].name;
                }
            };

            input.addEventListener('change', showFile);

            ['dragenter', 'dragover'].forEach((eventName) => {
                dropZone.addEventListener(eventName, (event) => {
                    event.preventDefault();
                    dropZone.classList.add('border-emerald-500', 'bg-emerald-100');
                });
            });

            ['dragleave', 'drop'].forEach((eventName) => {
                dropZone.addEventListener(eventName, (event) => {
                    event.preventDefault();
                    dropZone.classList.remove('border-emerald-500', 'bg-emerald-100');
                });
            });

            dropZone.addEventListener('drop', (event) => {
                if (event.dataTransfer.files.length > 0) {
                    input.files = event.dataTransfer.files;
                    showFile();
                }
            });
        });
    </script>
@endsection
