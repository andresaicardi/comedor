@extends('layouts.app')

<style>
    body {
        background-color: #000;
    }

    .custom-card {
        background: linear-gradient(to bottom right, #1a1a1a, #004080);
        border: none;
        color: #fff;
    }
</style>

@section('title')
    QR - {{$nombreCompleto}}
@endsection


@section('content')
    <div class="card" style="width: 100%; text-align: center;">
        <div class="card-body">
            <h5 class="card-title" style="width: 100%; text-align: center;">QR - {{$nombreCompleto}}</h5>
            <br>
            <br>

            <?php 
                $valor=QrCode::size(250)->generate($qr);
            ?>

            <p class="card-text">{{ $valor }}</p>
        </div>
    </div>
@endsection
