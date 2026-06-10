<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Guru;
use App\Models\JawabanSiswa;
use App\Models\Kelas;
use App\Models\MataPelajaran;
use App\Models\Mengajar;
use App\Models\Modul;
use App\Models\Nilai;
use App\Models\PengerjaanQuiz;
use App\Models\PengumpulanTugas;
use App\Models\Quiz;
use App\Models\Siswa;
use App\Models\Tugas;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Unique;
use Illuminate\View\View;

class MasterDataController extends Controller
{
    private const GURU_ALLOWED_RESOURCES = [
        'modul',
        'tugas',
        'pengumpulan-tugas',
        'quiz',
        'jawaban-siswa',
        'nilai-quiz',
        'nilai',
    ];

    public function index(string $resource): View
    {
        $this->authorizeResourceAccess($resource);

        $config = $this->config($resource);
        $query = $config['model']::query();
        $this->applyResourceScope($query, $resource);
        $this->applyResourceFilters($query, $resource);

        $records = $query
            ->with($config['relations'] ?? [])
            ->withCount($config['counts'] ?? [])
            ->latest($config['primary'])
            ->paginate(10);

        return view('admin.master.index', compact('config', 'records', 'resource'));
    }

    public function create(string $resource): View
    {
        $this->authorizeResourceAccess($resource);

        $config = $this->config($resource);
        $record = new $config['model']();
        $options = $this->options();

        return view('admin.master.form', compact('config', 'record', 'resource', 'options'));
    }

    public function store(Request $request, string $resource): RedirectResponse
    {
        $this->authorizeResourceAccess($resource);

        $config = $this->config($resource);
        $data = $request->validate($this->rules($resource, request: $request), $this->messages($resource));
        $this->authorizeGuruData($resource, $data);

        $config['model']::create($this->prepareData($request, $resource, $data));

        return redirect()->route('admin.master.index', $resource)->with('success', "{$config['title']} berhasil ditambahkan.");
    }

    public function edit(string $resource, int $id): View
    {
        $this->authorizeResourceAccess($resource);

        $config = $this->config($resource);
        $record = $this->find($config, $id);
        $options = $this->options();

        return view('admin.master.form', compact('config', 'record', 'resource', 'options'));
    }

    public function update(Request $request, string $resource, int $id): RedirectResponse
    {
        $this->authorizeResourceAccess($resource);

        $config = $this->config($resource);
        $record = $this->find($config, $id);
        $data = $request->validate($this->rules($resource, $record, $request), $this->messages($resource));
        $this->authorizeGuruData($resource, $data);

        $record->update($this->prepareData($request, $resource, $data, $record));

        return redirect()->route('admin.master.index', $resource)->with('success', "{$config['title']} berhasil diperbarui.");
    }

    public function destroy(string $resource, int $id): RedirectResponse
    {
        $this->authorizeResourceAccess($resource);

        $config = $this->config($resource);
        $record = $this->find($config, $id);

        if ($resource === 'modul' && filled($record->file_modul)) {
            Storage::disk('public')->delete($record->file_modul);
        }

        $record->delete();

        return redirect()->route('admin.master.index', $resource)->with('success', "{$config['title']} berhasil dihapus.");
    }

    private function find(array $config, int $id): Model
    {
        $query = $config['model']::query();
        $this->applyResourceScope($query, request()->route('resource'));
        $this->applyResourceFilters($query, request()->route('resource'));

        return $query->findOrFail($id);
    }

    private function authorizeResourceAccess(string $resource): void
    {
        $user = auth()->user();

        if ($user?->role !== 'guru') {
            return;
        }

        abort_if(! in_array($resource, self::GURU_ALLOWED_RESOURCES, true), 403);
    }

