<div class="fila">
	<div class="space"></div>
	<div class="columna columna-1">
		<center>
		<button type="submit" class="btn-effie" onclick="document.getElementById('frm-freelancer').submit();">
			<i class="fa fa-save"></i>&nbsp;{{ isset($freelancer) ? 'Guardar' : 'Registrar' }}
		</button>
		<a href="{{ route('freelancers.index') }}" class="btn-effie-inv">
			<i class="fa fa-reply"></i>&nbsp;Regresar
		</a>
		</center>
	</div>
</div>
<div class="fila">
	<div class="space"></div>
	<div class="columna columna-1">
		<p><i class="fa fa-info-circle fa-icon" aria-hidden="true"></i>&nbsp;<b>Importante</b>
		<ul>
			<li>(*) Campos obligatorios</li>
			<li>El tamaño máximo del nombre, apellidos y correo electrónico es cincuenta (50) caracteres.</li>
			<li>El tamaño máximo de la dirección de domicilio es cien (100) caracteres.</li>
		</ul></p>
	</div>
</div>
@section('script')
<script src="{{ asset('js/jquery.inputmask.bundle.js') }}"></script>
<script src="{{ asset('js/suppliers/form.js') }}"></script>
@endsection