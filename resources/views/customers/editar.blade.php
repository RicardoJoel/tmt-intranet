@extends('layouts.app')
@section('content')
<div class="fila">
	<div class="columna columna-1">
		<div class="title2">
			<h6>Entidades > Clientes > Editar ></h6>
		</div>
	</div>
</div>
<form method="POST" action="{{ route('customers.update',$customer->id) }}" role="form" id="frm-customer">
	@csrf
	<input type="hidden" name="_method" id="_method" value="PATCH">
	<div class="fila">
		<div class="columna columna-1">
			<div class="form-section">
				<a onclick="showForm('gen')">
					<h6 id="gen_subt" class="title3">Datos generales</h6>
					<p id="icn-gen" class="icn-sct"><i class="fa fa-minus fa-icon"></i></p>
				</a>
				<div id="div-gen">
					<div class="fila">
						<div class="columna columna-6">
							<p>Código</p>
							<input type="text" value="{{ $customer->code }}" disabled>
						</div>
						<div class="columna columna-2">
							<p>Razón social*</p>
							<input type="text" name="name" id="name" maxlength="100" value="{{ old('name',$customer->name) }}" onkeypress="return checkText(event)" required>
						</div>
						<div class="columna columna-3">
							<p>Nombre comercial*</p>
							<input type="text" name="alias" id="alias" maxlength="100" value="{{ old('alias',$customer->alias) }}" onkeypress="return checkText(event)" required>
						</div>
					</div>
					<div class="fila">
						<div class="columna columna-3">
							<p>Rubro de negocio*</p>
							@inject('bussiness','App\Services\Bussiness')
							<select name="business_id" id="business_id" required>
								<option selected disabled hidden value="">Selecciona un rubro</option>
								@foreach ($bussiness->get() as $index => $business)
								<option value="{{ $index }}" {{ old('business_id',$customer->business_id) == $index ? 'selected' : '' }}>
									{{ $business }}
								</option>
								@endforeach
							</select>
						</div>
						<div class="columna columna-6">
							<p>R. U. C.*</p>
							<input type="text" name="ruc" id="ruc" maxlength="11" value="{{ old('ruc',$customer->ruc) }}" onkeypress="return checkNumber(event)" required>
						</div>
						<div class="columna columna-6">
							<p>Referencia*</p>
							<input type="text" name="ref" id="ref" maxlength="3" value="{{ old('ref',$customer->ref) }}" onkeypress="return checkAlpha(event)" onkeyup="return mayusculas(this)" required>
						</div>
						<div class="columna columna-3">
							<p>Representante legal</p>
							<input type="text" name="representative" id="representative" maxlength="100" value="{{ old('representative',$customer->representative) }}" onkeypress="return checkName(event)">
						</div>
					</div>
					<div class="fila">
						<div class="columna columna-2">
							<p>Dirección de facturación*</p>
							<input type="text" name="address" id="address" maxlength="100" value="{{ old('address',$customer->address) }}" onkeypress="return checkText(event)" required>
						</div>
						<div class="columna columna-2">
							<p>Departamento / Provincia / Distrito de facturación*</p>
							@inject('ubigeos','App\Services\Ubigeos')
							<select name="ubigeo_id" id="ubigeo_id" required>
								<option selected disabled hidden value="">Selecciona un departamento / provincia / distrito</option>
								@foreach ($ubigeos->get() as $index => $ubigeo)
								<option value="{{ $index }}" {{ old('ubigeo_id',$customer->ubigeo_id) == $index ? 'selected' : '' }}>
									{{ $ubigeo }}
								</option>
								@endforeach
							</select>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</form>
@include('customers/contact')
@include('customers/submit')
@endsection