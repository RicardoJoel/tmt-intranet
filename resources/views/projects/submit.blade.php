<div class="fila">
	<div class="space"></div>
	<div class="columna columna-1">
		<div class="form-section">
			<a onclick="showForm('cmp')">
				<h6 id="cmp_subt" class="title3">Cronograma de actividades</h6>
				<p id="icn-cmp" class="icn-sct"><i class="fa fa-minus fa-icon"></i></p>
			</a>
			<div id="div-cmp" style="margin-bottom:10px">
				<p>Para agregar una tarea, debes hacer doble clic sobre una celda vacía de la columna 'Nombre de la tarea' dentro del cronograma. Si haces doble clic sobre una tarea previamente ingresada, podrás visualizar su detalle y modificarla.</p>
			</div>
		</div>
	</div>
</div>
<div class="fila">
	<div class="space"></div>
	<div class="columna columna-1 gantt">
		@include('projects/gantt')
	</div>
</div>
<div class="fila">
	<div class="space"></div>
	<div class="columna columna-1">
		<center>
		<button type="submit" class="btn-effie" id="btn-submit">
            <i class="fa fa-save"></i>&nbsp;{{ isset($project) ? 'Guardar' : 'Registrar' }}
        </button>
		<a href="{{ route('projects.index') }}" class="btn-effie-inv">
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
@include('searches/proposals')
@include('projects/notes/clear')
@include('projects/notes/success')

@section('script')
<script src="{{ asset('js/jquery.inputmask.bundle.js') }}"></script>
<script src="{{ asset('js/searches/clients2.js') }}"></script>
<script src="{{ asset('js/searches/proposals4.js') }}"></script>
<script src="{{ asset('js/searches/employees2.js') }}"></script>
<script src="{{ asset('js/gencode2.js') }}"></script>

<link rel="stylesheet" href="{{ asset('jQueryGantt-master/platform8.css') }}" type="text/css">
<link rel="stylesheet" href="{{ asset('jQueryGantt-master/libs/jquery/dateField/jquery.dateField.css') }}" type="text/css">

<link rel="stylesheet" href="{{ asset('jQueryGantt-master/gantt6.css') }}" type="text/css">
<link rel="stylesheet" href="{{ asset('jQueryGantt-master/ganttPrint.css') }}" type="text/css" media="print">
<link rel="stylesheet" href="{{ asset('jQueryGantt-master/libs/jquery/valueSlider/mb.slider.css') }}" type="text/css" media="print">

<script src="{{ asset('jQueryGantt-master/libs/jquery/jquery.livequery.1.1.1.min.js') }}"></script>
<script src="{{ asset('jQueryGantt-master/libs/jquery/jquery.timers.js') }}"></script>

<script src="{{ asset('jQueryGantt-master/libs/utilities.js') }}"></script>
<script src="{{ asset('jQueryGantt-master/libs/forms.js') }}"></script>
<script src="{{ asset('jQueryGantt-master/libs/date2.js') }}"></script>
<script src="{{ asset('jQueryGantt-master/libs/dialogs.js') }}"></script>
<script src="{{ asset('jQueryGantt-master/libs/layout.js') }}"></script>
<script src="{{ asset('jQueryGantt-master/libs/i18nJs2.js') }}"></script>
<script src="{{ asset('jQueryGantt-master/libs/jquery/dateField/jquery.dateField.js') }}"></script>
<script src="{{ asset('jQueryGantt-master/libs/jquery/JST/jquery.JST.js') }}"></script>
<script src="{{ asset('jQueryGantt-master/libs/jquery/valueSlider/jquery.mb.slider.js') }}"></script>

<script src="{{ asset('jQueryGantt-master/libs/jquery/svg/jquery.svg.min.js') }}"></script>
<script src="{{ asset('jQueryGantt-master/libs/jquery/svg/jquery.svgdom.1.8.js') }}"></script>

<script src="{{ asset('jQueryGantt-master/ganttUtilities.js') }}"></script>
<script src="{{ asset('jQueryGantt-master/ganttTask.js') }}"></script>
<script src="{{ asset('jQueryGantt-master/ganttDrawerSVG.js') }}"></script>
<script src="{{ asset('jQueryGantt-master/ganttZoom.js') }}"></script>
<script src="{{ asset('jQueryGantt-master/ganttGridEditor2.js') }}"></script>
<script src="{{ asset('jQueryGantt-master/ganttMaster.js') }}"></script>

<script src="{{ asset('js/projects/form2.js') }}"></script>
<script src="{{ asset('js/projects/gantt22.js') }}"></script>
<script src="{{ asset('js/projects/other.js') }}"></script>
<script>
function getDemoProject(){
	var tasks = [];
    $(@json($tasks)).each(function() {
		tasks.push({
			'id': this.id, 
			'code': this.code, 
			'name': this.name, 
			'start': moment(this.start_at),
			'end': moment(this.end_at), 
			'startIsMilestone': this.start_ms,
			'endIsMilestone': this.end_ms, 
			'status': this.status, 
			'progress': this.progress, 
			'description': this.description, 
			'relevance': this.relevance,
			'duration': this.duration,
			'level': this.level,
			'progressByWorklog': this.progressByWorklog, 
			'type': this.type,
			'typeId': this.typeId,
			'depends': this.depends,
			'canWrite': this.canWrite,
			'collapsed': this.collapsed,
			'assigs': this.assigs,
			'hasChild': this.hasChild
		});
	});
	var resources = [];
    $(@json($resources)).each(function(index) {
		resources.push({
			'id': 'tmp_' + (index + 1), 
			'code': this.id,
			'name': this.name
		});
	});
	var roles = [];
    $(@json($roles)).each(function() {
		roles.push({
			'id': this.id,
			'name': this.name
		});
	});
    return {
		"tasks": tasks, "resources": resources, "roles": roles, 
		"selectedRow": 0, "deletedTaskIds": [], "canWrite": true, 
		"canDelete": true, "canWriteOnParent": true, "canAdd": true
	};
}
</script>
@endsection