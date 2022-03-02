@extends('layouts.app')
@section('content')
<div class="fila">
    <div class="columna columna-1">
        <div class="title2">
            <h6>Utilitarios > Reporte de tiempos ></h6>
        </div>
    </div>
</div>
<form method="GET" action="{{ route('activities.descargar') }}">
    <div class="fila">
        <div class="columna columna-1">
            <div class="form-section">
                <a onclick="showForm('gen')">
                    <h6 id="gen_subt" class="title3">Indicaciones</h6>
                    <p id="icn-gen" class="icn-sct"><i class="fa fa-minus fa-icon"></i></p>
                </a>
                <div id="div-gen">
                    <div class="fila">
                        <div class="columna columna-1">
                            <p>Al generar el reporte, se mostrará el tiempo total agrupado por cliente, proyecto y servidor involucrado. Para conocer el detalle de las actividades realizadas por un servidor debes hacer clic en la fila correspondiente.</p>
                        </div>
                    </div>
                    <div class="space"></div>
                    <div class="fila">
                        <div class="columna columna-2">
                            <p>Proyecto</p>
                            <select id="project_id" name="project_id"></select>					
                        </div>
                        <div class="columna columna-6">
                            <p>Estado</p>
                            <select id="status" name="status"></select>
                        </div>
                        <div class="columna columna-6">
                            <p>Fecha inicial</p>
                            <input type="date" name="start_at" value="{{ old('start_at',Carbon\Carbon::today()->subDays(4)->toDateString()) }}" max="{{ Carbon\Carbon::today()->toDateString() }}" required>
                        </div>
                        <div class="columna columna-6">
                            <p>Fecha final</p>
                            <input type="date" name="end_at" value="{{ old('end_at',Carbon\Carbon::today()->toDateString()) }}" max="{{ Carbon\Carbon::today()->toDateString() }}" required>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="fila">
        <div class="space"></div>
        <div class="columna columna-1">
            <center>
            <button type="submit" class="btn-effie"><i class="fa fa-spinner"></i>&nbsp;Generar</button>
            <a href="{{ route('activities.report') }}" class="btn-effie-inv"><i class="fa fa-paint-brush"></i>&nbsp;Limpiar</a>
            <a href="{{ route('home') }}" class="btn-effie-inv"><i class="fa fa-home"></i>&nbsp;Ir al inicio</a>
            </center>
        </div>
    </div>
</form>
@endsection

@section('script')
<script src="{{ asset('js/filters.js') }}"></script>
@endsection