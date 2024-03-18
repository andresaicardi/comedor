@extends('layouts.app')

@section('title')
    Pedidos
@endsection

@section('TemplateName')
    Pedidos por Semana
@endsection

@section('content')
    <div class="card">
        <div class="card-body">
            <showpedido-component></showpedido-component>
        </div>
    </div>
@endsection