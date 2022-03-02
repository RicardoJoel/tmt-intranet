@extends('layouts.app')
@section('content')
<div class="fila">
	<div class="columna columna-1">
		<div class="title2">
			<h6>Procesos > Propuestas > Revisar ></h6>
		</div>
	</div>
</div>
<div class="fila">
	<div class="columna columna-1">
		<div class="form-section">
			<a onclick="showForm('gen')">
				<h6 id="gen_subt" class="title3">Datos generales</h6>
				<p id="icn-gen" class="icn-sct"><i class="fa fa-minus fa-icon"></i></p>
			</a>
			<div id="div-gen">
				<div class="fila">
					<div class="columna columna-5">
						<p>Código único :</p>
					</div>
					<div class="columna columna-5">
						<input type="text" value="{{ $proposal->code }}" readonly>
					</div>
					<div class="columna columna-5">
						<p>Fecha de propuesta :</p>
					</div>
					<div class="columna columna-5">
						<input type="text" value="{{ Carbon\Carbon::parse($proposal->happen_at)->format('d/m/Y') }}" readonly>
					</div>
					<div class="columna columna-5">
						<input type="text" value="{{ 'v'.$proposal->version.' / '.($proposal->status === 'P' ? 'PENDIENTE' : ($proposal->status === 'A' ? 'APROBADA' : 'RECHAZADA')) }}" style="text-align:center" readonly>
					</div>
				</div>
				<div class="fila">
					<div class="columna columna-5">
						<p>Datos del cliente :</p>
					</div>
					<div class="columna columna-5">
						<input type="text" value="{{ $proposal->customer->ruc ?? '' }}" readonly>
					</div>
					<div class="columna columna-5f">
						<input type="text" value="{{ ($proposal->customer->name ?? '').' ('.($proposal->customer->code ?? '').')' }}" readonly>
					</div>
				</div>
				<div class="fila">
					<div class="columna columna-5">
						<p>Datos del responsable :</p>
					</div>
					<div class="columna columna-5">
						<input type="text" value="{{ $proposal->accountable->document ?? '' }}" readonly>
					</div>
					<div class="columna columna-5f">
						<input type="text" value="{{ ($proposal->accountable->name ?? '').' '.($proposal->accountable->lastname ?? '').' ('.($proposal->accountable->code ?? '').')' }}" readonly>
					</div>
				</div>
				<div class="fila">
					<div class="columna columna-5">
						<p>Datos de la propuesta :</p>
					</div>
					<div class="columna columna-5">
						<input type="text" value="{{ ($proposal->projectType->name ?? '').' ('.($proposal->projectType->code ?? '').')' }}" readonly>
					</div>
					<div class="columna columna-5f">
						<input type="text" value="{{ $proposal->name }}" readonly>
					</div>
				</div>
				@if(!$proposal->visit_id)
				<div class="fila">
					<div class="columna columna-1">
						<p class="lbl-msg"><b>Nota: Propuesta sin visita previa</b></p>
					</div>
				</div>
				@endif
			</div>
		</div>
	</div>
</div>
<div class="fila">
	<div class="space"></div>
	<div class="columna columna-1">
		<div class="form-section">
			<a onclick="showForm('cmp')">
				<h6 id="cmp_subt" class="title3">Datos complementarios</h6>
				<p id="icn-cmp" class="icn-sct"><i class="fa fa-minus fa-icon"></i></p>
			</a>
			<div id="div-cmp">
				<div class="fila">
					<div class="columna columna-5">
						<p>Moneda de pago</p>
						<input type="text" value="{{ ($proposal->currency->name ?? '').' ('.($proposal->currency->code ?? '').')' }}" readonly>
					</div>
					<div class="columna columna-5">
						<p>Modalidad de pago</p>
						<input type="text" value="{{ $proposal->mode->name ?? '' }}" readonly>
					</div>
					<div class="columna columna-5">
						<p>Tipo de pago</p>
						<input type="text" value="{{ $proposal->type->name ?? '' }}" readonly>
					</div>
					<div class="columna columna-5">
						<p>Plazo de pago (sem)</p>
						<input type="text" value="{{ $proposal->term_weeks }}" readonly>
					</div>
					<div class="columna columna-5">
						<p>N° Orden de servicio</p>
						<input type="text" value="{{ $proposal->serv_order }}" readonly>
					</div>
				</div>
				<div class="fila">
					<div class="columna columna-5">
						<p>Total horas</p>
						<input type="text" value="{{ $proposal->totalHours }}" readonly>
					</div>
					<div class="columna columna-5">
						<p>Costo total (PEN)</p>
						<input type="text" value="{{ number_format($proposal->totalCost,2) }}" readonly>
					</div>
					<div class="columna columna-5">
						<p>Ajuste (%)</p>
						<input type="text" value="{{ number_format($proposal->perc_fit,2) }}" readonly>
					</div>
					<div class="columna columna-5">
						<p>Total comisión (%)</p>
						<input type="text" value="{{ number_format($proposal->totalCommission,2) }}" readonly>
					</div>
					<div class="columna columna-5">
						<p>Total proyecto (PEN)</p>
						<input type="text" value="{{ number_format($proposal->totalFinal,2) }}" readonly>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="fila">
	<div class="space"></div>
	<div class="columna columna-1">
		<div class="tab-step">
			<!-- Tab links -->
			<div class="tab">
				<button type="button" class="tablinks active" onclick="openTab(event,'tabemp')">Servidores</button>
				<button type="button" class="tablinks" onclick="openTab(event,'tabfre')">Independientes</button>
				<button type="button" class="tablinks" onclick="openTab(event,'tabpro')">Proveedores</button>
				<button type="button" class="tablinks" onclick="openTab(event,'tabsel')">Vendedores</button>
			</div>
			<!-- Tab content -->
			<div class="mycontent">
				@include('proposals/readonly/employees')
				@include('proposals/readonly/independents')
				@include('proposals/readonly/providers')
				@include('proposals/readonly/sellers')
			</div>
		</div>
	</div>
</div>
<div class="fila">
	<div class="space"></div>
	<div class="columna columna-1">
		<center>
		<a href="{{ route('proposals.index') }}" class="btn-effie-inv"><i class="fa fa-reply"></i>&nbsp;Regresar</a>
		</center>
	</div>
</div>
@endsection

@section('script')
<script src="{{ asset('js/resources/employees.js') }}"></script>
<script src="{{ asset('js/resources/independents.js') }}"></script>
<script src="{{ asset('js/resources/providers.js') }}"></script>
<script src="{{ asset('js/resources/sellers.js') }}"></script>
<script src="{{ asset('js/questions.js') }}"></script>
@endsection