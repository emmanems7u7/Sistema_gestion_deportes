@extends('layouts.argon')

@section('content')



    <div class="card">
        <div class="card-body">
            <h5>Crear Mapa</h5>

            <form action="{{ route('maps.store', 0) }}" method="POST">
                @include('maps._form')

                <button type="submit" class="btn btn-success">Crear</button>
                <a href="{{ route('maps.index') }}" class="btn btn-secondary">Cancelar</a>
            </form>
        </div>
    </div>








@endsection