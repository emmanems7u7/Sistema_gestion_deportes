<?php
namespace App\Repositories;

use App\Interfaces\PermisoInterface;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\Menu;
use App\Models\Seccion;
use Illuminate\Support\Facades\File;
class PermisoRepository extends BaseRepository implements PermisoInterface
{
    protected $permissions;

    public function __construct()
    {
        parent::__construct();
        $this->permissions = Permission::all();
    }
    public function GetPermisosTipo($tipo)
    {

        return $this->permissions->where('tipo', $tipo);
    }
    public function GetPermisoMenu($id, $rol_id)
    {

        $permission = Permission::findOrFail($id);

        $menus = Seccion::with('menus')->find($permission->id_relacion)->menus;

        if ($rol_id != -1) {
            $role = Role::find($rol_id);

        } else {
            $role = Role::all();

        }

        $permisos_menu = $permission->where('tipo', 'menu')->get();

        foreach ($permisos_menu as $permiso_menu) {
            foreach ($menus as $menu) {
                if ($permiso_menu->id_relacion == $menu->id) {

                    $permission = $permission->where('id_relacion', $menu->id)->where('tipo', 'menu')->first();

                    if ($rol_id != -1) {
                        if ($role->hasPermissionTo($permission)) {
                            $permission->check = true;
                        } else {
                            $permission->check = false;
                        }
                    }
                    $permisosPorTipo[] = $permission;

                }
            }

        }
        return $permisosPorTipo;
    }
    public function GetPermisoTipo($id, $tipo)
    {

    }
    function CrearPermiso($request)
    {
        $name = $this->cleanHtml($request->name);

        $this->store_permiso($name);
    }

    public function store_permiso($name, $tipo = 'permiso', $guard_name = 'web', $extra = [])
    {
        // Armar atributos base para firstOrCreate
        $atributos = array_merge([
            'name' => $name,
            'tipo' => $tipo,
            'guard_name' => $guard_name,
        ], $extra);

        // Crear permiso si no existe
        Permission::firstOrCreate(
            [
                'name' => $name,
                'tipo' => $tipo,
            ],
            $extra
        );

        // Crear el código PHP para el seeder con todos los atributos
        // Esto genera algo tipo:
        // Permission::create([
        //     'name' => 'xxx',
        //     'tipo' => 'permiso',
        //     'guard_name' => 'web',
        //     'id_relacion' => 123,
        // ]);
        $camposPhp = [];
        foreach ($atributos as $campo => $valor) {
            $valorPhp = is_numeric($valor) ? $valor : "'{$valor}'";
            $camposPhp[] = "            '{$campo}' => {$valorPhp},";
        }
        $nuevoPermiso = "        Permission::create([\n" . implode("\n", $camposPhp) . "\n        ]);\n";

        // Seeder
        $fecha = date('Ymd');
        $seederName = "PermisosSeeder_{$fecha}";
        $filePath = base_path("database/seeders/{$seederName}.php");

        if (!File::exists($filePath)) {
            $contenido = "<?php

            use Illuminate\Database\Seeder;
            use Spatie\Permission\Models\Permission;

            class {$seederName} extends Seeder
            {
                public function run()
                {
            {$nuevoPermiso}    }
            }
            ";
            File::put($filePath, $contenido);
        } else {
            $contenidoActual = File::get($filePath);

            // Evitar duplicados en el seeder (opcional)
            if (strpos($contenidoActual, "'name' => '{$name}'") === false) {
                $pos = strrpos($contenidoActual, "    }");

                if ($pos !== false) {
                    $nuevoContenido = substr_replace($contenidoActual, $nuevoPermiso, $pos, 0);
                    File::put($filePath, $nuevoContenido);
                }
            }
        }
    }
    public function EditarPermiso($request, $permission)
    {

        $permission->update([
            'name' => $request->name,
        ]);

    }
}
