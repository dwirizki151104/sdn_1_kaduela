<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('guru', function (Blueprint $table) {
            $table->id('id_guru');
            $table->foreignId('id_user')->unique()->constrained('users', 'id_user')->cascadeOnDelete();
            $table->string('nip', 30)->unique()->nullable();
            $table->string('nama_guru');
            $table->enum('jenis_guru', ['wali_kelas', 'bidang_studi']);
            $table->string('no_hp', 20)->nullable();
            $table->text('alamat')->nullable();
            $table->timestamps();
        });

        Schema::create('kelas', function (Blueprint $table) {
            $table->id('id_kelas');
            $table->string('nama_kelas', 20)->unique();
            $table->foreignId('id_wali_kelas')->nullable()->unique()->constrained('guru', 'id_guru')->nullOnDelete();
            $table->timestamps();
        });

        Schema::create('siswa', function (Blueprint $table) {
            $table->id('id_siswa');
            $table->foreignId('id_user')->unique()->constrained('users', 'id_user')->cascadeOnDelete();
            $table->string('nis', 30)->unique();
            $table->string('nama_siswa');
            $table->enum('jk', ['L', 'P']);
            $table->date('tanggal_lahir')->nullable();
            $table->text('alamat')->nullable();
            $table->foreignId('id_kelas')->constrained('kelas', 'id_kelas')->restrictOnDelete();
            $table->timestamps();
        });

        Schema::create('mata_pelajaran', function (Blueprint $table) {
            $table->id('id_mapel');
            $table->string('nama_mapel', 100)->unique();
            $table->enum('kategori', ['tematik', 'khusus']);
            $table->timestamps();
        });

        Schema::create('mengajar', function (Blueprint $table) {
            $table->id('id_mengajar');
            $table->foreignId('id_guru')->constrained('guru', 'id_guru')->cascadeOnDelete();
            $table->foreignId('id_kelas')->constrained('kelas', 'id_kelas')->cascadeOnDelete();
            $table->foreignId('id_mapel')->constrained('mata_pelajaran', 'id_mapel')->restrictOnDelete();
            $table->timestamps();
            $table->unique(['id_guru', 'id_kelas', 'id_mapel']);
        });

        Schema::create('modul', function (Blueprint $table) {
            $table->id('id_modul');
            $table->foreignId('id_mengajar')->constrained('mengajar', 'id_mengajar')->cascadeOnDelete();
            $table->string('judul_modul');
            $table->string('file_modul');
            $table->dateTime('tanggal_upload');
            $table->timestamps();
        });

        Schema::create('tugas', function (Blueprint $table) {
            $table->id('id_tugas');
            $table->foreignId('id_mengajar')->constrained('mengajar', 'id_mengajar')->cascadeOnDelete();
            $table->string('judul_tugas');
            $table->text('deskripsi')->nullable();
            $table->dateTime('deadline');
            $table->timestamps();
        });

        Schema::create('pengumpulan_tugas', function (Blueprint $table) {
            $table->id('id_pengumpulan');
            $table->foreignId('id_tugas')->constrained('tugas', 'id_tugas')->cascadeOnDelete();
            $table->foreignId('id_siswa')->constrained('siswa', 'id_siswa')->cascadeOnDelete();
            $table->string('file_jawaban');
            $table->dateTime('tanggal_kumpul');
            $table->decimal('nilai', 5, 2)->nullable();
            $table->timestamps();
            $table->unique(['id_tugas', 'id_siswa']);
        });

        Schema::create('quiz', function (Blueprint $table) {
            $table->id('id_quiz');
            $table->foreignId('id_mengajar')->constrained('mengajar', 'id_mengajar')->cascadeOnDelete();
            $table->string('judul_quiz');
            $table->unsignedSmallInteger('durasi')->comment('Durasi pengerjaan dalam menit');
            $table->dateTime('tanggal_mulai');
            $table->dateTime('tanggal_selesai');
            $table->enum('status', ['draft', 'aktif', 'selesai'])->default('draft');
            $table->timestamps();
        });

        Schema::create('soal_quiz', function (Blueprint $table) {
            $table->id('id_soal');
            $table->foreignId('id_quiz')->constrained('quiz', 'id_quiz')->cascadeOnDelete();
            $table->text('pertanyaan');
            $table->decimal('bobot', 5, 2)->default(1);
            $table->timestamps();
        });

        Schema::create('pilihan_jawaban', function (Blueprint $table) {
            $table->id('id_pilihan');
            $table->foreignId('id_soal')->constrained('soal_quiz', 'id_soal')->cascadeOnDelete();
            $table->enum('opsi', ['A', 'B', 'C', 'D']);
            $table->text('isi_pilihan');
            $table->boolean('is_benar')->default(false);
            $table->timestamps();
            $table->unique(['id_soal', 'opsi']);
        });

        Schema::create('pengerjaan_quiz', function (Blueprint $table) {
            $table->id('id_pengerjaan');
            $table->foreignId('id_quiz')->constrained('quiz', 'id_quiz')->cascadeOnDelete();
            $table->foreignId('id_siswa')->constrained('siswa', 'id_siswa')->cascadeOnDelete();
            $table->dateTime('waktu_mulai');
            $table->dateTime('waktu_selesai')->nullable();
            $table->decimal('nilai', 5, 2)->nullable();
            $table->timestamps();
            $table->unique(['id_quiz', 'id_siswa']);
        });

        Schema::create('jawaban_siswa', function (Blueprint $table) {
            $table->id('id_jawaban');
            $table->foreignId('id_pengerjaan')->constrained('pengerjaan_quiz', 'id_pengerjaan')->cascadeOnDelete();
            $table->foreignId('id_soal')->constrained('soal_quiz', 'id_soal')->cascadeOnDelete();
            $table->foreignId('id_pilihan')->nullable()->constrained('pilihan_jawaban', 'id_pilihan')->nullOnDelete();
            $table->timestamps();
            $table->unique(['id_pengerjaan', 'id_soal']);
        });

        Schema::create('nilai', function (Blueprint $table) {
            $table->id('id_nilai');
            $table->foreignId('id_siswa')->constrained('siswa', 'id_siswa')->cascadeOnDelete();
            $table->foreignId('id_mapel')->constrained('mata_pelajaran', 'id_mapel')->restrictOnDelete();
            $table->enum('semester', ['1', '2']);
            $table->decimal('nilai_tugas', 5, 2)->nullable();
            $table->decimal('nilai_quiz', 5, 2)->nullable();
            $table->decimal('nilai_uts', 5, 2)->nullable();
            $table->decimal('nilai_uas', 5, 2)->nullable();
            $table->decimal('nilai_akhir', 5, 2)->nullable();
            $table->timestamps();
            $table->unique(['id_siswa', 'id_mapel', 'semester']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('nilai');
        Schema::dropIfExists('jawaban_siswa');
        Schema::dropIfExists('pengerjaan_quiz');
        Schema::dropIfExists('pilihan_jawaban');
        Schema::dropIfExists('soal_quiz');
        Schema::dropIfExists('quiz');
        Schema::dropIfExists('pengumpulan_tugas');
        Schema::dropIfExists('tugas');
        Schema::dropIfExists('modul');
        Schema::dropIfExists('mengajar');
        Schema::dropIfExists('mata_pelajaran');
        Schema::dropIfExists('siswa');
        Schema::dropIfExists('kelas');
        Schema::dropIfExists('guru');
    }
};
