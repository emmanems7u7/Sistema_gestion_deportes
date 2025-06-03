<?php

            use Illuminate\Database\Seeder;
            use Spatie\Permission\Models\Permission;

            class PermisosSeeder_20250603 extends Seeder
            {
                public function run()
                {
                    Permission::create([
            'name' => 'Mapas',
            'tipo' => 'menu',
            'guard_name' => 'web',
            'id_relacion' => 21,
        ]);
    }
            }
            