@extends('layouts.app')
@section('content')
<div id="subtitle" class="fila">
	<div class="columna columna-1">
		<div class="span-fail" id="fail_div"><span id="fail_msg"></span></div>
	</div>
</div>
<div class="fila">
	<div class="columna columna-1">
		<div class="title2">
			<h6>Procesos > Proyectos > Nuevo ></h6>
		</div>
	</div>
</div>
<form method="POST" action="{{ route('projects.update',$project->id) }}" role="form" id="frm-proj">
	@csrf
	<input type="hidden" name="_method" id="_method" value="PATCH">
	<input type="hidden" name="proposal_id" id="proposal_id" value="{{ old('proposal_id',$project->proposal_id) }}">
	<input type="hidden" name="aux_code" id="aux_code" value="{{ old('aux_code',$project->code) }}">
	<input type="checkbox" id="has_proposal" checked="checked" style="display:none">
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
							<input type="text" name="code" id="prop_code" value="{{ old('code',$project->code) }}" disabled>
						</div>
						<div class="columna columna-5">
							<p>Fecha de inicio* :</p>
						</div>
						<div class="columna columna-5">
							<input type="date" name="happen_at" id="happen_at" value="{{ old('happen_at',$project->happen_at) }}" required>
						</div>
						<div class="columna columna-5">
							<select name="status" id="status" required>
								<option selected disabled hidden value="">Estado actual*</option>
								<option value="DEFINIDO" {{ old('status',$project->status) == 'DEFINIDO' ? 'selected' : '' }}>DEFINIDO</option>
								<option value="EN EJECUCIÓN" {{ old('status',$project->status) == 'EN EJECUCIÓN' ? 'selected' : '' }}>EN EJECUCIÓN</option>
								<option value="SUSPENDIDO" {{ old('status',$project->status) == 'SUSPENDIDO' ? 'selected' : '' }}>SUSPENDIDO</option>
								<option value="CANCELADO" {{ old('status',$project->status) == 'CANCELADO' ? 'selected' : '' }}>CANCELADO</option>
								<option value="COMPLETADO" {{ old('status',$project->status) == 'COMPLETADO' ? 'selected' : '' }}>COMPLETADO</option>
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
							<input type="hidden" name="customer_id" id="customer_id" value="{{ old('customer_id',$project->customer_id) }}">
							<input type="hidden" name="customer_ref" id="customer_ref" value="{{ old('customer_ref',$project->customer->ref ?? '') }}">
							<div class="search_field">
								<input type="text" name="customer_ruc" id="customer_ruc" value="{{ old('customer_ruc',$project->customer->ruc ?? '') }}" maxlength="11" onkeypress="return checkNumber(event)" placeholder="R. U. C.*" required>
								<a onclick="clearDataCust()"><i class="fa fa-close fa-icon" title="Borrar entrada"></i></a>
							</div>
						</div>
						<div class="columna columna-5d">
							<input type="text" name="customer_name" id="customer_name" value="{{ old('customer_name',$project->customer->nameCode ?? '') }}" placeholder=" -- Nombre comercial y código* -- " disabled>
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
							<input type="hidden" name="user_id" id="user_id" value="{{ old('user_id',$project->user_id) }}">
							<div class="search_field">
								<input type="text" name="user_document" id="user_document" value="{{ old('user_document',$project->accountable->document ?? '') }}" maxlength="15" onkeypress="return checkAlNum(event)" placeholder="N° Documento*" required>
								<a onclick="clearDataUser()"><i class="fa fa-close fa-icon"></i></a>
							</div>
						</div>
						<div class="columna columna-5d">
							<input type="text" name="user_wholename" id="user_wholename" value="{{ old('user_wholename',$project->accountable->fullnameCode ?? '') }}" placeholder=" -- Nombre completo y código* -- " disabled>
						</div>
						<div class="columna columna-5">
							<button type="button" id="btn-sch-user" class="btn-effie-inv" style="width:100%"><i class="fa fa-search"></i>&nbsp;Buscar colab.</button>
						</div>
					</div>
					<div class="fila">
						<div class="columna columna-5">
							<p>Datos del proyecto :</p>
						</div>
						<div class="columna columna-5">
							@inject('projectTypes','App\Services\ProjectTypes')
							<select name="project_type_id" id="project_type_id" required>
								<option selected disabled hidden value="">Tipo*</option>
								@foreach ($projectTypes->get() as $index => $projectType)
								<option value="{{ $index }}" {{ old('project_type_id',$project->project_type_id) == $index ? 'selected' : '' }}>
									{{ $projectType }}
								</option>
								@endforeach
							</select>
						</div>
						<div class="columna columna-5f">
							<input type="text" name="name" id="name" value="{{ old('name',$project->name) }}" maxlength="200" onkeypress="return checkText(event)" placeholder=" -- Nombre del proyecto* -- " required>
						</div>
					</div>
					<div class="fila" id="div_not_prop">
						<div class="columna columna-1">
							<p id="msg_not_prop" class="lbl-msg"><b>Nota: Propuesta sin visita previa</b></p>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</form>
@include('projects/submit')
@endsection