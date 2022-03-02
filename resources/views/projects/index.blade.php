@extends('layouts.app')
@section('content')
<div id="subtitle" class="fila">
	<div class="columna columna-1">
        <div class="span-done" id="done_div"><span id="done_msg"></span></div>	
	</div>
</div>
<div class="fila">
    <div class="columna columna-1">
        <div class="title2">
            <h6>Procesos > Proyectos ></h6>
        </div>
    </div>
</div>
<div class="fila">
    <div class="columna columna-1">
        <div class="tab">
            <button type="button" class="tablinks" onclick="openTab(event,'defi')">Definidos</button>    
            <button type="button" class="tablinks active" onclick="openTab(event,'ejec')">En ejecución</button>
            <button type="button" class="tablinks" onclick="openTab(event,'susp')">Suspendidos</button>
            <button type="button" class="tablinks" onclick="openTab(event,'canc')">Cancelados</button>
            <button type="button" class="tablinks" onclick="openTab(event,'comp')">Completados</button>
        </div>
        <!-- Tab content -->
        <div>
            @include('projects/tabs/defi')    
            @include('projects/tabs/ejec')
            @include('projects/tabs/susp')
            @include('projects/tabs/canc')
            @include('projects/tabs/comp')
        </div>
    </div>
</div>
<div class="fila">
    <div class="space2"></div>
    <div class="columna columna-1">
        <center>
        <a href="{{ route('projects.create') }}" class="btn-effie"><i class="fa fa-plus"></i>&nbsp;Nuevo</a>
        <a href="{{ route('home') }}" class="btn-effie-inv"><i class="fa fa-home"></i>&nbsp;Ir al inicio</a>
        </center>
    </div>
</div>
@endsection