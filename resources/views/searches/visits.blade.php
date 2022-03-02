<div class="modal fade" id="mdl-sch-visit" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">x</button>
                <h3 class="modal-title">Visitas sin propuesta</h3>
            </div>
            <div class="modal-body">
                <div class="span-fail" id="fail-div-visit"><span id="fail-msg-visit"></span></div>
                <form method="GET" action="{{ route('visits.searchByFilter') }}" id="frm-sch-visit">
                    <input type="hidden" name="visit_cust_id" id="visit_cust_id">
                    <input type="hidden" id="visit_cust_ref">
                    <input type="hidden" id="visit_cust_ruc">
                    <div class="form-section">
                        <a onclick="showForm('fvs')">
                            <h6 id="fvs_subt" class="title3">Filtros de búsqueda</h6>
                            <p id="icn-fvs" class="icn-sct"><i class="fa fa-minus fa-icon"></i></p>
                        </a>
                        <div id="div-fvs">
                            <div class="fila">
                                <div class="columna columna-2">
                                    <p>Cliente</p>
                                    <input type="text" id="visit_cust_name" readonly>
                                </div>
                                <div class="columna columna-4">
                                    <p>Cod. Visita</p>
                                    <input type="text" name="visit_code" id="visit_code" maxlength="8" onkeypress="return checkAlNum(event)" onkeyup="return mayusculas(this)">
                                </div>
                                <div class="columna columna-4">
                                    <p>Cod. Propuesta</p>
                                    <input type="text" name="visit_prop_code" id="visit_prop_code" maxlength="15" onkeypress="return checkAlNum(event)" onkeyup="return mayusculas(this)">
                                </div>
                            </div>
                            <div class="fila">
                                <div class="columna columna-4">
                                    <p>Tipo</p>
                                    @inject('projectTypes','App\Services\ProjectTypes')
                                    <select name="visit_proj_type_id" id="visit_proj_type_id">
                                        <option value="">Todos los tipos</option>
                                        @foreach ($projectTypes->get() as $index => $projectType)
                                        <option value="{{ $index }}">{{ $projectType }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="columna columna-4">
                                    <p>Fecha de visita</p>
                                    <input type="date" name="visit_happen_at" id="visit_happen_at">
                                </div>
                                <div class="columna columna-2">
                                    <p>Responsable</p>
                                    <input type="text" name="visit_user_name" id="visit_user_name" maxlength="100" onkeypress="return checkName(event)">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="fila">
                        <div class="space"></div>
                        <center>
                        <button type="submit" class="btn-effie"><i class="fa fa-search"></i>&nbsp;Buscar</button>
                        <a onclick="clearFormVisit()" class="btn-effie-inv"><i class="fa fa-paint-brush"></i>&nbsp;Limpiar</a>
                        </center>
                    </div>
                </form>
                <div class="fila">
                    <div class="space"></div>
                    <h6 class="title3">Resultados</h6>
                </div>
                <div class="fila">
                    <table id="tbl-visits" class="tablealumno" style="width:100%">
                        <thead>
                            <th width="16%">Código</th>
                            <th width="16%">Tipo</th>
                            <th width="16%">Responsable</th>
                            <th width="16%">Fecha</th>
                            <th width="16%">Cod. Propuesta</th>
                            <th width="16%">Observación</th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                        </thead>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <center>
                <button class="btn-effie" data-dismiss="modal" onclick="sendOnlyCust()"><i class="fa fa-exclamation"></i>&nbsp;Sin visita</button>
                <button class="btn-effie-inv" data-dismiss="modal" onclick="$('#mdl-sch-cust').modal('show');"><i class="fa fa-reply"></i>&nbsp;Atrás</button>
                <button class="btn-effie-inv" data-dismiss="modal">Cerrar</button>
                </center>
            </div>
        </div>
    </div>
</div>