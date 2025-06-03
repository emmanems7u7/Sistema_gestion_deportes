<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermisosSeeder_20250602 extends Seeder
{
    public function run()
    {

        Permission::create([
            'name' => 'Logica de negocio',
            'tipo' => 'seccion',
            'guard_name' => 'web',
            'id_relacion' => 16,
        ]);
        Permission::create([
            'name' => 'Competencias',
            'tipo' => 'menu',
            'guard_name' => 'web',
            'id_relacion' => 13,
        ]);
        Permission::create([
            'name' => 'Logica de Competencias',
            'tipo' => 'seccion',
            'guard_name' => 'web',
            'id_relacion' => 17,
        ]);
        Permission::create([
            'name' => 'Logica de Competencias 2',
            'tipo' => 'seccion',
            'guard_name' => 'web',
            'id_relacion' => 19,
        ]);
        Permission::create([
            'name' => 'competencias.ver',
            'tipo' => 'permiso',
            'guard_name' => 'web',
        ]);
        Permission::create([
            'name' => 'competencias.crear',
            'tipo' => 'permiso',
            'guard_name' => 'web',
        ]);
        Permission::create([
            'name' => 'competencias.guardar',
            'tipo' => 'permiso',
            'guard_name' => 'web',
        ]);
        Permission::create([
            'name' => 'competencias.editar',
            'tipo' => 'permiso',
            'guard_name' => 'web',
        ]);
        Permission::create([
            'name' => 'competencias.actualizar',
            'tipo' => 'permiso',
            'guard_name' => 'web',
        ]);
        Permission::create([
            'name' => 'competencias.eliminar',
            'tipo' => 'permiso',
            'guard_name' => 'web',
        ]);
        Permission::create([
            'name' => 'documento_competencia.ver',
            'tipo' => 'permiso',
            'guard_name' => 'web',
        ]);
        Permission::create([
            'name' => 'documento_competencia.crear',
            'tipo' => 'permiso',
            'guard_name' => 'web',
        ]);
        Permission::create([
            'name' => 'documento_competencia.guardar',
            'tipo' => 'permiso',
            'guard_name' => 'web',
        ]);
        Permission::create([
            'name' => 'documento_competencia.editar',
            'tipo' => 'permiso',
            'guard_name' => 'web',
        ]);
        Permission::create([
            'name' => 'documento_competencia.actualizar',
            'tipo' => 'permiso',
            'guard_name' => 'web',
        ]);
        Permission::create([
            'name' => 'documento_competencia.eliminar',
            'tipo' => 'permiso',
            'guard_name' => 'web',
        ]);
        Permission::create([
            'name' => 'Eventos',
            'tipo' => 'menu',
            'guard_name' => 'web',
            'id_relacion' => 20,
        ]);
    }
}