    private function applyResourceScope($query, ?string $resource): void
    {
        $user = auth()->user();

        if ($user?->role !== 'guru') {
            return;
        }

        $guruId = $user->guru?->id_guru;

        if (! $guruId) {
            $query->whereRaw('1 = 0');

            return;
        }

        if (in_array($resource, ['modul', 'tugas', 'quiz'], true)) {
            $query->whereHas('mengajar', fn ($mengajar) => $mengajar->where('id_guru', $guruId));
        }

        if ($resource === 'pengumpulan-tugas') {
            $query->whereHas('tugas.mengajar', fn ($mengajar) => $mengajar->where('id_guru', $guruId));
        }

        if ($resource === 'jawaban-siswa') {
            $query->whereHas('pengerjaan.quiz.mengajar', fn ($mengajar) => $mengajar->where('id_guru', $guruId));
        }

        if ($resource === 'nilai-quiz') {
            $query->whereHas('quiz.mengajar', fn ($mengajar) => $mengajar->where('id_guru', $guruId));
        }

        if ($resource === 'nilai') {
            $this->scopeNilaiForGuru($query, $guruId);
        }
    }

    private function scopeNilaiForGuru($query, int $guruId): void
    {
        $pairs = Mengajar::where('id_guru', $guruId)->get(['id_kelas', 'id_mapel']);

        if ($pairs->isEmpty()) {
            $query->whereRaw('1 = 0');

            return;
        }

        $query->where(function ($nilaiQuery) use ($pairs) {
            foreach ($pairs as $pair) {
                $nilaiQuery->orWhere(function ($pairQuery) use ($pair) {
                    $pairQuery
                        ->where('id_mapel', $pair->id_mapel)
                        ->whereHas('siswa', fn ($siswa) => $siswa->where('id_kelas', $pair->id_kelas));
                });
            }
        });
    }

    private function applyResourceFilters($query, ?string $resource): void
    {
        if ($resource === 'nilai-quiz') {
            $query->whereNotNull('waktu_selesai');
        }
    }

