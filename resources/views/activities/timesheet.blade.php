@extends('layouts.app')
@section('content')
<div class="fila">
    <div class="columna columna-1">
        <div class="title2">
            <h6>Utilitarios > Mis actividades ></h6>
        </div>
    </div>
</div>
<div class="fila">
    <div class="columna columna-1">
        <div class="form-section">
            <a onclick="showForm('gen')">
                <h6 id="gen_subt" class="title3">Indicaciones</h6>
                <p id="icn-gen" class="icn-sct"><i class="fa fa-minus fa-icon"></i></p>
            </a>
            <div id="div-gen" style="margin-bottom:10px">
                <p>Para agregar una actividad, presiona una celda vacía del calendario. Si seleccionas una actividad previamente ingresada, podrás visualizar su detalle, modificarla o eliminarla. Puedes agregar, editar y eliminar actividades dentro de los últimos {{ $dates }} días calendario.</p>
            </div>
        </div>
    </div>
</div>
<div class="fila">
    <div class="space"></div>
    <div class="columna columna-1">
        <div id="calendar"></div>
    </div>
</div>
<div class="fila">
	<div class="space2"></div>
	<div class="columna columna-1">
		<center>
        <a href="{{ route('home') }}" class="btn-effie-inv"><i class="fa fa-home"></i>&nbsp;Ir al inicio</a>
		</center>
	</div>
</div>
@endsection

@include('activities/create')
@include('activities/edit')
@include('popups/message')
@include('popups/confirm')

@section('script')
<link rel='stylesheet' href="{{ asset('fullcalendar/css/core.min.css') }}">
<link rel='stylesheet' href="{{ asset('fullcalendar/css/daygrid.min.css') }}">
<link rel='stylesheet' href="{{ asset('fullcalendar/css/timegrid.min.css') }}">
<script src="{{ asset('fullcalendar/js/core.min.js') }}"></script>
<script src="{{ asset('fullcalendar/js/locales-all.min.js') }}"></script>
<script src="{{ asset('fullcalendar/js/interaction.min.js') }}"></script>
<script src="{{ asset('fullcalendar/js/daygrid.min.js') }}"></script>
<script src="{{ asset('fullcalendar/js/timegrid.min.js') }}"></script>
<script>
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')     
    }
});

let task_id = null;
let tasks = [];

