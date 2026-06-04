<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AdminMasterUserCrudTest extends TestCase
{
    public function test_admin_can_manage_users_from_master_crud(): void
    {
        if (config('database.default') === 'sqlite' && ! extension_loaded('pdo_sqlite')) {
            $this->markTestSkipped('Ekstensi pdo_sqlite belum aktif, sementara phpunit.xml memakai SQLite in-memory.');
        }

        $admin = User::factory()->create([
            'role' => 'admin',
            'status' => 'aktif',
        ]);

        try {
            $this->actingAs($admin)
                ->get(route('admin.master.index', 'users'))
                ->assertOk()
                ->assertSee('Data User');

            $this->actingAs($admin)
                ->post(route('admin.master.store', 'users'), [
                    'username' => 'guru_crud',
                    'password' => 'password123',
                    'role' => 'guru',
                    'status' => 'aktif',
                ])
                ->assertRedirect(route('admin.master.index', 'users'));

            $user = User::where('username', 'guru_crud')->firstOrFail();

            $this->assertTrue(Hash::check('password123', $user->password));

            $this->actingAs($admin)
                ->put(route('admin.master.update', ['users', $user->id_user]), [
                    'username' => 'guru_crud_edit',
                    'password' => '',
                    'role' => 'guru',
                    'status' => 'nonaktif',
                ])
                ->assertRedirect(route('admin.master.index', 'users'));

            $user->refresh();

            $this->assertSame('guru_crud_edit', $user->username);
            $this->assertSame('nonaktif', $user->status);
            $this->assertTrue(Hash::check('password123', $user->password));

            $this->actingAs($admin)
                ->delete(route('admin.master.destroy', ['users', $user->id_user]))
                ->assertRedirect(route('admin.master.index', 'users'));

            $this->assertDatabaseMissing('users', [
                'id_user' => $user->id_user,
            ]);
        } finally {
            User::whereIn('username', ['guru_crud', 'guru_crud_edit'])->delete();
            $admin->delete();
        }
    }
}
