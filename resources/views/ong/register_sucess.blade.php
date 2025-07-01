@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="alert alert-success">
            @if(session('success'))
                {{ session('success') }}
            @else
                Seu cadastro foi enviado para análise. Você receberá um e-mail quando for aprovado.
            @endif
        </div>
    </div>
@endsection