@extends('layouts.argon')

@section('content')

    <div class="card">
        <div class="card-body">
            <h4>Listado de Competencias</h4>
            <a href="{{ route('competencias.create') }}" class="btn btn-sm btn-info">Crear competencia</a>

        </div>
    </div>

    <div class="card mt-2">
        <div class="card-body">
            @if($competencias->isNotEmpty())
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead class="table-dark">
                            <tr>
                                <th>Nombre</th>
                                <th>Descripción</th>
                                <th>Fecha de Inicio</th>
                                <th>Fecha de Fin</th>
                                <th>Categoría</th>
                                <th>Documentos asociados</th>
                                <th>Eventos asociados</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($competencias as $competencia)
                                <tr>
                                    <td>{{ $competencia->nombre }}</td>
                                    <td>{{ $competencia->descripcion }}</td>
                                    <td>{{ $competencia->fecha_inicio }}</td>
                                    <td>{{ $competencia->fecha_fin }}</td>
                                    <td>{{ $competencia->categoria_nombre }}</td>
                                    <td>
                                        @if($competencia->documentos->isEmpty())
                                            <p>No hay documentos asociados</p>
                                        @else
                                            @foreach ($competencia->documentos as $documento)
                                                <a href="{{ asset($documento->documento_ruta) }}" target="_blank">
                                                    - {{ $documento->documento_nombre }}
                                                </a><br>
                                            @endforeach
                                        @endif
                                    </td>
                                    <td>
                                        @if($competencia->eventos->isEmpty())
                                            <p>No hay eventos asociados</p>
                                        @else
                                            @foreach ($competencia->eventos as $evento)
                                                <p>
                                                    {{ $evento->nombre }}
                                                </p>
                                            @endforeach
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('documento_competencia.create', $competencia) }}"
                                            class="btn btn-sm btn-info">Agregar
                                            documentos
                                        </a>
                                        <a href="{{ route('competencias.edit', $competencia->id) }}"
                                            class="btn btn-warning btn-sm">Editar</a>
                                        <a href=""></a>
                                        <a type="button" class="btn btn-sm btn-danger" id="modal_edit_usuario_button"
                                            onclick="confirmarEliminacion('eliminarUsuarioForm', '¿Estás seguro de que deseas eliminar este usuario?')">Eliminar
                                            Usuario</a>
                                        <form id="eliminarCompetenciaForm" method="POST"
                                            action="{{ route('competencias.destroy', ['id' => $competencia->id]) }}"
                                            style="display: none;">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                    </td>
                                </tr>

                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p>No hay Competencias Registradas</p>
            @endif
        </div>
    </div>

@endsection