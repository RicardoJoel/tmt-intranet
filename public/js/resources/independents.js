$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')     
    }
});

let tblFre = $('#tbl-independents').DataTable({
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
    },
    footerCallback: function () {
        var api = this.api();

        // Remove the formatting to get integer data for summation
        var intVal = function ( i ) {
            return typeof i === 'string' ? i.replace(/[<center>,</center>]/g, '')*1 :
                   typeof i === 'number' ? i : 0;
        };
        
        // Total over all pages
        totFre = api
            .column( 4 )
            .data()
            .reduce( function (a, b) {
                return intVal(a) + intVal(b);
            }, 0 );

        // Update footer
        $( api.column( 4 ).footer() ).html(
            parseFloat(totFre).toFixed(2)
        );

        calculateTotals();
    }
});

let preInd = $('#_method').val() ? '../' : '';

$(function() {
    $('#fre_form').submit(function(e) {
        e.preventDefault();
        $('body').loadingModal({
            text:'Un momento, por favor...',
            animation:'wanderingCubes'
        });
        $.post($(this).attr('action'), $(this).serialize())
        .done(function(data) {
            $('body').loadingModal('destroy');
            tblFre.clear().draw();
            var dataSet = [];
            $(JSON.parse(data)).each(function(index) {
                dataSet.push([
                    this.profile ?? '',
                    this.detail ?? '',
                    '<center>' + (this.price ? parseFloat(this.price).toFixed(2) : '') + '</center>',
                    '<center>' + (this.currency ?? '') + '</center>',
                    '<center>' + (this.subtotal ? parseFloat(this.subtotal).toFixed(2) : '') + '</center>',
                    '<center><a name="' + index + '" onclick="editIndependent(this)"><i class="fa fa-edit"></i></a></center>',
                    '<center><a name="' + index + '" onclick="removeIndependent(this)"><i class="fa fa-trash"></i></a></center>'
                ]);
            });
            tblFre.rows.add(dataSet).draw();
            clearFormFre();
        })
        .fail(function(msg) {
            $('body').loadingModal('destroy');
            var message = '<p><b>¡Atención!</b></p><ul>';
            $.each(msg.responseJSON['errors'], function() {
                message += addItem(this);
            });
            $('#fre_fail_msg').html(message + '</ul>');
            $('#fre_fail_div').css('display','block');
        });
	});
});

function editIndependent(e) {
    $('body').loadingModal({
        text:'Un momento, por favor...',
        animation:'wanderingCubes'
    });
    $.ajax({
        type: 'get',
        url: preInd + '../independents/' + e.name + '/edit',
        success: function(data) {
            $('body').loadingModal('destroy');
            var item = JSON.parse(data);
            $('#fre_id').val(item.id);
            $('#fre_profile_id').val(item.profile_id);
            $('#fre_detail').val(item.detail);
            $('#fre_currency_id').val(item.currency_id);
            $('#fre_rate_value').val(item.rate_value);
            $('#fre_rate_id').val(item.rate_id);
            $('#fre_price').val(parseFloat(item.price).toFixed(2));
            $('#fre_subtotal').val(parseFloat(item.subtotal).toFixed(2));
            $('#fre_method').val('PATCH');
            $('#fre_submit').html('<i class="fa fa-refresh"></i>&nbsp;Actualizar');
            $('#fre_form').attr('action',preInd + '../independents/' + e.name);
            $('#fre_subt').text('Editar');
            $('#fre_fail_div').css('display','none');
            $('#div-fre').css('display','block');
		    $('#icn-fre').html('<i class="fa fa-minus fa-icon"></i>');
            if (item.rate_id) {
                $('#fre_rate_msg').text('Tipo de cambio al ' + 
                    moment(new Date(item.rate_date)).format('LL') + ': ' + 
                    parseFloat(item.rate_value).toFixed(3) + ' PEN/USD');
                $('#fre_rate_div').css('display','block');
            }
            else {
                $('#fre_rate_div').css('display','none');
            }
        },
        error: function(msg) {
            $('body').loadingModal('destroy');
            $('#fre_fail_msg').text(JSON.stringify(msg));
            $('#fre_fail_div').css('display', 'block');
        }
    });
}

function removeIndependent(e) {
	if (confirm('¿Realmente deseas eliminar el recurso seleccionado?')) {
		$('body').loadingModal({
			text:'Un momento, por favor...',
			animation:'wanderingCubes'
		});
		$.ajax({
			type: 'delete',
			url: preInd + '../independents/' + e.name,
			success: function(data) {
                $('body').loadingModal('destroy');
				tblFre.clear().draw();
				var dataSet = [];			
				$(JSON.parse(data)).each(function(index) {
                    dataSet.push([
                        this.profile ?? '',
                        this.detail ?? '',
                        '<center>' + (this.price ? parseFloat(this.price).toFixed(2) : '') + '</center>',
                        '<center>' + (this.currency ?? '') + '</center>',
                        '<center>' + (this.subtotal ? parseFloat(this.subtotal).toFixed(2) : '') + '</center>',
                        '<center><a name="' + index + '" onclick="editIndependent(this)"><i class="fa fa-edit"></i></a></center>',
                        '<center><a name="' + index + '" onclick="removeIndependent(this)"><i class="fa fa-trash"></i></a></center>'
                    ]);
                });
				tblFre.rows.add(dataSet).draw();
                clearFormFre();
			},
			error: function(msg) {
                $('body').loadingModal('destroy');
                $('#fre_fail_msg').text(JSON.stringify(msg));
                $('#fre_fail_div').css('display','block');
			}
		});
	}
}

function clearFormFre() {
    $('#fre_id').val('');
    $('#fre_detail').val('');
    $('#fre_profile_id').val('');
    $('#fre_currency_id').val(1);
    $('#fre_rate_value').val(1);
    $('#fre_rate_date').val('');
    $('#fre_rate_id').val('');
    $('#fre_price').val('');
    $('#fre_subtotal').val('');
    $('#fre_method').val('');
    $('#fre_submit').html('<i class="fa fa-plus"></i>&nbsp;Agregar');
    $('#fre_form').attr('action',preInd + '../independents');
    $('#fre_subt').text('Nuevo');
    $('#fre_fail_div').css('display','none');
    $('#fre_rate_div').css('display','none');
}

$('#fre_currency_id').change(function() {
    if ($(this).val() == 1) {
        $('#fre_rate_msg').text('');
        $('#fre_rate_div').css('display','none');
        $('#fre_rate_value').val(1);
        $('#fre_rate_date').val('');
        $('#fre_rate_id').val('');
        priceFre();
    }
    else {
        $.ajax({
            type: 'get',
            url: preInd + '../currentRate',
            success: (data) => {
                $('#fre_rate_msg').text('Tipo de cambio al ' + 
                    moment(new Date(data.date)).format('LL') + ': ' + 
                    parseFloat(data.value).toFixed(3) + ' PEN/USD');
                $('#fre_rate_div').css('display','block');
                $('#fre_rate_value').val(data.value);
                $('#fre_rate_date').val(data.date);
                $('#fre_rate_id').val(data.id);
                priceFre();
            },
            error: function(msg) {
                $('#fre_fail_msg').text(JSON.stringify(msg));
                $('#fre_fail_div').css('display', 'block');
            }
        });
    }
});

$('#fre_price').change(function() {
    priceFre();
});

function priceFre() {
    var xrate = $('#fre_rate_value').val();
    var price = $('#fre_price').val();
    $('#fre_subtotal').val(parseFloat(xrate * price).toFixed(2));
}