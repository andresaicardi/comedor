@extends('layouts.app')

@section('title')
    Pedidos
@endsection

@section('TemplateName')
    Pedidos por dia
@endsection

@section('content')
    <div class="card">
        <div class="card-body">
            <showpedidosdia-component></showpedidosdia-component>
        </div>
    </div>
@endsection