    private function config(string $resource): array
    {
        $configs = [
            'users' => [
                'title' => 'Data User',
                'model' => User::class,
                'primary' => 'id_user',
                'relations' => [],
                'columns' => ['id_user' => 'ID', 'username' => 'Username', 'role' => 'Role', 'status' => 'Status'],
                'fields' => [
                    ['name' => 'username', 'label' => 'Username', 'type' => 'text'],
                    ['name' => 'password', 'label' => 'Password', 'type' => 'password', 'help' => 'Kosongkan saat edit jika tidak ingin mengganti password.'],
                    ['name' => 'role', 'label' => 'Role', 'type' => 'select', 'choices' => ['admin' => 'Admin', 'guru' => 'Guru', 'siswa' => 'Siswa']],
                    ['name' => 'status', 'label' => 'Status', 'type' => 'select', 'choices' => ['aktif' => 'Aktif', 'nonaktif' => 'Nonaktif']],
                ],
            ],
            'guru' => [
                'title' => 'Data Guru',
                'model' => Guru::class,
                'primary' => 'id_guru',
                'relations' => ['user'],
                'columns' => ['id_guru' => 'ID', 'nama_guru' => 'Nama Guru', 'nip' => 'NIP', 'jenis_guru' => 'Jenis Guru', 'user.username' => 'Username'],
                'fields' => [
                    ['name' => 'id_user', 'label' => 'Akun User', 'type' => 'relation', 'options' => 'users_guru'],
                    ['name' => 'nip', 'label' => 'NIP', 'type' => 'text'],
                    ['name' => 'nama_guru', 'label' => 'Nama Guru', 'type' => 'text'],
                    ['name' => 'jenis_guru', 'label' => 'Jenis Guru', 'type' => 'select', 'choices' => ['wali_kelas' => 'Wali Kelas', 'bidang_studi' => 'Guru Bidang Studi']],
                    ['name' => 'no_hp', 'label' => 'No HP', 'type' => 'text'],
                    ['name' => 'alamat', 'label' => 'Alamat', 'type' => 'textarea'],
                ],
            ],
            'siswa' => [
                'title' => 'Data Siswa',
                'model' => Siswa::class,
                'primary' => 'id_siswa',
                'relations' => ['user', 'kelas'],
                'columns' => ['id_siswa' => 'ID', 'nama_siswa' => 'Nama Siswa', 'nis' => 'NIS', 'jk' => 'JK', 'kelas.nama_kelas' => 'Kelas'],
                'fields' => [
                    ['name' => 'id_user', 'label' => 'Akun User', 'type' => 'relation', 'options' => 'users_siswa'],
                    ['name' => 'nis', 'label' => 'NIS', 'type' => 'text'],
                    ['name' => 'nama_siswa', 'label' => 'Nama Siswa', 'type' => 'text'],
                    ['name' => 'jk', 'label' => 'Jenis Kelamin', 'type' => 'select', 'choices' => ['L' => 'Laki-laki', 'P' => 'Perempuan']],
                    ['name' => 'tanggal_lahir', 'label' => 'Tanggal Lahir', 'type' => 'date'],
                    ['name' => 'alamat', 'label' => 'Alamat', 'type' => 'textarea'],
                    ['name' => 'id_kelas', 'label' => 'Kelas', 'type' => 'relation', 'options' => 'kelas'],
                ],
            ],
            'kelas' => [
                'title' => 'Data Kelas',
                'model' => Kelas::class,
                'primary' => 'id_kelas',
                'relations' => ['waliKelas'],
                'columns' => ['id_kelas' => 'ID', 'nama_kelas' => 'Nama Kelas', 'waliKelas.nama_guru' => 'Wali Kelas'],
                'fields' => [
                    ['name' => 'nama_kelas', 'label' => 'Nama Kelas', 'type' => 'text'],
                    ['name' => 'id_wali_kelas', 'label' => 'Wali Kelas', 'type' => 'relation', 'options' => 'wali_kelas'],
                ],
            ],
            'mata-pelajaran' => [
                'title' => 'Mata Pelajaran',
                'model' => MataPelajaran::class,
                'primary' => 'id_mapel',
                'relations' => [],
                'columns' => ['id_mapel' => 'ID', 'nama_mapel' => 'Nama Mapel', 'kategori' => 'Kategori'],
                'fields' => [
                    ['name' => 'nama_mapel', 'label' => 'Nama Mapel', 'type' => 'text'],
                    ['name' => 'kategori', 'label' => 'Kategori', 'type' => 'select', 'choices' => ['tematik' => 'Tematik', 'khusus' => 'Khusus']],
                ],
            ],
            'mengajar' => [
                'title' => 'Data Mengajar',
                'model' => Mengajar::class,
                'primary' => 'id_mengajar',
                'relations' => ['guru', 'kelas', 'mataPelajaran'],
                'columns' => ['id_mengajar' => 'ID', 'guru.nama_guru' => 'Guru', 'kelas.nama_kelas' => 'Kelas', 'mataPelajaran.nama_mapel' => 'Mapel'],
                'fields' => [
                    ['name' => 'id_guru', 'label' => 'Guru', 'type' => 'relation', 'options' => 'guru'],
                    ['name' => 'id_kelas', 'label' => 'Kelas', 'type' => 'relation', 'options' => 'kelas'],
                    ['name' => 'id_mapel', 'label' => 'Mata Pelajaran', 'type' => 'relation', 'options' => 'mapel'],
                ],
            ],
            'modul' => [
                'title' => 'Modul Pembelajaran',
                'model' => Modul::class,
                'primary' => 'id_modul',
                'relations' => ['mengajar.guru', 'mengajar.kelas', 'mengajar.mataPelajaran'],
                'columns' => ['id_modul' => 'ID', 'judul_modul' => 'Judul Modul', 'mengajar.guru.nama_guru' => 'Guru', 'mengajar.kelas.nama_kelas' => 'Kelas', 'file_modul' => 'File', 'tanggal_upload' => 'Upload'],
                'fields' => [
                    ['name' => 'id_mengajar', 'label' => 'Data Mengajar', 'type' => 'relation', 'options' => 'mengajar'],
                    ['name' => 'judul_modul', 'label' => 'Judul Modul', 'type' => 'text'],
                    ['name' => 'file_modul', 'label' => 'File Modul', 'type' => 'file', 'help' => 'Seret file ke area upload atau pilih file dari perangkat. Format: PDF, DOC, DOCX, PPT, PPTX, ZIP, JPG, PNG. Maksimal 10 MB.'],
                    ['name' => 'tanggal_upload', 'label' => 'Tanggal Upload', 'type' => 'datetime-local'],
                ],
            ],
            'tugas' => [
                'title' => 'Tugas',
                'model' => Tugas::class,
                'primary' => 'id_tugas',
                'relations' => ['mengajar.guru', 'mengajar.kelas', 'mengajar.mataPelajaran'],
                'columns' => ['id_tugas' => 'ID', 'judul_tugas' => 'Judul Tugas', 'mengajar.kelas.nama_kelas' => 'Kelas', 'mengajar.mataPelajaran.nama_mapel' => 'Mapel', 'deadline' => 'Deadline'],
                'fields' => [
                    ['name' => 'id_mengajar', 'label' => 'Data Mengajar', 'type' => 'relation', 'options' => 'mengajar'],
                    ['name' => 'judul_tugas', 'label' => 'Judul Tugas', 'type' => 'text'],
                    ['name' => 'deskripsi', 'label' => 'Deskripsi', 'type' => 'textarea'],
                    ['name' => 'deadline', 'label' => 'Deadline', 'type' => 'datetime-local'],
                ],
            ],
            'quiz' => [
                'title' => 'Quiz Online',
                'model' => Quiz::class,
                'primary' => 'id_quiz',
                'relations' => ['mengajar.guru', 'mengajar.kelas', 'mengajar.mataPelajaran'],
                'counts' => ['soal'],
                'columns' => ['id_quiz' => 'ID', 'judul_quiz' => 'Judul Quiz', 'mengajar.kelas.nama_kelas' => 'Kelas', 'durasi' => 'Durasi', 'soal_count' => 'Soal', 'status' => 'Status'],
                'fields' => [
                    ['name' => 'id_mengajar', 'label' => 'Data Mengajar', 'type' => 'relation', 'options' => 'mengajar'],
                    ['name' => 'judul_quiz', 'label' => 'Judul Quiz', 'type' => 'text'],
                    ['name' => 'durasi', 'label' => 'Durasi Menit', 'type' => 'number'],
                    ['name' => 'tanggal_mulai', 'label' => 'Tanggal Mulai', 'type' => 'datetime-local'],
                    ['name' => 'tanggal_selesai', 'label' => 'Tanggal Selesai', 'type' => 'datetime-local'],
                    ['name' => 'status', 'label' => 'Status', 'type' => 'select', 'choices' => ['draft' => 'Draft', 'aktif' => 'Aktif', 'selesai' => 'Selesai']],
                ],
            ],
            'pengumpulan-tugas' => [
                'title' => 'Pengumpulan Tugas',
                'model' => PengumpulanTugas::class,
                'primary' => 'id_pengumpulan',
                'readonly' => true,
                'relations' => ['siswa.kelas', 'tugas.mengajar.kelas', 'tugas.mengajar.mataPelajaran'],
                'columns' => [
                    'id_pengumpulan' => 'ID',
                    'siswa.nama_siswa' => 'Nama Siswa',
                    'siswa.kelas.nama_kelas' => 'Kelas',
                    'tugas.judul_tugas' => 'Tugas',
                    'tugas.mengajar.mataPelajaran.nama_mapel' => 'Mapel',
                    'tanggal_kumpul' => 'Waktu Kumpul',
                    'file_jawaban' => 'File Jawaban',
                    'nilai' => 'Nilai',
                ],
                'fields' => [],
            ],
            'jawaban-siswa' => [
                'title' => 'Jawaban Siswa',
                'model' => JawabanSiswa::class,
                'primary' => 'id_jawaban',
                'readonly' => true,
                'relations' => [
                    'pengerjaan.siswa',
                    'pengerjaan.quiz',
                    'soal.pilihanJawaban',
                    'pilihan',
                ],
                'columns' => [
                    'id_jawaban' => 'ID',
                    'pengerjaan.siswa.nama_siswa' => 'Nama Siswa',
                    'pengerjaan.quiz.judul_quiz' => 'Quiz',
                    'soal.pertanyaan' => 'Soal',
                    'pilihan.opsi' => 'Opsi',
                    'pilihan.isi_pilihan' => 'Jawaban',
                    'pilihan.is_benar' => 'Status',
                    'jawaban_benar' => 'Jawaban Benar',
                ],
                'fields' => [],
            ],
            'nilai-quiz' => [
                'title' => 'Nilai Quiz dan Ulangan Harian',
                'model' => PengerjaanQuiz::class,
                'primary' => 'id_pengerjaan',
                'readonly' => true,
                'relations' => [
                    'siswa.kelas',
                    'quiz.mengajar.kelas',
                    'quiz.mengajar.mataPelajaran',
                ],
                'columns' => [
                    'id_pengerjaan' => 'ID',
                    'siswa.nama_siswa' => 'Nama Siswa',
                    'siswa.kelas.nama_kelas' => 'Kelas Siswa',
                    'quiz.judul_quiz' => 'Quiz/Ulangan',
                    'quiz.mengajar.mataPelajaran.nama_mapel' => 'Mapel',
                    'waktu_mulai' => 'Mulai',
                    'waktu_selesai' => 'Selesai',
                    'nilai' => 'Nilai',
                ],
                'fields' => [],
            ],
            'nilai' => [
                'title' => 'Nilai Siswa',
                'model' => Nilai::class,
                'primary' => 'id_nilai',
                'relations' => ['siswa.kelas', 'mataPelajaran'],
                'columns' => ['id_nilai' => 'ID', 'siswa.nama_siswa' => 'Siswa', 'siswa.kelas.nama_kelas' => 'Kelas', 'mataPelajaran.nama_mapel' => 'Mapel', 'semester' => 'Semester', 'nilai_akhir' => 'Nilai Akhir'],
                'fields' => [
                    ['name' => 'id_siswa', 'label' => 'Siswa', 'type' => 'relation', 'options' => 'siswa'],
                    ['name' => 'id_mapel', 'label' => 'Mata Pelajaran', 'type' => 'relation', 'options' => 'mapel'],
                    ['name' => 'semester', 'label' => 'Semester', 'type' => 'select', 'choices' => ['1' => 'Semester 1', '2' => 'Semester 2']],
                    ['name' => 'nilai_tugas', 'label' => 'Nilai Tugas', 'type' => 'number'],
                    ['name' => 'nilai_quiz', 'label' => 'Nilai Quiz', 'type' => 'number'],
                    ['name' => 'nilai_uts', 'label' => 'Nilai UTS', 'type' => 'number'],
                    ['name' => 'nilai_uas', 'label' => 'Nilai UAS', 'type' => 'number'],
                    ['name' => 'nilai_akhir', 'label' => 'Nilai Akhir', 'type' => 'number'],
                ],
            ],
        ];

        abort_if(! isset($configs[$resource]), 404);

        return $configs[$resource];
    }

