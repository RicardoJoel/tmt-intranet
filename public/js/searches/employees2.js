$("#user_mobile").inputmask({"mask":"999 999 999"});

let tblUser = $('#tbl-users').DataTable({
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
        targets: 6,
        visible: false
    },{
        targets: [0, 2, 4],
        className: "text-center"
    }]
});

$(function() {
    $('#tbl-users tbody').on('click', 'tr', function() {
        var data = tblUser.row(this).data();
        $('#user_wholename').val(data[1] + ' (' + data[0] + ')');
        $('#user_document').val(data[4]);
        $('#user_id').val(data[6]);
        $('#mdl-sch-user').modal('hide');
    });

    $('#btn-sch-user').click(function() {
        var pre = $('#_method').val() ? '../' : '';
        var doc = $('#user_document').val().trim();
        if (doc) {
            $('body').loadingModal({
                text:'Un momento, por favor...',
                animation:'wanderingCubes'
            });
            $.ajax({
                type: 'get',
                url: pre + '../users.getByDocument/' + doc,
                success: function(data) {
                    $('body').loadingModal('destroy');
                    if (data) {
                        $('#user_id').val(data.id);
                        $('#user_wholename').val(data.name + ' (' + data.code + ')');
                    }
                    else{
                        $('#user_id').val('');
                        $('#user_wholename').val('');
                    }
                    $('#user_fail-div').css('display','none');
                },
                error: function(msg) {
                    $('body').loadingModal('destroy');
                    $('#user_fail-msg').text(JSON.stringify(msg));
                    $('#user_fail-div').css('display','block');
                }
            });
        }
        else {
            $('#mdl-sch-user').modal('show');
            clearFormUser();
        }
    });
 
    $('#frm-sch-user').submit(function(e) {
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
                tblUser.clear().draw();
                var dataSet = [];
                $(JSON.parse(data)).each(function() {
                    dataSet.push([
                        this.code,
                        this.name,
                        this.mobile,
                        this.documentType,
                        this.document,
                        this.email,
                        this.id
                    ]);
                });
                tblUser.rows.add(dataSet).draw();
                $('#fail-div-user').css('display','none');
            },
            error: function(msg) {
                $('body').loadingModal('destroy');
                $('#fail-msg-user').text(JSON.stringify(msg));
                $('#fail-div-user').css('display','block');
            }
        });
    });
});

function clearFormUser() {
    $('#user_code').val('');
    $('#user_name').val('');
    $('#user_email').val('');
    $('#user_doc_type_id').val('');
    $('#user_doc').val('');
    $('#user_mobile').val('');
    $('#fail-div-user').css('display','none');
    tblUser.clear().draw();
}

function clearDataUser() {
    $('#user_id').val('');
    $('#user_document').val('');
    $('#user_wholename').val('');
    $('#user_fail-div').css('display','none');
}