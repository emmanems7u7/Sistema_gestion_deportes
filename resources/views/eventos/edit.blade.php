@extends('layouts.argon')

@section('content')
    <div class="card">
        <div class="card-body">
            <h1>Editar Evento</h1>

            <form action="{{ route('eventos.update', $evento) }}" method="POST">
                @include('eventos._form')
            </form>
        </div>
    </div>

@endsection