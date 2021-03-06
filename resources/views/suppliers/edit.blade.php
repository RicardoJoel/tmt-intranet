@extends('layouts.app')
@section('content')
<div class="fila">
	<div class="columna columna-1">
		<div class="title2">
			<h6>Entidades > Proveedores > Editar ></h6>
		</div>
	</div>
</div>
<form method="POST" action="{{ route('suppliers.update',$supplier->id) }}" role="form" id="frm-supplier">
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
							<input type="text" value="{{ $supplier->code }}" disabled>
						</div>
						<div class="columna columna-2">
							<p>Nombre o razón social*</p>
							<input type="text" name="name" id="name" maxlength="100" value="{{ old('name',$supplier->name) }}" onkeypress="return checkText(event)" required>
						</div>
						<div class="columna columna-3">
							<p>Servicio*</p>
							@inject('profiles','App\Services\Profiles')
							<select name="profile_id" id="profile_id" required>
								<option selected disabled hidden value="">Selecciona un servicio</option>
								@foreach ($profiles->get('S') as $index => $profile)
								<option value="{{ $index }}" {{ old('profile_id',$supplier->profile_id) == $index ? 'selected' : '' }}>
									{{ $profile }}
								</option>
								@endforeach
							</select>
						</div>
					</div>
					<div class="fila">
						<div class="columna columna-3">
							<p>Tipo de documento*</p>
							@inject('documentTypes','App\Services\DocumentTypes')
							<select name="document_type_id" id="document_type_id" required>
								<option selected disabled hidden value="">Selecciona un tipo de documento</option>
								@foreach ($documentTypes->get() as $index => $documentType)
								<option value="{{ $index }}" {{ old('document_type_id',$supplier->document_type_id) == $index ? 'selected' : '' }}>
									{{ $documentType['name'] }}
								</option>
								@endforeach
							</select>
						</div>
						<div class="columna columna-6">
							<p>N° Documento*</p>
							<input type="hidden" name="doc_pattern" id="doc_pattern" value="{{ old('doc_pattern') }}" required>
							<input type="text" name="document" id="document" value="{{ old('document',$supplier->document) }}" onkeyup="return mayusculas(this)" disabled required>
						</div>
						<div class="columna columna-2">
							<p>Especifica otro servicio</p>
							<input type="text" name="other" id="other" maxlength="100" value="{{ old('other',$supplier->other) }}" onkeypress="return checkText(event)" disabled>
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
				<a onclick="showForm('ctt')">
					<h6 id="ctt_subt" class="title3">Datos de contacto</h6>
					<p id="icn-ctt" class="icn-sct"><i class="fa fa-minus fa-icon"></i></p>
				</a>
				<div id="div-ctt">
					<div class="fila">
						<div class="columna columna-2">
							<p>Dirección de facturación*</p>
							<input type="text" name="address" id="address" maxlength="100" value="{{ old('address',$supplier->address) }}" onkeypress="return checkText(event)" required>
						</div>
						<div class="columna columna-2">
							<p>Departamento / Provincia / Distrito de facturación*</p>
							@inject('ubigeos','App\Services\Ubigeos')
							<select name="ubigeo_id" id="ubigeo_id" required>
								<option selected disabled hidden value="">Selecciona un departamento / provincia / distrito</option>
								@foreach ($ubigeos->get() as $index => $ubigeo)
								<option value="{{ $index }}" {{ old('ubigeo_id',$supplier->ubigeo_id) == $index ? 'selected' : '' }}>
									{{ $ubigeo }}
								</option>
								@endforeach
							</select>
						</div>
					</div>
					<div class="fila">
						<div class="columna columna-6">
							<p>Código país*</p>
							@inject('countries','App\Services\Countries')
							<select name="country_id" id="country_id" required>
								<option selected disabled hidden value="">Selecciona un departamento / provincia / distrito</option>
								@foreach ($countries->get() as $index => $country)
								<option value="{{ $index }}" {{ old('country_id',$supplier->country_id) == $index ? 'selected' : '' }}>
									{{ $country }}
								</option>
								@endforeach
							</select>
						</div>
						<div class="columna columna-6">
							<p>Celular*</p>
							<input type="tel" name="mobile" id="mobile" maxlength="11" value="{{ old('mobile',$supplier->mobile) }}" onkeypress="return checkNumber(event)" pattern="[0-9]{3} [0-9]{3} [0-9]{3}" required>
						</div>
						<div class="columna columna-6">
							<p>Teléfono fijo</p>
							<input type="tel" name="phone" id="phone" maxlength="11" value="{{ old('phone',$supplier->phone) }}" onkeypress="return checkNumber(event)" pattern="[0-9]{2} [0-9]{3} [0-9]{4}">
						</div>
						<div class="columna columna-6">
							<p>Anexo</p>
							<input type="tel" name="annex" id="annex" maxlength="6" value="{{ old('annex',$supplier->annex) }}" onkeypress="return checkNumber(event)" pattern="[0-9]{4,6}">
						</div>
						<div class="columna columna-3">
							<p>Correo electrónico</p>
							<input type="email" name="email" id="email" maxlength="50" value="{{ old('email',$supplier->email) }}" onkeypress="return checkEmail(event)" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,4}$">
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
				<a onclick="showForm('pln')">
					<h6 id="pln_subt" class="title3">Datos de facturación</h6>
					<p id="icn-pln" class="icn-sct"><i class="fa fa-minus fa-icon"></i></p>
				</a>
				<div id="div-pln">
					<div class="fila">
						<div class="columna columna-3">
							<p>Entidad bancaria</p>
							@inject('banks','App\Services\Banks')
							<select name="bank_id" id="bank_id">
								<option selected disabled hidden value="">Selecciona una entidad bancaria</option>
								@foreach ($banks->get() as $index => $bank)
								<option value="{{ $index }}" {{ old('bank_id',$supplier->bank_id) == $index ? 'selected' : '' }}>
									{{ $bank }}
								</option>
								@endforeach
							</select>
						</div>
						<div class="columna columna-3">
							<p>N° Cuenta</p>
							<input type="text" name="account" id="account" maxlength="20" value="{{ old('account',$supplier->account) }}" onkeypress="return checkNumber(event)" disabled>
						</div>
						<div class="columna columna-3">
							<p>Código de Cuenta Interbancario (CCI)</p>
							<input type="text" name="cci" id="cci" maxlength="23" value="{{ old('cci',$supplier->cci) }}" onkeypress="return checkNumber(event)" disabled>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</form>
@include('suppliers/submit')
@endsection