$(function() {
    moment.locale('es');
    var color = '#A5C862';
    var dates = parseInt(@json($dates));
    var begin = new Date(moment(new Date()).subtract(dates+1,'days'));
    begin.setHours(23,59,59);
    var ended = new Date(moment(new Date()).add(1,'days'));
    ended.setHours(0,0,0);
    var project = null;
    var message = 'Solo puedes action actividades dentro de los últimos '+dates+' días calendario y la fecha actual.';
    var separator = '\n----\n';
    var calendar = new FullCalendar.Calendar(document.getElementById('calendar'), {
        plugins: ['interaction','timeGrid','dayGrid'],
        header: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay'
        },
        height: window.matchMedia("(max-width: 767px)").matches ? 540 : 570,
        locale: 'es',
        navLinks: true,
        editable: true,
        eventLimit: true,
        selectable: true,
        allDaySlot: false,
        buttonIcons: true,
        selectMirror: true,
        nowIndicator: true,
        eventOverlap: false,
        scrollTime: '09:00:00',
        defaultView: 'timeGridWeek',
        businessHours: {
            daysOfWeek: [1, 2, 3, 4, 5],
            startTime: '09:00',
            endTime: '19:00',
        },
        select: function(info) {
            if (begin < info.start && info.end < ended) {
                $('#frm-create #date').text(moment(info.start).format('dddd, DD [de] MMMM'));
                $('#frm-create #aux-ini').val(moment(info.start).format());
                $('#frm-create #aux-fin').val(moment(info.end).format());
                $('#frm-create #start_at').val(moment(info.start).format('LT').padStart(5, 0));
                $('#frm-create #end_at').val(moment(info.end).format('LT').padStart(5, 0));
                $('#frm-create #project_id').val('');   
                $('#frm-create #description').attr({'placeholder':'Descripción de la actividad','disabled':false}).val('');
                $('#frm-create #comment').val('');
                $('#frm-create #color').val(color);
                $('#frm-create #finished').prop('checked',false);
                $('#frm-create #aux-cod').val('');
                $('#mdl-create #div-span-act').css('display','none');
                $('#mdl-create').modal('show');
                project = null;
                task_id = null;
                tasks = [];
            }
            else {
                $('#txt-detalle').html(message.replace('action','agregar'));
                $('#mdl-message').modal('show');
            }
        },
        eventClick: function(info) {
            $('#frm-edit #date').text(moment(info.event.start).format('dddd, DD [de] MMMM'));
            $('#frm-edit #aux-ini').val(moment(info.event.start).format());
            $('#frm-edit #aux-fin').val(moment(info.event.end).format());
            $('#frm-edit #start_at').val(moment(info.event.start).format('LT').padStart(5, 0));
            $('#frm-edit #end_at').val(moment(info.event.end).format('LT').padStart(5, 0));
            $('#frm-edit #project_id').val(info.event.extendedProps.project.id);
            $('#frm-edit #description').val(info.event.extendedProps.description);
            $('#frm-edit #comment').val(info.event.extendedProps.comment);
            $('#frm-edit #color').val(info.event.backgroundColor);
            $('#frm-edit #finished').prop('checked',info.event.extendedProps.finished);
            $('#frm-edit #aux-cod').val(info.event.id);
            project = info.event.extendedProps.project;
            task_id = info.event.extendedProps.task_id;
            tasks = info.event.extendedProps.tasks.slice();
            //Llenado de tareas
            $.each(tasks, function(index, value) {
                var level = parseInt(index) + 1;
                //Creo el nuevo select
                $('#frm-edit #project_id').parent().parent().append(
                    '<div class="fila sltdiv"><div class="columna columna-10"><i class="fa fa-puzzle-piece fa-2x fa-icon"></i></div>' +
                    '<div class="columna columna-10c"><select id="seltsk'+level+'" name="'+level+'" onchange="createSelect(this)" required>' +
                    '<option selected disabled hidden value="">Selecciona una actividad</option></select></div></div>');
                //Establezco opciones
                $(value[level].options).each(function() {
                    var option = $(document.createElement('option'));
                    option.val(this.id);
                    option.text(this.name);
                    $('#frm-edit #seltsk'+level).append(option);
                });
                $('#seltsk'+level).val(value[level].selected);
            });
            //Termino de completar el formulario
            if (begin < info.event.start && info.event.end < ended) {
                $('#frm-edit #start_at').attr('disabled', false);
                $('#frm-edit #end_at').attr('disabled', false);
                $('#frm-edit #project_id').attr('disabled', false);
                //Habilito / deshabilito campo descripción
                if (isProject(project.code)) {
                    $('#frm-edit #description').attr({'placeholder':'Descripción de la actividad','disabled':false});
                    //$('#frm-edit #msg_status').text('Estado actual del proyecto: ' + project.status);
                    //$('#frm-edit #div_status').css('display','block');
                }
                else {
                    $('#frm-edit #description').attr({'placeholder':'No necesita descripción','disabled':true});
                    //$('#frm-edit #div_status').css('display','none');
                }
                $('#frm-edit #comment').attr('disabled', false)
                $('#frm-edit #color').attr('disabled', false);
                $('#frm-edit #finished').attr('disabled', false);
                $('#lbl-titulo').text('Editar actividad');
                $('#frm-edit #btn-delete').show();
                $('#frm-edit #btn-save').show();
            }
            else {
                $('#frm-edit input').attr('disabled', true);
                $('#frm-edit select').attr('disabled', true);
                $('#frm-edit textarea').attr('disabled', true);
                $('#lbl-titulo').text('Detalle de la actividad');
                $('#frm-edit #btn-delete').hide();
                $('#frm-edit #btn-save').hide();
            }
            $('#mdl-edit #div-span-act').css('display','none');
            $('#mdl-edit').modal('show');
        },
        eventResize: function(info) {
            if (begin < info.event.start && info.event.end < ended) {
                $('#frm-edit #aux-ini').val(moment(info.event.start).format());
                $('#frm-edit #aux-fin').val(moment(info.event.end).format());
                $('#frm-edit #start_at').val(moment(info.event.start).format('LT').padStart(5, 0));
                $('#frm-edit #end_at').val(moment(info.event.end).format('LT').padStart(5, 0));
                $('#frm-edit #project_id').val(info.event.extendedProps.project.id);
                $('#frm-edit #description').val(info.event.extendedProps.description);
                $('#frm-edit #comment').val(info.event.extendedProps.comment);
                $('#frm-edit #color').val(info.event.backgroundColor);
                $('#frm-edit #finished').prop('checked',info.event.extendedProps.finished);
                $('#frm-edit #aux-cod').val(info.event.id);
                project = info.event.extendedProps.project;
                task_id = info.event.extendedProps.task_id;
                tasks = info.event.extendedProps.tasks.slice();
                $('#frm-edit').submit();
            }
            else {
                info.revert();
                $('#txt-detalle').html(message.replace('action','redimensionar'));
                $('#mdl-message').modal('show');
            }
        },
        eventDrop: function(info) { // event drag and drop
            if (begin < info.oldEvent.start && info.oldEvent.end < ended && begin < info.event.start && info.event.end < ended) {
                $('#frm-edit #aux-ini').val(moment(info.event.start).format());
                $('#frm-edit #aux-fin').val(moment(info.event.end).format());
                $('#frm-edit #start_at').val(moment(info.event.start).format('LT').padStart(5, 0));
                $('#frm-edit #end_at').val(moment(info.event.end).format('LT').padStart(5, 0));
                $('#frm-edit #project_id').val(info.event.extendedProps.project.id);
                $('#frm-edit #description').val(info.event.extendedProps.description);
                $('#frm-edit #comment').val(info.event.extendedProps.comment);
                $('#frm-edit #color').val(info.event.backgroundColor);
                $('#frm-edit #finished').prop('checked',info.event.extendedProps.finished);
                $('#frm-edit #aux-cod').val(info.event.id);
                project = info.event.extendedProps.project;
                task_id = info.event.extendedProps.task_id;
                tasks = info.event.extendedProps.tasks.slice();
                $('#frm-edit').submit();
            }
            else {
                info.revert();
                $('#txt-detalle').html(message.replace('action','mover'));
                $('#mdl-message').modal('show');
            }
        }
    });
    
    /* 
     * Load activities to calendar
     */
    $('body').loadingModal({
        text:'Un momento, por favor...',
        animation:'wanderingCubes'
    });
    $(@json($activities)).each(function() {
        //carga de tareas asociadas al evento
        var aux = [];
        $.each(this.tasks, function(index, value) {
            var options = [];
            $.each(value.options, function(index, value) {
                //alert(index+' '+JSON.stringify(value));
                options.push({id: value.id, name: value.name});
            });
            aux.push({[index]: {options: options, selected: value.selected}});
        });
        //agrego el evento al calendar 
        calendar.addEvent({
            id: this.id,
            title: this.project.name + (isProject(this.project.code) ? separator + this.description : ''),
            start: this.start_at,
            end: this.end_at,
            description: this.description,
            comment: this.comment,
            finished: this.finished,
            backgroundColor: this.color,
            project: this.project,
            task_id: this.task_id,
            tasks: aux,
        });
    });
    calendar.render();
    $('body').loadingModal('destroy');

    /* 
     * Load selected project
     */
    $('#frm-create #project_id').change(function() {
        var e = $(this);
        $.ajax({
            type: 'get',
            url: 'getProject/' + e.val(),
            success: function(data) {
                //Elimino selects innecesarios
                $('#frm-create .sltdiv').remove();
                //Si tiene tareas asociadas
                if (data.tasks.length) {
                    //Creo el nuevo select
                    e.parent().parent().append(
                        '<div class="fila sltdiv"><div class="columna columna-10"><i class="fa fa-puzzle-piece fa-2x fa-icon"></i></div>' +
                        '<div class="columna columna-10c"><select id="seltsk1" name="1" onchange="createSelect(this)" required>' +
                        '<option selected disabled hidden value="">Selecciona una actividad</option></select></div></div>');
                    //Establezco opciones
                    $(data.tasks).each(function() {
                        var option = $(document.createElement('option'));
                        option.val(this.id);
                        option.text(this.name);
                        $('#frm-create #seltsk1').append(option);
                    });
                }
                //Reservo proyecto
                project = data.project;
                //Aún no hay tarea seleccionada
                task_id = null;
                //Elimino tareas innecesarias
                tasks.splice(0, Infinity);
                //Habilito / deshabilito campo descripción
                if (isProject(project.code)) {
                    $('#frm-create #description').attr({'placeholder':'Descripción de la actividad','disabled':false});
                    //$('#frm-create #msg_status').text('Estado actual del proyecto: ' + project.status);
                    //$('#frm-create #div_status').css('display','block');
                }
                else {
                    $('#frm-create #description').attr({'placeholder':'No necesita descripción','disabled':true}).val('');
                    //$('#frm-create #div_status').css('display','none');
                }
            },
            error: function(msg) {
                $('#txt-detalle').html(msg.responseJSON['message']);
                $('#mdl-create').modal('hide');
                $('#mdl-message').modal('show');
            }
        });
    });

    /* 
     * Load selected project
     */
    $('#frm-edit #project_id').change(function() {
        var e = $(this);
        $.ajax({
            type: 'get',
            url: 'getProject/' + e.val(),
            success: function(data) {
                //Elimino selects innecesarios
                $('#frm-edit .sltdiv').remove();
                //Si tiene tareas asociadas
                if (data.tasks.length) {
                    //Creo el nuevo select
                    e.parent().parent().append(
                        '<div class="fila sltdiv"><div class="columna columna-10"><i class="fa fa-puzzle-piece fa-2x fa-icon"></i></div>' +
                        '<div class="columna columna-10c"><select id="seltsk1" name="1" onchange="createSelect(this)" required>' +
                        '<option selected disabled hidden value="">Selecciona una actividad</option></select></div></div>');
                    //Establezco opciones
                    $(data.tasks).each(function() {
                        var option = $(document.createElement('option'));
                        option.val(this.id);
                        option.text(this.name);
                        $('#frm-edit #seltsk1').append(option);
                    });
                }
                //Reservo proyecto
                project = data.project;
                //Aún no hay tarea seleccionada
                task_id = null;
                //Elimino tareas innecesarias
                tasks.splice(0, Infinity);
                //Habilito / deshabilito campo descripción
                if (isProject(project.code)) {
                    $('#frm-edit #description').attr({'placeholder':'Descripción de la actividad','disabled':false});
                    //$('#frm-edit #msg_status').text('Estado actual del proyecto: ' + project.status);
                    //$('#frm-edit #div_status').css('display','block');
                }
                else {
                    $('#frm-edit #description').attr({'placeholder':'No necesita descripción','disabled':true}).val('');
                    //$('#frm-edit #div_status').css('display','none');
                }
            },
            error: function(msg) {
                $('#txt-detalle').html(msg.responseJSON['message']);
                $('#mdl-edit').modal('hide');
                $('#mdl-message').modal('show');
            }
        });
    });

    /* 
     * Add event submit 
     */
    $('#frm-create').submit(function(e) {
        e.preventDefault();

        var hrIni = $('#frm-create #start_at').val().trim();
        var hrFin = $('#frm-create #end_at').val().trim();
        var start = $('#frm-create #aux-ini').val().substr(0, 10) + ' ' + hrIni + ':00';
        var final = $('#frm-create #aux-fin').val().substr(0, 10) + ' ' + hrFin + ':00';
        var descr = $('#frm-create #description').val().trim();
        var cmmnt = $('#frm-create #comment').val().trim();
        var color = $('#frm-create #color').val().trim();
        var finis = $('#frm-create #finished').prop('checked') ? 1 : 0;
        var isPrj = isProject(project.code);

        if (hrIni && hrFin && start < final && project && (descr || !isPrj)) {
            $('body').loadingModal({
                text:'Un momento, por favor...',
                animation:'wanderingCubes'
            });
            $.post($(this).attr('action'), {
                'project_id': project.id,
                'task_id': task_id,
                'start_at': start,
                'end_at': final,
                'description': descr,
                'comment': cmmnt,
                'color': color,
                'finished': finis
            })
            .done(function(data) {
                $('body').loadingModal('destroy');
                calendar.addEvent({
                    id: data.id,
                    title: project.name + (isPrj ? separator + descr : ''),
                    start: new Date(start),
                    end: new Date(final),
                    description: descr,
                    comment: cmmnt,
                    finished: finis,
                    backgroundColor: color,
                    project: project,
                    task_id: task_id,
                    tasks: tasks
                });
                $('#mdl-create').modal('hide');
            })
            .fail(function(msg) {
                $('body').loadingModal('destroy');
                $('#txt-detalle').html(msg.responseJSON['message']);
                $('#mdl-message').modal('show');
                $('#mdl-create').modal('hide');
            });
        }
        else {
            $('#msg-span-act').empty();
            if (!hrIni || !hrFin) $('#mdl-create #msg-span-act').append('<li>Debes ingresar el inicio y término de la actividad.</li>');
            if (start >= final) $('#mdl-create #msg-span-act').append('<li>La hora de inicio debe ser menor a la hora final.</li>');
            if (!project) $('#mdl-create #msg-span-act').append('<li>Debes seleccionar un proyecto.</li>');
            if (isPrj && !descr) $('#mdl-create #msg-span-act').append('<li>Debes ingresar una descripción de la actividad.</li>');
            $('#mdl-create #div-span-act').css('display', 'block');
        }
    });

    /* 
     * Update event submit
     */
    $('#frm-edit').submit(function(e) {
        e.preventDefault();

        var codex = $('#frm-edit #aux-cod').val();
        var hrIni = $('#frm-edit #start_at').val().trim();
        var hrFin = $('#frm-edit #end_at').val().trim();
        var start = $('#frm-edit #aux-ini').val().substr(0, 10) + ' ' + hrIni + ':00';
        var final = $('#frm-edit #aux-fin').val().substr(0, 10) + ' ' + hrFin + ':00';
        var descr = $('#frm-edit #description').val().trim();
        var cmmnt = $('#frm-edit #comment').val().trim();
        var color = $('#frm-edit #color').val().trim();
        var finis = $('#frm-edit #finished').prop('checked') ? 1 : 0;
        var isPrj = isProject(project.code);

        if (hrIni && hrFin && start < final && project && (descr || !isPrj)) {
            $('body').loadingModal({
                text:'Un momento, por favor...',
                animation:'wanderingCubes'
            });
            $.post($(this).attr('action'), {
                'id': codex,
                'project_id': project.id,
                'task_id': task_id,
                'start_at': start,
                'end_at': final,
                'description': descr,
                'comment': cmmnt,
                'color': color,
                'finished': finis
            })
            .done(function(data) {
                $('body').loadingModal('destroy');
                calendar.getEventById(codex).remove();
                calendar.addEvent({
                    id: codex,
                    title: project.name + (isPrj ? separator + descr : ''),
                    start: new Date(start),
                    end: new Date(final),
                    description: descr,
                    comment: cmmnt,
                    finished: finis,
                    backgroundColor: color,
                    project: project,
                    task_id: task_id,
                    tasks: tasks
                });
                $('#mdl-edit').modal('hide');
            })
            .fail(function(msg) {
                $('body').loadingModal('destroy');
                $('#txt-detalle').html(msg.responseJSON['message']);
                $('#mdl-message').modal('show');
                $('#mdl-edit').modal('hide');
            });
        }
        else {
            $('#msg-span-act').empty();
            if (!hrIni || !hrFin) $('#mdl-edit #msg-span-act').append('<li>Debes ingresar el inicio y término de la actividad.</li>');
            if (start >= final) $('#mdl-edit #msg-span-act').append('<li>La hora de inicio debe ser menor a la hora final.</li>');
            if (!project) $('#mdl-edit #msg-span-act').append('<li>Debes seleccionar un proyecto.</li>');
            if (isPrj && !descr) $('#mdl-edit #msg-span-act').append('<li>Debes ingresar una descripción de la actividad.</li>');
            $('#mdl-edit #div-span-act').css('display', 'block');
        }
    });
    
    /* 
     * Delete event clicked
     */
    $('#btn-delete').click(function() {
        $('#txt-confirm').html('¿Realmente desea eliminar la actividad seleccionada?');
        $('#mdl-confirm').modal('show');
        $('#mdl-edit').modal('hide');
    });

    $('#btn-yes').click(function() {
        var code = $('#frm-edit #aux-cod').val();
        $('body').loadingModal({
            text:'Un momento, por favor...',
            animation:'wanderingCubes'
        });
        $.ajax({
            type: 'delete',
            url: 'activities/' + code ,
            success: function (data) {
                $('body').loadingModal('destroy');
                if (data)
                    calendar.getEventById(code).remove();
                else {
                    $('#txt-detalle').html('Lo sentimos, no se pudo eliminar la actividad seleccionada. Vuelva a intentarlo en unos minutos. De persistir el problema, contacte al administrador del sistema.');
                    $('#mdl-message').modal('show');
                }
            },
            error: function(msg) {
                $('body').loadingModal('destroy');
                $('#txt-detalle').html(msg.responseJSON['message']);
                $('#mdl-message').modal('show');
            }
        });
    });
});
    
