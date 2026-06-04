<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Guru;
use App\Models\Kelas;
use App\Models\MataPelajaran;
use App\Models\Mengajar;
use App\Models\Modul;
use App\Models\Nilai;
use App\Models\Quiz;
use App\Models\Siswa;
use App\Models\Tugas;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Unique;
use Illuminate\View\View;

class MasterDataController extends Controller
{
    public function index(string $resource): View
    {
        $config = $this->config($resource);
        $records = $config['model']::query()
            ->with($config['relations'] ?? [])
            ->latest($config['primary'])
            ->paginate(10);

        return view('admin.master.index', compact('config', 'records', 'resource'));
    }

    public function create(string $resource): View
    {
        $config = $this->config($resource);
        $record = new $config['model']();
        $options = $this->options();

        return view('admin.master.form', compact('config', 'record', 'resource', 'options'));
    }

    public function store(Request $request, string $resource): RedirectResponse
    {
        $config = $this->config($resource);
        $data = $request->validate($this->rules($resource));

        $config['model']::create($this->prepareData($resource, $data));

        return redirect()->route('admin.master.index', $resource)->with('success', "{$config['title']} berhasil ditambahkan.");
    }

    public function edit(string $resource, int $id): View
    {
        $config = $this->config($resource);
        $record = $this->find($config, $id);
        $options = $this->options();

        return view('admin.master.form', compact('config', 'record', 'resource', 'options'));
    }

    public function update(Request $request, string $resource, int $id): RedirectResponse
    {
        $config = $this->config($resource);
        $record = $this->find($config, $id);
        $data = $request->validate($this->rules($resource, $record));

        $record->update($this->prepareData($resource, $data, $record));

        return redirect()->route('admin.master.index', $resource)->with('success', "{$config['title']} berhasil diperbarui.");
    }

    public function destroy(string $resource, int $id): RedirectResponse
    {
        $config = $this->config($resource);
        $this->find($config, $id)->delete();

        return redirect()->route('admin.master.index', $resource)->with('success', "{$config['title']} berhasil dihapus.");
    }

    private function find(array $config, int $id): Model
    {
        return $config['model']::query()->findOrFail($id);
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
                'columns' => ['id_modul' => 'ID', 'judul_modul' => 'Judul Modul', 'mengajar.guru.nama_guru' => 'Guru', 'mengajar.kelas.nama_kelas' => 'Kelas', 'tanggal_upload' => 'Upload'],
                'fields' => [
                    ['name' => 'id_mengajar', 'label' => 'Data Mengajar', 'type' => 'relation', 'options' => 'mengajar'],
                    ['name' => 'judul_modul', 'label' => 'Judul Modul', 'type' => 'text'],
                    ['name' => 'file_modul', 'label' => 'File Modul', 'type' => 'text', 'help' => 'Isi path file, contoh: modul/tema-1.pdf'],
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
                'columns' => ['id_quiz' => 'ID', 'judul_quiz' => 'Judul Quiz', 'mengajar.kelas.nama_kelas' => 'Kelas', 'durasi' => 'Durasi', 'status' => 'Status'],
                'fields' => [
                    ['name' => 'id_mengajar', 'label' => 'Data Mengajar', 'type' => 'relation', 'options' => 'mengajar'],
                    ['name' => 'judul_quiz', 'label' => 'Judul Quiz', 'type' => 'text'],
                    ['name' => 'durasi', 'label' => 'Durasi Menit', 'type' => 'number'],
                    ['name' => 'tanggal_mulai', 'label' => 'Tanggal Mulai', 'type' => 'datetime-local'],
                    ['name' => 'tanggal_selesai', 'label' => 'Tanggal Selesai', 'type' => 'datetime-local'],
                    ['name' => 'status', 'label' => 'Status', 'type' => 'select', 'choices' => ['draft' => 'Draft', 'aktif' => 'Aktif', 'selesai' => 'Selesai']],
                ],
            ],
            'nilai' => [
                'title' => 'Nilai Siswa',
                'model' => Nilai::class,
                'primary' => 'id_nilai',
                'relations' => ['siswa', 'mataPelajaran'],
                'columns' => ['id_nilai' => 'ID', 'siswa.nama_siswa' => 'Siswa', 'mataPelajaran.nama_mapel' => 'Mapel', 'semester' => 'Semester', 'nilai_akhir' => 'Nilai Akhir'],
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

    private function rules(string $resource, ?Model $record = null): array
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
                'id_guru' => ['required', 'exists:guru,id_guru'],
                'id_kelas' => ['required', 'exists:kelas,id_kelas'],
                'id_mapel' => ['required', 'exists:mata_pelajaran,id_mapel'],
            ],
            'modul' => [
                'id_mengajar' => ['required', 'exists:mengajar,id_mengajar'],
                'judul_modul' => ['required', 'string', 'max:255'],
                'file_modul' => ['required', 'string', 'max:255'],
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

    private function prepareData(string $resource, array $data, ?Model $record = null): array
    {
        if ($resource === 'users' && blank($data['password'] ?? null)) {
            unset($data['password']);
        }

        return $data;
    }

    private function unique(string $table, string $column, ?Model $record = null): Unique
    {
        $rule = Rule::unique($table, $column);

        return $record ? $rule->ignore($record->getKey(), $record->getKeyName()) : $rule;
    }

    private function options(): array
    {
        return [
            'users_guru' => User::where('role', 'guru')->orderBy('username')->pluck('username', 'id_user'),
            'users_siswa' => User::where('role', 'siswa')->orderBy('username')->pluck('username', 'id_user'),
            'guru' => Guru::orderBy('nama_guru')->pluck('nama_guru', 'id_guru'),
            'wali_kelas' => Guru::where('jenis_guru', 'wali_kelas')->orderBy('nama_guru')->pluck('nama_guru', 'id_guru'),
            'siswa' => Siswa::orderBy('nama_siswa')->pluck('nama_siswa', 'id_siswa'),
            'kelas' => Kelas::orderBy('nama_kelas')->pluck('nama_kelas', 'id_kelas'),
            'mapel' => MataPelajaran::orderBy('nama_mapel')->pluck('nama_mapel', 'id_mapel'),
            'mengajar' => Mengajar::with(['guru', 'kelas', 'mataPelajaran'])
                ->get()
                ->mapWithKeys(fn (Mengajar $mengajar) => [
                    $mengajar->id_mengajar => ($mengajar->guru->nama_guru ?? '-') . ' - ' . ($mengajar->kelas->nama_kelas ?? '-') . ' - ' . ($mengajar->mataPelajaran->nama_mapel ?? '-'),
                ]),
        ];
    }

    public static function value(Model $record, string $key): mixed
    {
        return data_get($record, $key) ?? '-';
    }
}
