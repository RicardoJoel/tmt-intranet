@extends('layouts.app')
@section('content')
<div class="fila">
	<div class="columna columna-1">
		<div class="span-fail" id="fail-div"><span id="fail-msg"></span></div>
	</div>
</div>
<div class="fila">
	<div class="columna columna-1">
		<div class="title2">
			<h6>Procesos > Visitas > Nueva ></h6>
		</div>
	</div>
</div>
<form method="POST" action="{{ route('visits.store') }}" role="form" id="frm-visit">
	@csrf
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
							<input type="text" readonly>
						</div>
						<div class="columna columna-5e">
							<p>Fecha y hora visita* :</p>
						</div>
						<div class="columna columna-5b">
							<input type="datetime-local" name="happen_at" id="happen_at" value="{{ old('happen_at') }}" required>
						</div>
						<div class="columna columna-5">
							@inject('projectTypes','App\Services\ProjectTypes')
							<select name="project_type_id" id="project_type_id" required>
								<option selected readonly hidden value="">Tipo*</option>
								@foreach ($projectTypes->get() as $index => $projectType)
								<option value="{{ $index }}" {{ old('project_type_id') == $index ? 'selected' : '' }}>
									{{ $projectType }}
								</option>
								@endforeach
							</select>	
						</div>
					</div>
					<div class="fila">
						<div class="columna columna-1">
							<div class="span-fail" id="customer_fail-div"><span id="customer_fail-msg"></span></div>
						</div>
					</div>
					<div class="fila">
						<div class="columna columna-5">
							<p>Datos del cliente :</p>
						</div>
						<div class="columna columna-5">
							<input type="hidden" name="customer_id" id="customer_id" value="{{ old('customer_id') }}">
							<input type="hidden" name="customer_ref" id="customer_ref" value="{{ old('customer_ref') }}">
							<div class="search_field">
								<input type="text" name="customer_ruc" id="customer_ruc" value="{{ old('customer_ruc') }}" maxlength="11" onkeypress="return checkNumber(event)" placeholder="R. U. C.*" required>
								<a onclick="clearDataCust()"><i class="fa fa-close fa-icon"></i></a>
							</div>
						</div>
						<div class="columna columna-5d">
							<input type="text" name="customer_name" id="customer_name" value="{{ old('customer_name') }}" placeholder=" -- Nombre comercial y código* -- " readonly>
						</div>
						<div class="columna columna-5">
							<button type="button" id="btn-sch-cust" class="btn-effie-inv" style="width:100%"><i class="fa fa-search"></i>&nbsp;Buscar cliente</button>
						</div>
					</div>
					<div class="fila">
						<div class="columna columna-1">
							<div class="span-fail" id="user_fail-div"><span id="user_fail-msg"></span></div>
						</div>
					</div>
					<div class="fila">
						<div class="columna columna-5">
							<p>Datos del responsable :</p>
						</div>
						<div class="columna columna-5">
							<input type="hidden" name="user_id" id="user_id" value="{{ old('user_id') }}">
							<div class="search_field">
								<input type="text" name="user_document" id="user_document" value="{{ old('user_document') }}" maxlength="15" onkeypress="return checkAlNum(event)" placeholder="N° Documento*" required>
								<a onclick="clearDataUser()"><i class="fa fa-close fa-icon"></i></a>
							</div>
						</div>
						<div class="columna columna-5d">
							<input type="text" name="user_wholename" id="user_wholename" value="{{ old('user_wholename') }}" placeholder=" -- Nombre completo y código* -- " readonly>
						</div>
						<div class="columna columna-5">
							<button type="button" id="btn-sch-user" class="btn-effie-inv" style="width:100%"><i class="fa fa-search"></i>&nbsp;Buscar respon.</button>
						</div>
					</div>
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
							<label><input type="checkbox" name="is_done" id="is_done" value="1" {{ old('is_done') ? 'checked="checked"' : '' }}>&nbsp;Visita concretada</label>
						</div>
						<div class="columna columna-5">
							<label><input type="checkbox" name="by_reference" id="by_reference" value="1" {{ old('by_reference') ? 'checked="checked"' : '' }}>&nbsp;Por referencia</label>
						</div>
						<div class="columna columna-5">
							<label><input type="checkbox" name="has_proposal" id="has_proposal" value="1" {{ old('has_proposal') ? 'checked="checked"' : '' }}>&nbsp;Agregar propuesta</label>
						</div>
						<div class="columna columna-5">
							<p style="text-align:right">Código de propuesta :</p>
						</div>
						<div class="columna columna-5">
							<input type="text" name="prop_code" id="prop_code" value="{{ old('prop_code') }}" readonly>
						</div>
					</div>
					<div class="fila">
						<div class="columna columna-1">
							<p>Observaciones (máx. 500 caracteres)</p>
							<textarea name="observation" id="observation" maxlength="500" rows="4">{{ old('observation') }}</textarea>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</form>
@include('visits/submit')
@endsection