    private function rules(string $resource, ?Model $record = null, ?Request $request = null): array
    {
        return match ($resource) {
            'users' => [
                'username' => ['required', 'string', 'max:50', $this->unique('users', 'username', $record)],
                'password' => [$record ? 'nullable' : 'required', 'string', 'min:8'],
                'role' => ['required', Rule::in(['admin', 'guru', 'siswa'])],
                'status' => ['required', Rule::in(['aktif', 'nonaktif'])],
            ],
            'guru' => [
                'id_user' => ['required', 'exists:users,id_user', $this->unique('guru', 'id_user', $record)],
                'nip' => ['nullable', 'string', 'max:30', $this->unique('guru', 'nip', $record)],
                'nama_guru' => ['required', 'string', 'max:255'],
                'jenis_guru' => ['required', Rule::in(['wali_kelas', 'bidang_studi'])],
                'no_hp' => ['nullable', 'string', 'max:20'],
                'alamat' => ['nullable', 'string'],
            ],
            'siswa' => [
                'id_user' => ['required', 'exists:users,id_user', $this->unique('siswa', 'id_user', $record)],
                'nis' => ['required', 'string', 'max:30', $this->unique('siswa', 'nis', $record)],
                'nama_siswa' => ['required', 'string', 'max:255'],
                'jk' => ['required', Rule::in(['L', 'P'])],
                'tanggal_lahir' => ['nullable', 'date'],
                'alamat' => ['nullable', 'string'],
                'id_kelas' => ['required', 'exists:kelas,id_kelas'],
            ],
            'kelas' => [
                'nama_kelas' => ['required', 'string', 'max:20', $this->unique('kelas', 'nama_kelas', $record)],
                'id_wali_kelas' => ['nullable', 'exists:guru,id_guru', $this->unique('kelas', 'id_wali_kelas', $record)],
            ],
            'mata-pelajaran' => [
                'nama_mapel' => ['required', 'string', 'max:100', $this->unique('mata_pelajaran', 'nama_mapel', $record)],
                'kategori' => ['required', Rule::in(['tematik', 'khusus'])],
            ],
            'mengajar' => [
                'id_guru' => ['required', 'exists:guru,id_guru', $this->uniqueMengajar($request, $record)],
                'id_kelas' => ['required', 'exists:kelas,id_kelas'],
                'id_mapel' => ['required', 'exists:mata_pelajaran,id_mapel'],
            ],
            'modul' => [
                'id_mengajar' => ['required', 'exists:mengajar,id_mengajar'],
                'judul_modul' => ['required', 'string', 'max:255'],
                'file_modul' => [$record ? 'nullable' : 'required', 'file', 'mimes:pdf,doc,docx,ppt,pptx,zip,jpg,jpeg,png', 'max:10240'],
                'tanggal_upload' => ['required', 'date'],
            ],
            'tugas' => [
                'id_mengajar' => ['required', 'exists:mengajar,id_mengajar'],
                'judul_tugas' => ['required', 'string', 'max:255'],
                'deskripsi' => ['nullable', 'string'],
                'deadline' => ['required', 'date'],
            ],
            'quiz' => [
                'id_mengajar' => ['required', 'exists:mengajar,id_mengajar'],
                'judul_quiz' => ['required', 'string', 'max:255'],
                'durasi' => ['required', 'integer', 'min:1', 'max:600'],
                'tanggal_mulai' => ['required', 'date'],
                'tanggal_selesai' => ['required', 'date', 'after:tanggal_mulai'],
                'status' => ['required', Rule::in(['draft', 'aktif', 'selesai'])],
            ],
            'nilai' => [
                'id_siswa' => ['required', 'exists:siswa,id_siswa'],
                'id_mapel' => ['required', 'exists:mata_pelajaran,id_mapel'],
                'semester' => ['required', Rule::in(['1', '2'])],
                'nilai_tugas' => ['nullable', 'numeric', 'min:0', 'max:100'],
                'nilai_quiz' => ['nullable', 'numeric', 'min:0', 'max:100'],
                'nilai_uts' => ['nullable', 'numeric', 'min:0', 'max:100'],
                'nilai_uas' => ['nullable', 'numeric', 'min:0', 'max:100'],
                'nilai_akhir' => ['nullable', 'numeric', 'min:0', 'max:100'],
            ],
        };
    }

