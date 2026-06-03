<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasColumn('users', 'id') && ! Schema::hasColumn('users', 'id_user')) {
            Schema::table('users', function (Blueprint $table) {
                $table->renameColumn('id', 'id_user');
            });
        }

        Schema::table('users', function (Blueprint $table) {
            if (! Schema::hasColumn('users', 'username')) {
                $table->string('username')->nullable()->after('id_user');
            }

            if (! Schema::hasColumn('users', 'role')) {
                $table->enum('role', ['admin', 'guru', 'siswa'])->default('siswa')->after('password');
            }

            if (! Schema::hasColumn('users', 'status')) {
                $table->enum('status', ['aktif', 'nonaktif'])->default('aktif')->after('role');
            }
        });

        $users = DB::table('users')->get();

        foreach ($users as $user) {
            if (! empty($user->username)) {
                continue;
            }

            $source = $user->email ?? $user->name ?? "user{$user->id_user}";
            $username = str($source)->before('@')->lower()->replaceMatches('/[^a-z0-9_]+/', '_')->trim('_')->toString();
            $username = $username ?: "user{$user->id_user}";

            $baseUsername = $username;
            $counter = 1;

            while (DB::table('users')->where('username', $username)->exists()) {
                $username = "{$baseUsername}_{$counter}";
                $counter++;
            }

            DB::table('users')
                ->where('id_user', $user->id_user)
                ->update(['username' => $username]);
        }

        if (! Schema::hasIndex('users', 'users_username_unique')) {
            Schema::table('users', function (Blueprint $table) {
                $table->unique('username');
            });
        }

        DB::statement('ALTER TABLE users MODIFY username VARCHAR(255) NOT NULL');

        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'email')) {
                if (Schema::hasIndex('users', 'users_email_unique')) {
                    $table->dropUnique('users_email_unique');
                }

                $table->dropColumn('email');
            }

            if (Schema::hasColumn('users', 'name')) {
                $table->dropColumn('name');
            }

            if (Schema::hasColumn('users', 'email_verified_at')) {
                $table->dropColumn('email_verified_at');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'id_user') && ! Schema::hasColumn('users', 'id')) {
                $table->renameColumn('id_user', 'id');
            }
        });
    }
};
