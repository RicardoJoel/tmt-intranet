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
        <h6 class="title3">Entidades</h6>
    </div>
</div>
<div class="fila">
    <div class="columna columna-5">
        <div class="scene">
            <div class="card">
                <a href="{{ route('users.index') }}">
                    <div class="card__face card__face--front">
                        <div class="content" title="Actualiza la lista de servidores">
                            <i class="fa fa-user-circle fa-4x"></i>                            
                            <p>Servidores</p>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>
    <div class="columna columna-5">
        <div class="scene">
            <div class="card">
                <a href="{{ route('customers.index') }}">
                    <div class="card__face card__face--front">
                        <div class="content" title="Actualiza la lista de clientes">
                            <i class="fa fa-briefcase fa-4x"></i>                            
                            <p>Clientes</p>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>
    <div class="columna columna-5">
        <div class="scene">
            <div class="card">
                <a href="{{ route('suppliers.index') }}">
                    <div class="card__face card__face--front">
                        <div class="content" title="Actualiza la lista de proveedores">
                            <i class="fa fa-truck fa-4x"></i>                            
                            <p>Proveedores</p>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>
    <div class="columna columna-5">
        <div class="scene">
            <div class="card">
                <a href="{{ route('freelancers.index') }}">
                    <div class="card__face card__face--front">
                        <div class="content" title="Actualiza la lista de independientes">
                            <i class="fa fa-camera-retro fa-4x"></i>                            
                            <p>Independientes</p>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>
    <div class="columna columna-5">
        <div class="scene">
            <div class="card">
                <a href="#">
                    <div class="card__face card__face--front">
                        <div class="content" title="Actualiza la lista de servidores">
                            <i class="fa fa-users fa-4x"></i>                            
                            <p>Equipos</p>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>
</div>
<div class="fila">
    <div class="space"></div>
    <div class="columna columna-1">
        <h6 class="title3">Procesos</h6>
    </div>
</div>
<div class="fila">
    <div class="columna columna-5">
        <div class="scene">
            <div class="card">
                <a href="{{ route('visits.index') }}">
                    <div class="card__face card__face--front">
                        <div class="content" title="Registra tus contactos con clientes">
                            <i class="fa fa-street-view fa-4x"></i>                            
                            <p>Visitas</p>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>
    <div class="columna columna-5">
        <div class="scene">
            <div class="card">
                <a href="{{ route('proposals.index') }}">
                    <div class="card__face card__face--front">
                        <div class="content" title="Registra tus propuestas de proyecto">
                            <i class="fa fa-file-powerpoint-o fa-4x"></i>                            
                            <p>Propuestas</p>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>
    <div class="columna columna-5">
        <div class="scene">
            <div class="card">
                <a href="{{ route('projects.index') }}">
                    <div class="card__face card__face--front">
                        <div class="content" title="Registra y estructura tus proyectos">
                            <i class="fa fa-sitemap fa-4x"></i>                            
                            <p>Proyectos</p>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>
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
</div>
<div class="fila">
    <div class="space"></div>
    <div class="columna columna-1">
        <h6 class="title3">Otros</h6>
    </div>
</div>
<div class="fila">
    <div class="columna columna-5">
        <div class="scene">
            <div class="card">
                <a href="{{ route('parameters.index') }}">
                    <div class="card__face card__face--front">
                        <div class="content" title="Gestión de parámetros del sistema">
                            <i class="fa fa-cog fa-4x"></i>                            
                            <p>Ajustes</p>
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