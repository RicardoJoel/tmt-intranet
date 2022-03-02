@extends('layouts.app')
@section('content')
<div class="fila">
    <div class="columna columna-1">
        <div class="title2">
            <h6>Menú principal ></h6>
        </div>
    </div>
</div>
<div class="space1"></div>
<div class="fila">
    <div class="columna columna-1">
        <h6 class="title3">Selecciona una opción</h6>
    </div>
</div>
<div class="fila">
    <div class="columna columna-5">
        <div class="scene">
            <div class="card">
                <a href="{{ route('activities.index') }}">
                    <div class="card__face card__face--front">
                        <div class="content" title="Ingresa tus actividades del día">
                            <i class="fa fa-calendar fa-4x"></i>                            
                            <p>Mis actividades</p>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>
    <div class="columna columna-5">
        <div class="scene">
            <div class="card">
                <a href="{{ route('activities.report') }}">
                    <div class="card__face card__face--front">
                        <div class="content" title="Reporte de actividades por proyecto">
                            <i class="fa fa-bar-chart fa-4x"></i>                            
                            <p>Reporte de tiempos</p>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>
    <div class="columna columna-5">
        <div class="scene">    
            <div class="card">
                <a href="{{ route('profile') }}">
                    <div class="card__face card__face--front">
                        <div class="content" title="Actualiza tus datos personales">
                            <i class="fa fa-address-card fa-4x"></i>                            
                            <p>Mis datos</p>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>
    <div class="columna columna-5">
        <div class="scene">
            <div class="card">
                <a href="{{ route('password') }}">
                    <div class="card__face card__face--front">
                        <div class="content" title="Actualiza regularmente tu contraseña">
                            <i class="fa fa-lock fa-4x"></i>                            
                            <p>Seguridad</p>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>
</div>
@endsection