    private function messages(string $resource): array
    {
        return match ($resource) {
            'mengajar' => [
                'id_guru.unique' => 'Kombinasi guru, kelas, dan mata pelajaran ini sudah ada.',
            ],
            default => [],
        };
    }

    private function prepareData(Request $request, string $resource, array $data, ?Model $record = null): array
    {
        if ($resource === 'users' && blank($data['password'] ?? null)) {
            unset($data['password']);
        }

        if ($resource === 'modul') {
            if ($request->hasFile('file_modul')) {
                if ($record && filled($record->file_modul)) {
                    Storage::disk('public')->delete($record->file_modul);
                }

                $data['file_modul'] = $request->file('file_modul')->store('modul', 'public');
            } else {
                unset($data['file_modul']);
            }
        }

        return $data;
    }

    private function unique(string $table, string $column, ?Model $record = null): Unique
    {
        $rule = Rule::unique($table, $column);

        return $record ? $rule->ignore($record->getKey(), $record->getKeyName()) : $rule;
    }

    private function uniqueMengajar(?Request $request, ?Model $record = null): Unique
    {
        $rule = Rule::unique('mengajar', 'id_guru')
            ->where('id_kelas', $request?->input('id_kelas'))
            ->where('id_mapel', $request?->input('id_mapel'));

        return $record ? $rule->ignore($record->getKey(), $record->getKeyName()) : $rule;
    }

