@extends('layouts.app')
@section('content')
<div class="fila">
	<div class="columna columna-1">
		<div class="title2">
			<h6>Entidades > Independientes > Editar ></h6>
		</div>
	</div>
</div>
<form method="POST" action="{{ route('freelancers.update',$freelancer->id) }}" role="form" id="frm-freelancer">
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
							<input type="text" value="{{ $freelancer->code }}" disabled>
						</div>
						<div class="columna columna-4">
							<p>Nombres*</p>
							<input type="text" name="name" id="name" maxlength="50" value="{{ old('name',$freelancer->name) }}" onkeypress="return checkName(event)" required>
						</div>
						<div class="columna columna-4">
							<p>Apellidos*</p>
							<input type="text" name="lastname" id="lastname" maxlength="50" value="{{ old('lastname',$freelancer->lastname) }}" onkeypress="return checkName(event)" required>
						</div>
						<div class="columna columna-3">
							<p>Perfil*</p>
							@inject('profiles','App\Services\Profiles')
							<select name="profile_id" id="profile_id" required>
								<option selected disabled hidden value="">Selecciona un perfil</option>
								@foreach ($profiles->get('I') as $index => $profile)
								<option value="{{ $index }}" {{ old('profile_id',$freelancer->profile_id) == $index ? 'selected' : '' }}>
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
								<option value="{{ $index }}" {{ old('document_type_id',$freelancer->document_type_id) == $index ? 'selected' : '' }}>
									{{ $documentType['name'] }}
								</option>
								@endforeach
							</select>
						</div>
						<div class="columna columna-6">
							<p>N° Documento*</p>
							<input type="hidden" name="doc_pattern" id="doc_pattern" value="{{ old('doc_pattern') }}" required>
							<input type="text" name="document" id="document" value="{{ old('document',$freelancer->document) }}" onkeyup="return mayusculas(this)" disabled required>
						</div>
						<div class="columna columna-6">
							<p>F. Nacimiento*</p>
							<input type="date" name="birthdate" id="birthdate" max="{{ Carbon\Carbon::today()->subYear(18)->toDateString() }}" value="{{ old('birthdate',$freelancer->birthdate) }}" required>
						</div>
						<div class="columna columna-3">
							<p>Especifica otro perfil</p>
							<input type="text" name="other" id="other" maxlength="100" value="{{ old('other',$freelancer->other) }}" onkeypress="return checkText(event)" disabled>
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
							<p>Dirección de domicilio*</p>
							<input type="text" name="address" id="address" maxlength="100" value="{{ old('address',$freelancer->address) }}" onkeypress="return checkText(event)" required>
						</div>
						<div class="columna columna-2">
							<p>Departamento / Provincia / Distrito de domicilio*</p>
							@inject('ubigeos','App\Services\Ubigeos')
							<select name="ubigeo_id" id="ubigeo_id" required>
								<option selected disabled hidden value="">Selecciona un distrito</option>
								@foreach ($ubigeos->get() as $index => $ubigeo)
								<option value="{{ $index }}" {{ old('ubigeo_id',$freelancer->ubigeo_id) == $index ? 'selected' : '' }}>
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
								<option selected disabled hidden value="">Selecciona un país</option>
								@foreach ($countries->get() as $index => $country)
								<option value="{{ $index }}" {{ old('country_id',$freelancer->country_id) == $index ? 'selected' : '' }}>
									{{ $country }}
								</option>
								@endforeach
							</select>
						</div>
						<div class="columna columna-6">
							<p>Celular*</p>
							<input type="tel" name="mobile" id="mobile" maxlength="11" value="{{ old('mobile',$freelancer->mobile) }}" onkeypress="return checkNumber(event)" pattern="[0-9]{3} [0-9]{3} [0-9]{3}" required>
						</div>
						<div class="columna columna-6">
							<p>Teléfono fijo</p>
							<input type="tel" name="phone" id="phone" maxlength="11" value="{{ old('phone',$freelancer->phone) }}" onkeypress="return checkNumber(event)" pattern="[0-9]{2} [0-9]{3} [0-9]{4}">
						</div>
						<div class="columna columna-6">
							<p>Anexo</p>
							<input type="tel" name="annex" id="annex" maxlength="6" value="{{ old('annex',$freelancer->annex) }}" onkeypress="return checkNumber(event)" pattern="[0-9]{4,6}">
						</div>
						<div class="columna columna-3">
							<p>Correo electrónico</p>
							<input type="email" name="email" id="email" maxlength="50" value="{{ old('email',$freelancer->email) }}" onkeypress="return checkEmail(event)" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,4}$">
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
							<p>Entidad bancaria*</p>
							@inject('banks','App\Services\Banks')
							<select name="bank_id" id="bank_id" required>
								<option selected disabled hidden value="">Selecciona una entidad bancaria</option>
								@foreach ($banks->get() as $index => $bank)
								<option value="{{ $index }}" {{ old('bank_id',$freelancer->bank_id) == $index ? 'selected' : '' }}>
									{{ $bank }}
								</option>
								@endforeach
							</select>
						</div>
						<div class="columna columna-3">
							<p>N° Cuenta*</p>
							<input type="text" name="account" id="account" maxlength="20" value="{{ old('account',$freelancer->account) }}" onkeypress="return checkNumber(event)" disabled required>
						</div>
						<div class="columna columna-3">
							<p>Código de Cuenta Interbancario (CCI)</p>
							<input type="text" name="cci" id="cci" maxlength="23" value="{{ old('cci',$freelancer->cci) }}" onkeypress="return checkNumber(event)" disabled>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</form>
@include('freelancers/submit')
@endsection