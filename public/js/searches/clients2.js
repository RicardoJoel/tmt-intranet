let tblCust = $('#tbl-customers').DataTable({
    lengthChange: false,
    searching: false,
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
    columnDefs: [{
        targets: [6, 7],
        visible: false
    },{
        targets: [0, 2, 3],
        className: "text-center"
    }]
});

$(function() {
    $('#tbl-customers tbody').on('click', 'tr', function() {
        var data = tblCust.row(this).data();
        $('#prop_cust_name').val(data[6] + ' (' + data[0] + ')');
        $('#prop_cust_ref').val(data[2]);
        $('#prop_cust_ruc').val(data[3]);
        $('#prop_cust_id').val(data[7]);
        $('#customer_ref').val(data[2]);
        $('#frm-sch-prop').submit();
        $('#mdl-sch-cust').modal('hide');
        $('#mdl-sch-prop').modal('show');
    });

    $('#btn-sch-cust').click(function() {
        var pre = $('#_method').val() ? '../' : '';
        var doc = $('#customer_ruc').val().trim();
        if (doc) {
            $('body').loadingModal({
                text:'Un momento, por favor...',
                animation:'wanderingCubes'
            });
            $.ajax({
                type: 'get',
                url: pre + '../customers.getByDocument/' + doc,
                success: function(data) {
                    $('body').loadingModal('destroy');
                    if (data) {
                        $('#prop_cust_name').val(data.alias + ' (' + data.code + ')');
                        $('#prop_cust_ref').val(data.ref);
                        $('#prop_cust_ruc').val(data.ruc);
                        $('#prop_cust_id').val(data.id);
                        $('#frm-sch-prop').submit();
                        $('#mdl-sch-prop').modal('show');
                    }
                    else{
                        $('#customer_id').val('');
                        $('#customer_name').val('');
                    }
                    $('#customer_fail-div').css('display','none');
                    setProjectCode();
                },
                error: function(msg) {
                    $('body').loadingModal('destroy');
                    $('#customer_fail-msg').html(JSON.stringify(msg));
                    $('#customer_fail-div').css('display','block');
                }
            });
        }
        else {
            $('#mdl-sch-cust').modal('show');
            clearFormCust();
        }
    });
 
    $('#frm-sch-cust').submit(function(e) {
        e.preventDefault();
        $('body').loadingModal({
            text:'Un momento, por favor...',
            animation:'wanderingCubes'
        });
        $.ajax({
            type: 'get',
            data: $(this).serialize(),
            url: $(this).attr('action'),
            success: function(data) {
                $('body').loadingModal('destroy');
                tblCust.clear().draw();
                var dataSet = [];
                $(JSON.parse(data)).each(function() {
                    dataSet.push([
                        this.code,
                        this.name,
                        this.ref,
                        this.ruc,
                        this.representative,
                        this.business,
                        this.alias,
                        this.id
                    ]);
                });
                tblCust.rows.add(dataSet).draw();
                $('#fail-div-cust').css('display','none');
            },
            error: function(msg) {
                $('body').loadingModal('destroy');
                $('#fail-msg-cust').html(JSON.stringify(msg));
                $('#fail-div-cust').css('display','block');
            }
        });
    });
});

function clearFormCust() {
    $('#cust_code').val('');
    $('#cust_name').val('');
    $('#cust_ref').val('');
    $('#cust_ruc').val('');
    $('#cust_repr').val('');
    $('#cust_business_id').val('');
    $('#fail-div-cust').css('display','none');
    tblCust.clear().draw();
}

function clearDataCust() {
    $('#customer_id').val('');
    $('#customer_ruc').val('');
    $('#customer_ref').val('');
    $('#customer_name').val('');
    $('#customer_fail-div').css('display','none');
}