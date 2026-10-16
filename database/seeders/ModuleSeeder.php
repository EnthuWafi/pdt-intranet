<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Module;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
class ModuleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // For internal module just handle here, make roles and everything here
        // 1. Ensure core internal modules exist in the catalog

        $stock = Module::firstOrCreate(
            ['code' => 'EZSTOK'],
            [
                'name' => 'Ez-Stok',
                'description' => 'Sistem Pengurusan Stok dan Inventori',
                'is_internal' => true,
                'is_active' => true,
            ]
        );

        $asset = Module::firstOrCreate(
            ['code' => 'EZASET'],
            [
                'name' => 'Ez-Aset',
                'description' => 'Sistem Pengurusan Aset Alih',
                'is_internal' => true,
                'is_active' => true,
            ]
        );

        // 2. Define base roles for internal modules so they appear in dropdowns
        $roles = [
            'EZSTOK: Admin',
            'EZSTOK: User',
            'EZSTOK: Viewer',
            'EZASET: Admin',
            'EZASET: User',
            'EZASET: Viewer',
        ];

        foreach ($roles as $roleName) {
            Role::firstOrCreate(['name' => $roleName, 'guard_name' => 'web']);
        }
    }
}
