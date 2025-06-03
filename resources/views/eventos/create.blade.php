@extends('layouts.argon')

@section('content')
    <div class="card">
        <div class="card-body">
            <h1>Crear Evento</h1>

            <form action="{{ route('eventos.store') }}" method="POST">
                @include('eventos._form')
            </form>
        </div>
    </div>
    <!-- Modal -->

@endsection