$('#mdl-create').on('hidden.bs.modal', function () {
    $('#mdl-create .sltdiv').remove();
});

$('#mdl-edit').on('hidden.bs.modal', function () {
    $('#mdl-edit .sltdiv').remove();
});

function isProject(code) {
    return $.inArray(code,['OTR000000000VAC','OTR000000000PER','OTR000000000ALM','OTR000000000FER']) == -1;
}

function createSelect(e) {
    //Obtengo la tarea seleccionada
    task_id = e.value;
    var level = parseInt(e.name);
    //Elimino selects innecesarios
    var divs = document.getElementsByClassName('sltdiv');
    for (var i=level; i<divs.length;) divs[i].remove();
    //Elimino tareas innecesarias
    tasks.splice(level-1, Infinity);
    //Obtengo la lista de opciones por nivel
    var options = [];
    $('#'+e.id+' option').each(function() {
        if (this.value)
            options.push({id: this.value, name: this.text});
    });
    tasks.push({[level]: {options: options, selected: task_id}});
    //Creo el nuevo select
    level++;
    $.ajax({
        type: 'get',
        url: 'tasks.getByParent/' + task_id,
        success: function(data) {
            if (data.length) {
                $(e).parent().parent().append(
                    '<div class="fila sltdiv"><div class="columna columna-10"><i class="fa fa-puzzle-piece fa-2x fa-icon"></i></div>' +
                    '<div class="columna columna-10c"><select id="seltsk'+level+'" name="'+level+'" onchange="createSelect(this)" required>' +
                    '<option selected disabled hidden value="">Selecciona una actividad</option></select></div></div>');
                $(data).each(function() {
                    var option = $(document.createElement('option'));
                    option.val(this.id);
                    option.text(this.name);
                    $('#seltsk'+level).append(option);
                });
            }
        },
        error: function(msg) {
            $('#txt-detalle').html(msg.responseJSON['message']);
            $('#mdl-create').modal('hide');
            $('#mdl-message').modal('show');
        }
    });
}
</script>
@endsection