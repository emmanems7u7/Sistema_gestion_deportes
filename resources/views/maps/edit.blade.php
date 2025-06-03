@extends('layouts.argon')


@section('content')

    <div class="card">
        <div class="card-body">
            <h5>Editar Mapa</h5>

            <form action="{{ route('maps.update', $map) }}" method="POST">
                @csrf
                @method('PUT')

                @include('maps._form')

                <button type="submit" class="btn btn-success">Actualizar</button>
                <a href="{{ route('maps.index') }}" class="btn btn-secondary">Cancelar</a>
            </form>
        </div>
    </div>


@endsection