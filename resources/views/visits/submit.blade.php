<div class="fila">
    <div class="space"></div>
    <div class="columna columna-1">
        <center>
        <button type="submit" class="btn-effie" onclick="document.getElementById('frm-visit').submit();">
			<i class="fa fa-save"></i>&nbsp;{{ isset($visit) ? 'Guardar' : 'Registrar' }}
		</button>
		<a href="{{ route('visits.index') }}" class="btn-effie-inv">
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
            <li>Para buscar un cliente debes ingresar el R. U. C. y luego presionar el botón "Buscar cliente".</li>
            <li>Para buscar un responsable debes ingresar el N° Documento y luego presionar el botón "Buscar respon.".</li>
		</ul></p>
	</div>
</div>

@include('searches/customers')
@include('searches/employees')

@section('script')
<script src="{{ asset('js/jquery.inputmask.bundle.js') }}"></script>
<script src="{{ asset('js/searches/customers.js') }}"></script>
<script src="{{ asset('js/searches/employees2.js') }}"></script>
<script src="{{ asset('js/gencode2.js') }}"></script>
@endsection