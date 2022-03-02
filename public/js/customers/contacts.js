$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')     
    }
});

let tblCnt = $('#tbl-contacts').DataTable({
    lengthChange: false,
    searching: false,
    paginate: false,
    ordering: false,
    bInfo : false,
	language: {
		'decimal': '',
		'emptyTable': 'No hay información para mostrar',
		'info': 'Mostrando _START_ a _END_ de _TOTAL_ entradas',
		'infoEmpty': 'Mostrando 0 to 0 of 0 entradas',
		'infoFiltered': '(Filtrado de _MAX_ total entradas)',
		'infoPostFix': '',
		'thousands': ',',
		'lengthMenu': 'Mostrar _MENU_ entradas',
		'loadingRecords': 'Cargando...',
		'processing': 'Procesando...',
		'search': 'Buscar ',
		'zeroRecords': 'Sin resultados encontrados',
		'paginate': {
			'first': 'Primero',
			'last': 'Último',
			'next': 'Siguiente',
			'previous': 'Anterior'
		}
    }
});

let pre = $('#_method').val() ? '../' : '';
	
$(function() {
    $('#contact_form').submit(function(e) {
        e.preventDefault();
        $('body').loadingModal({
            text:'Un momento, por favor...',
            animation:'wanderingCubes'
        });
        $.post($(this).attr('action'), $(this).serialize())
        .done(function(data) {
            $('body').loadingModal('destroy');
            tblCnt.clear().draw();
            var dataSet = [];
            $(JSON.parse(data)).each(function(index) {
                dataSet.push([
                    this.lastname + ', ' + this.name,
                    this.position ?? '',
                    this.type ?? '',
                    '<center>' + ((this.country ?? '') + (this.mobile ? ' ' + this.mobile : '')) + '</center>',
                    '<center>' + ((this.phone ?? '') + (this.annex ? ' #' + this.annex : '')) + '</center>',
                    '<center>' + (this.email ?? '') + '</center>',
                    '<center><a name="' + index + '" onclick="editContact(this)"><i class="fa fa-edit"></i></a></center>',
                    '<center><a name="' + index + '" onclick="removeContact(this)"><i class="fa fa-trash"></i></a></center>'
                ]);
            });
            tblCnt.rows.add(dataSet).draw();
            clearForm();
        })  
        .fail(function(msg) {
            $('body').loadingModal('destroy');
            var message = '<p><b>¡Atención!</b></p><ul>';
            $.each(msg.responseJSON['errors'], function() {
                message += addItem(this);
            });
            $('#contact_fail_msg').html(message + '</ul>');
            $('#contact_fail_div').css('display','block');
        });
	});
});

function editContact(e) {
    $('body').loadingModal({
        text:'Un momento, por favor...',
        animation:'wanderingCubes'
    });
    $.ajax({
        type: 'get',
        url: pre + '../contacts/' + e.name + '/edit',
        success: function(data) {
            $('body').loadingModal('destroy');
            var item = JSON.parse(data);
            $('#contact_id').val(item.id);
            $('#contact_type_id').val(item.type_id);
            $('#contact_name').val(item.name);
            $('#contact_lastname').val(item.lastname);
            $('#contact_position').val(item.position);
            $('#contact_country_id').val(item.country_id);
            $('#contact_mobile').val(item.mobile);
            $('#contact_phone').val(item.phone);
            $('#contact_annex').val(item.annex);
            $('#contact_email').val(item.email);
            $('#contact_method').val('PATCH');
            $('#contact_submit').html('<i class="fa fa-refresh"></i>&nbsp;Actualizar');
            $('#contact_form').attr('action',pre + '../contacts/' + e.name);
            $('#contact_fail_div').css('display','none');
        },
        error: function(msg) {
            $('body').loadingModal('destroy');
            $('#contact_fail_msg').html(JSON.stringify(msg));
            $('#contact_fail_div').css('display', 'block');
        }
    });
}

function removeContact(e) {
	if (confirm('¿Realmente desea eliminar el contacto seleccionado?')) {
		$('body').loadingModal({
			text:'Un momento, por favor...',
			animation:'wanderingCubes'
		});
		$.ajax({
			type: 'delete',
			url: pre + '../contacts/' + e.name,
			success: function(data) {
                $('body').loadingModal('destroy');
				tblCnt.clear().draw();
				var dataSet = [];			
				$(JSON.parse(data)).each(function(index) {
                    dataSet.push([
                        this.lastname + ', ' + this.name,
                        this.position ?? '',
                        this.type ?? '',
                        '<center>' + ((this.country ?? '') + (this.mobile ? ' ' + this.mobile : '')) + '</center>',
                        '<center>' + ((this.phone ?? '') + (this.annex ? ' #' + this.annex : '')) + '</center>',
                        '<center>' + (this.email ?? '') + '</center>',
                        '<center><a name="' + index + '" onclick="editContact(this)"><i class="fa fa-edit"></i></a></center>',
                        '<center><a name="' + index + '" onclick="removeContact(this)"><i class="fa fa-trash"></i></a></center>'
                    ]);
				});
				tblCnt.rows.add(dataSet).draw();
				$('#contact_fail_div').css('display', 'none');
                clearForm();
			},
			error: function(msg) {
                $('body').loadingModal('destroy');
                $('#contact_fail_msg').html(JSON.stringify(msg.responseJSON['errors']));
                $('#contact_fail_div').css('display', 'block');
			}
		});
	}
}

function clearForm() {
    $('#contact_id').val('');
    $('#contact_type_id').val('');
    $('#contact_name').val('');
    $('#contact_lastname').val('');
    $('#contact_position').val('');
    $('#contact_country_id').val(164);
    $('#contact_mobile').val('');
    $('#contact_phone').val('');
    $('#contact_annex').val('');
    $('#contact_email').val('');
    $('#contact_method').val('');
    $('#contact_submit').html('<i class="fa fa-plus"></i>&nbsp;Agregar');
    $('#contact_form').attr('action',pre + '../contacts');
    $('#contact_fail_div').css('display','none');
}