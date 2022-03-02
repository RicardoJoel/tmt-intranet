<div class="modal fade" id="mdl-sch-user" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">x</button>
                <h3 class="modal-title">Búsqueda de servidores</h3>
            </div>
            <div class="modal-body">
                <div class="span-fail" id="fail-div-user"><span id="fail-msg-user"></span></div>
                <form method="GET" action="{{ route('users.searchByFilter') }}" id="frm-sch-user">
                    <div class="form-section">
                        <a onclick="showForm('fus')">
                            <h6 id="fus_subt" class="title3">Filtros de búsqueda</h6>
                            <p id="icn-fus" class="icn-sct"><i class="fa fa-minus fa-icon"></i></p>
                        </a>
                        <div id="div-fus">
                            <div class="fila">
                                <div class="columna columna-5">
                                    <p>Código</p>
                                    <input type="text" name="user_code" id="user_code" maxlength="8" onkeypress="return checkAlNum(event)">
                                </div>
                                <div class="columna columna-5f">
                                    <p>Nombres o apellidos</p>
                                    <input type="text" name="user_name" id="user_name" maxlength="100" onkeypress="return checkName(event)">
                                </div>
                                <div class="columna columna-5">
                                    <p>Celular</p>
                                    <input type="text" name="user_mobile" id="user_mobile" maxlength="11" onkeypress="return checkText(event)">
                                </div>
                            </div>
                            <div class="fila">
                                <div class="columna columna-5d">
                                    <p>Tipo de documento</p>
                                    @inject('documentTypes','App\Services\DocumentTypes')
                                    <select name="user_doc_type_id" id="user_doc_type_id">
                                        <option value="">Todos los tipos</option>
                                        @foreach ($documentTypes->get() as $index => $documentType)
                                        <option value="{{ $index }}">{{ $documentType['name'] }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="columna columna-5">
                                    <p>N° Documento</p>
                                    <input type="text" name="user_doc" id="user_doc" maxlength="15" onkeypress="return checkAlNum(event)">
                                </div>
                                <div class="columna columna-5d">
                                    <p>Correo electrónico</p>
                                    <input type="text" name="user_email" id="user_email" maxlength="50" onkeypress="return checkEmail(event)">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="fila">
                        <div class="space"></div>
                        <center>
                        <button type="submit" class="btn-effie"><i class="fa fa-search"></i>&nbsp;Buscar</button>
                        <a onclick="clearFormUser()" class="btn-effie-inv"><i class="fa fa-paint-brush"></i>&nbsp;Limpiar</a>
                        </center>
                    </div>
                </form>
                <div class="fila">
                    <div class="space"></div>
                    <h6 class="title3">Resultados</h6>
                </div>
                <div class="fila">
                    <table id="tbl-users" class="tablealumno" style="width:100%">
                        <thead>
                            <th width="15%">Código</th>
                            <th width="15%">Nombre</th>
                            <th width="15%">Celular</th>
                            <th width="15%">Tipo doc.</th>
                            <th width="15%">N°&nbsp;Doc.</th>
                            <th width="15%">Correo electrónico</th>
                            <th></th>
                        </thead>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <center><button class="btn-effie-inv" data-dismiss="modal">Cerrar</button></center>
            </div>
        </div>
    </div>
</div>