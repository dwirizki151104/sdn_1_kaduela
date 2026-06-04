<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'remember_token')) {
                $table->dropColumn('remember_token');
            }

            if (Schema::hasColumn('users', 'updated_at')) {
                $table->dropColumn('updated_at');
            }
        });

        DB::statement('ALTER TABLE users MODIFY username VARCHAR(50) NOT NULL');
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (! Schema::hasColumn('users', 'remember_token')) {
                $table->rememberToken()->after('status');
            }

            if (! Schema::hasColumn('users', 'updated_at')) {
                $table->timestamp('updated_at')->nullable()->after('created_at');
            }
        });

        DB::statement('ALTER TABLE users MODIFY username VARCHAR(255) NOT NULL');
    }
};
