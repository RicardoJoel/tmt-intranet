$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')     
    }
});

const preProj = $('#_method').val() ? '../' : '';

$(function() {
	let project_id;

	$('#btn-submit').click(function() {
		//obtengo las tareas ingresads al proyecto
		var tasks = [];
		$.each(ge.tasks, function() {
			tasks.push({
				id			:	this.id,
				code		:	this.code,
				name		:	this.name,
				start_at	:	moment(this.start).format('YYYY-MM-DD'),
				end_at		:	moment(this.end).format('YYYY-MM-DD'),
				start_ms	:	this.startIsMilestone ? 1 : 0,
				end_ms		:	this.endIsMilestone ? 1 : 0,
				status		:	this.status,
				progress	:	this.progress,
				description	:	this.description,
				relevance	:	this.relevance,
				duration	:	this.duration,
				depends		:	this.depends,
				level		:	this.level
			});		
		});
		//comunicación con el servidor
		$('body').loadingModal({
			text:'Un momento, por favor...',
			animation:'wanderingCubes'
		});
		$.post($('#frm-proj').attr('action'), {
			_method			: $('#_method').val(),
			proposal_id		: $('#proposal_id').val(),
			code			: $('#prop_code').val(),
			status			: $('#status').val(),
			happen_at		: $('#happen_at').val(),
			customer_id 	: $('#customer_id').val(),
			user_id			: $('#user_id').val(),
			project_type_id : $('#project_type_id').val(),
			name			: $('#name').val(),
			tasks			: tasks
		})
		.done(function(data) {
			$('body').loadingModal('destroy');
			$('#txt-detalle').html(data.message);
			$('#mdl-message').modal('show');
			project_id = data.id;
		})
		.fail(function(msg) {
			$('body').loadingModal('destroy');
			var message = '<p><b>¡Atención!</b> Revisa los campos obligatorios.</p><br><ul>';
			$.each(msg.responseJSON['errors'], function() { message += addItem(this); });
			$('#fail_msg').html(message + '</ul>');
			$('#fail_div').css('display','block');
			$('#done_div').css('display','none');
			animacion();
		});
	});

	$('#mdl-message').on('hidden.bs.modal', function () {
		location.href = preProj + '../projects/' + project_id;
	});

	$('#ver-list').click(function () {
		location.href = preProj + '../projects/';
	});
});