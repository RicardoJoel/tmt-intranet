<div class="fila">
	<div class="space"></div>
	<div class="columna columna-1">
		<center>
		<button type="submit" class="btn-effie" onclick="document.getElementById('frm-customer').submit();">
			<i class="fa fa-save"></i>&nbsp;{{ isset($customer) ? 'Guardar' : 'Registrar' }}
		</button>
		<a href="{{ route('customers.index') }}" class="btn-effie-inv">
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
			<li>El tamaño máximo de la razón social, el representante legal y la dirección de facturación es cien (100) caracteres.</li>
			<li>El tamaño máximo del nombre comercial es cincuenta (50) caracteres.</li>
			<li>El R. U. C. debe estar compuesto por once (11) caracteres.</li>
            <li>La referencia debe estar compuesto únicamente por tres (3) letras.</li>
			<li>El tamaño máximo del nombre, apellidos y correo electrónico del contacto es cincuenta (50) caracteres.</li>
			<li>El tamaño máximo del cargo es cincuenta (100) caracteres.</li>
		</ul></p>
	</div>
</div>
@section('script')
<script src="{{ asset('js/jquery.inputmask.bundle.js') }}"></script>
<script src="{{ asset('js/customers/contacts.js') }}"></script>
<script src="{{ asset('js/customers/form.js') }}"></script>
@endsection