<?php

namespace App\Http\Controllers;

use App\Models\Evento;
use App\Models\Competencia;
use App\Models\Map;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
class EventoController extends Controller
{
    public function index(Request $request)
    {
        $breadcrumb = [
            ['name' => 'Inicio', 'url' => route('home')],
            ['name' => 'Eventos', 'url' => route('eventos.index')],


        ];
        $query = Evento::with('competencia');

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                // Buscar en campos del evento
                $q->where('ubicacion', 'like', "%$search%")
                    ->orWhere('fecha', 'like', "%$search%")
                    // Buscar en relación competencia, por ejemplo nombre
                    ->orWhereHas('competencia', function ($q2) use ($search) {
                        $q2->where('nombre', 'like', "%$search%");
                    });
            });
        }

        $eventos = $query->paginate(10);

        return view('eventos.index', compact('breadcrumb', 'eventos'));
    }

    public function create()
    {
        $breadcrumb = [
            ['name' => 'Inicio', 'url' => route('home')],
            ['name' => 'Eventos', 'url' => route('eventos.index')],
            ['name' => 'Crear Evento', 'url' => route('eventos.index')],

        ];
        $competencias = Competencia::all();
        $ubicaciones = Map::all();

        return view('eventos.create', compact('ubicaciones', 'breadcrumb', 'competencias'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'competencia_id' => 'required|exists:competencias,id',
            'fecha' => 'required|date',
            'hora_inicio' => 'required',
            'hora_fin' => 'required|after_or_equal:hora_inicio',
            'ubicacion' => 'required|string|max:255',
            'geolocalizacion' => 'nullable|string|max:255',

        ]);

        $competencia = Competencia::findOrFail($request->competencia_id);
        if (
            $request->fecha < $competencia->fecha_inicio ||
            $request->fecha > $competencia->fecha_fin
        ) {
            return back()->withErrors([
                'fecha' => 'La fecha del evento debe estar entre ' .
                    $competencia->fecha_inicio . ' y ' . $competencia->fecha_fin,
            ])->withInput();
        }

        Evento::create([
            'nombre' => $request->nombre,
            'competencia_id' => $request->competencia_id,
            'fecha' => $request->fecha,
            'hora_inicio' => $request->hora_inicio,
            'hora_fin' => $request->hora_fin,
            'ubicacion' => $request->ubicacion,
            'geolocalizacion' => $request->geolocalizacion,
            'accion_usuario' => Auth::user()->name,
        ]);


        return redirect()->route('eventos.index')->with('success', 'Evento creado correctamente.');
    }

    public function show(Evento $evento)
    {
        $evento->load('competencia');
        return view('eventos.show', compact('evento'));
    }

    public function edit(Evento $evento)
    {
        $breadcrumb = [
            ['name' => 'Inicio', 'url' => route('home')],
            ['name' => 'Eventos', 'url' => route('eventos.index')],
            ['name' => 'Editar Evento', 'url' => route('eventos.index')],


        ];
        $competencias = Competencia::all();
        return view('eventos.edit', compact('evento', 'breadcrumb', 'competencias'));
    }

    public function update(Request $request, Evento $evento)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'competencia_id' => 'required|exists:competencias,id',
            'fecha' => 'required|date',
            'hora_inicio' => 'required',
            'hora_fin' => 'required|after_or_equal:hora_inicio',
            'ubicacion' => 'required|string|max:255',
            'geolocalizacion' => 'nullable|string|max:255',
            'accion_usuario' => 'nullable|string|max:255',
        ]);

        $evento->update($request->all());

        return redirect()->route('eventos.index')->with('success', 'Evento actualizado correctamente.');
    }

    public function destroy(Evento $evento)
    {
        $evento->delete();
        return redirect()->route('eventos.index')->with('success', 'Evento eliminado correctamente.');
    }
}