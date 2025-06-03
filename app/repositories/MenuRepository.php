<?php
namespace App\Repositories;

use App\Interfaces\MenuInterface;
use Illuminate\Support\Facades\Auth;
use App\Models\Menu;
use Spatie\Permission\Models\Permission;
use App\Models\Seccion;
use App\Repositories\PermisoRepository;
class MenuRepository extends BaseRepository implements MenuInterface
{
    protected $permisoRepository;
    public function __construct(PermisoRepository $permisoRepository)
    {
        $this->permisoRepository = $permisoRepository;
        parent::__construct();

    }
    public function CrearMenu($request)
    {

        $menu = Menu::create([
            'nombre' => $this->cleanHtml($request->input('nombre')),
            'orden' => $this->cleanHtml($request->input('orden', 0)),
            'padre_id' => $this->cleanHtml($request->input('padre_id')) ?: null,
            'seccion_id' => $this->cleanHtml($request->input('seccion_id')),
            'ruta' => $this->cleanHtml($request->input('ruta')),
            'accion_usuario' => Auth::user()->na
        ]);


        $this->permisoRepository->store_permiso($menu->nombre, 'menu', 'web', ['id_relacion' => $menu->id]);
    }
    public function CrearSeccion($request)
    {
        $seccion = Seccion::create(
            [
                'titulo' => $this->cleanHtml($request->input('titulo')),
                'icono' => $this->cleanHtml($request->input('icono')),
                'posicion' => $this->cleanHtml($request->input('posicion', 0)),
                'accion_usuario' => Auth::user()->name,
            ]
        );


        $this->permisoRepository->store_permiso($seccion->titulo, 'seccion', 'web', ['id_relacion' => $seccion->id]);

    }

    public function ObtenerMenuPorSeccion($seccion_id)
    {
        $menus = Menu::Where('seccion_id', $seccion_id)->orderBy('orden')->get();
        return $menus;
    }
}
