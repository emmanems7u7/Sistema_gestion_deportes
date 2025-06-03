@extends('layouts.argon')

@section('content')
    <div class="card">
        <div class="card-body">
            <h5>Eventos</h5>
            <div class="d-flex justify-content-between align-items-center">
                <a href="{{ route('eventos.create') }}" class="btn btn-primary">Crear Evento</a>

                <form method="GET" action="{{ route('eventos.index') }}" class="d-flex" role="search"
                    style="max-width: 300px;">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Buscar..."
                        class="form-control me-2" />
                    <button type="submit" class="btn btn-primary">Buscar</button>
                </form>
            </div>
        </div>
    </div>


    <div class="card mt-3">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead class="table-dark">
                        <tr>

                            <th>
                                <a href="{{ route('eventos.index') }}" class="text-white">
                                    Competencia
                                </a>
                            </th>

                            <th>Nombre</th>
                            <th>Fecha</th>
                            <th>Hora Inicio</th>
                            <th>Hora Fin</th>
                            <th>Ubicación</th>
                            <th>Geolocalización</th>

                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($eventos as $evento)
                            <tr>

                                <td>
                                    @if($evento->competencia)
                                        <a href="{{ route('eventos.index', ['search' => $evento->competencia->nombre]) }}"
                                            class="text-info">
                                            {{ $evento->competencia->nombre }}
                                        </a>
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>{{ $evento->nombre }}</td>

                                <td>{{ $evento->fecha }}</td>
                                <td>{{ $evento->hora_inicio }}</td>
                                <td>{{ $evento->hora_fin }}</td>
                                <td>{{ $evento->ubicacion }}</td>
                                <td>{{ $evento->geolocalizacion }}</td>

                                <td>
                                    <a href="{{ route('eventos.show', $evento) }}" class="btn btn-info btn-sm">Ver</a>
                                    <a href="{{ route('eventos.edit', $evento) }}" class="btn btn-warning btn-sm">Editar</a>
                                    <form action="{{ route('eventos.destroy', $evento) }}" method="POST"
                                        style="display:inline-block" onsubmit="return confirm('¿Eliminar evento?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-danger btn-sm">Eliminar</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                {{ $eventos->appends(request()->only('search'))->links() }}
            </div>
        </div>
    </div>
@endsection