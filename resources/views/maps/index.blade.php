@extends('layouts.argon')

@section('content')
    <div class="card">
        <div class="card-body">
            <h5>Catálogo de Mapas</h5>

            <a href="{{ route('maps.create') }}" class="btn btn-primary mb-3">Agregar Nuevo Mapa</a>

        </div>
    </div>

    <div class="card mt-3">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead class="table-dark">
                        <tr>
                            <th>Título</th>
                            <th>Descripción</th>
                            <th>Latitud</th>
                            <th>Longitud</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($maps as $map)
                            <tr>
                                <td>{{ $map->titulo }}</td>
                                <td>{{ $map->descripcion }}</td>
                                <td>{{ $map->latitud }}</td>
                                <td>{{ $map->longitud }}</td>
                                <td>
                                    <button type="button" class="btn btn-sm btn-info" data-bs-toggle="modal"
                                        data-bs-target="#mapModal" data-lat="{{ $map->latitud }}"
                                        data-lng="{{ $map->longitud }}" data-title="{{ $map->titulo }}">
                                        Ver en mapa
                                    </button>
                                    <a href="{{ route('maps.edit', $map) }}" class="btn btn-sm btn-warning">Editar</a>

                                    <a type="button" class="btn btn-sm btn-danger"
                                        onclick="confirmarEliminacion('eliminarmapaForm', '¿Estás seguro de que deseas eliminar este mapa?')">Eliminar
                                    </a>

                                    <form id="eliminarmapaForm" method="POST" action="{{ route('maps.destroy', $map) }}"
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
        </div>
    </div>

    <div class="modal fade" id="mapModal" tabindex="-1" aria-labelledby="mapModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="mapModalLabel">Ubicación del Mapa</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <div id="modalMap" style="height: 400px; width: 100%;"></div>
                </div>
            </div>
        </div>
    </div>

    <script>
        let modalMap;
        let marker;

        const initModalMap = () => {
            modalMap = L.map('modalMap').setView([0, 0], 2);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '&copy; OpenStreetMap contributors'
            }).addTo(modalMap);
        };

        const updateModalMap = (lat, lng, title) => {
            modalMap.setView([lat, lng], 13);
            if (marker) marker.setLatLng([lat, lng]);
            else marker = L.marker([lat, lng]).addTo(modalMap);
            marker.bindPopup(title).openPopup();
        };

        document.addEventListener('DOMContentLoaded', () => {
            const mapModal = document.getElementById('mapModal');

            mapModal.addEventListener('show.bs.modal', event => {
                if (!modalMap) {
                    initModalMap();  // Inicializa solo cuando se abre el modal la primera vez
                }

                const button = event.relatedTarget;
                const lat = parseFloat(button.getAttribute('data-lat'));
                const lng = parseFloat(button.getAttribute('data-lng'));
                const title = button.getAttribute('data-title');

                updateModalMap(lat, lng, title);
            });

            mapModal.addEventListener('shown.bs.modal', () => {
                modalMap.invalidateSize();  // Forzar que Leaflet reajuste el tamaño
            });

            mapModal.addEventListener('hidden.bs.modal', () => {
                if (marker) {
                    modalMap.removeLayer(marker);
                    marker = null;
                }
                modalMap.setView([0, 0], 2);
            });
        });
    </script>
@endsection