    private function authorizeGuruData(string $resource, array $data): void
    {
        $user = auth()->user();

        if ($user?->role !== 'guru') {
            return;
        }

        $guruId = $user->guru?->id_guru;
        abort_if(! $guruId, 403);

        if (in_array($resource, ['modul', 'tugas', 'quiz'], true)) {
            abort_if(
                ! Mengajar::where('id_guru', $guruId)
                    ->where('id_mengajar', $data['id_mengajar'] ?? null)
                    ->exists(),
                403
            );
        }

        if ($resource === 'nilai') {
            $kelasId = Siswa::where('id_siswa', $data['id_siswa'] ?? null)->value('id_kelas');

            abort_if(
                ! $kelasId || ! Mengajar::where('id_guru', $guruId)
                    ->where('id_kelas', $kelasId)
                    ->where('id_mapel', $data['id_mapel'] ?? null)
                    ->exists(),
                403
            );
        }
    }

    private function options(): array
    {
        $user = auth()->user();
        $guruId = $user?->role === 'guru' ? $user->guru?->id_guru : null;
        $mengajarQuery = Mengajar::with(['guru', 'kelas', 'mataPelajaran']);
        $siswaQuery = Siswa::orderBy('nama_siswa');
        $mapelQuery = MataPelajaran::orderBy('nama_mapel');

        if ($guruId) {
            $mengajarQuery->where('id_guru', $guruId);
            $kelasIds = Mengajar::where('id_guru', $guruId)->pluck('id_kelas');
            $mapelIds = Mengajar::where('id_guru', $guruId)->pluck('id_mapel');
            $siswaQuery->whereIn('id_kelas', $kelasIds);
            $mapelQuery->whereIn('id_mapel', $mapelIds);
        } elseif ($user?->role === 'guru') {
            $mengajarQuery->whereRaw('1 = 0');
            $siswaQuery->whereRaw('1 = 0');
            $mapelQuery->whereRaw('1 = 0');
        }

        return [
            'users_guru' => User::where('role', 'guru')->orderBy('username')->pluck('username', 'id_user'),
            'users_siswa' => User::where('role', 'siswa')->orderBy('username')->pluck('username', 'id_user'),
            'guru' => Guru::orderBy('nama_guru')->pluck('nama_guru', 'id_guru'),
            'wali_kelas' => Guru::where('jenis_guru', 'wali_kelas')->orderBy('nama_guru')->pluck('nama_guru', 'id_guru'),
            'siswa' => $siswaQuery->pluck('nama_siswa', 'id_siswa'),
            'kelas' => Kelas::orderBy('nama_kelas')->pluck('nama_kelas', 'id_kelas'),
            'mapel' => $mapelQuery->pluck('nama_mapel', 'id_mapel'),
            'mengajar' => $mengajarQuery
                ->get()
                ->mapWithKeys(fn (Mengajar $mengajar) => [
                    $mengajar->id_mengajar => ($mengajar->guru->nama_guru ?? '-') . ' - ' . ($mengajar->kelas->nama_kelas ?? '-') . ' - ' . ($mengajar->mataPelajaran->nama_mapel ?? '-'),
                ]),
        ];
    }

    public static function value(Model $record, string $key): mixed
    {
        if ($key === 'pilihan.is_benar') {
            $isCorrect = data_get($record, $key);

            return is_null($isCorrect) ? '-' : ($isCorrect ? 'Benar' : 'Salah');
        }

        if ($key === 'jawaban_benar') {
            if ($record->pilihan?->is_benar) {
                return '-';
            }

            $correctChoice = $record->soal?->pilihanJawaban?->firstWhere('is_benar', true);

            return $correctChoice
                ? "{$correctChoice->opsi}. {$correctChoice->isi_pilihan}"
                : '-';
        }

        return data_get($record, $key) ?? '-';
    }
}
