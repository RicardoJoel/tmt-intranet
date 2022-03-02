<div class="modal fade" id="mdl-sch-cust" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">x</button>
                <h3 class="modal-title">Búsqueda de clientes</h3>
            </div>
            <div class="modal-body">
                <div class="span-fail" id="fail-div-cust"><span id="fail-msg-cust"></span></div>
                <form method="GET" action="{{ route('customers.searchByFilter') }}" id="frm-sch-cust">
                    <div class="form-section">
                        <a onclick="showForm('fcs')">
                            <h6 id="fcs_subt" class="title3">Filtros de búsqueda</h6>
                            <p id="icn-fcs" class="icn-sct"><i class="fa fa-minus fa-icon"></i></p>
                        </a>
                        <div id="div-fcs">
                            <div class="fila">
                                <div class="columna columna-5">
                                    <p>Código</p>
                                    <input type="text" name="cust_code" id="cust_code" maxlength="8" onkeypress="return checkAlNum(event)">
                                </div>
                                <div class="columna columna-5f">
                                    <p>Razón social o nombre comercial</p>
                                    <input type="text" name="cust_name" id="cust_name" maxlength="100" onkeypress="return checkText(event)">
                                </div>
                                <div class="columna columna-5">
                                    <p>Referencia</p>
                                    <input type="text" name="cust_ref" id="cust_ref" maxlength="3" onkeypress="return checkText(event)" onkeyup="return mayusculas(this)">
                                </div>
                            </div>
                            <div class="fila">
                                <div class="columna columna-5">
                                    <p>R. U. C.</p>
                                    <input type="text" name="cust_ruc" id="cust_ruc" maxlength="11" onkeypress="return checkNumber(event)">
                                </div>
                                <div class="columna columna-5d">
                                    <p>Representante legal</p>
                                    <input type="text" name="cust_repr" id="cust_repr" maxlength="50" onkeypress="return checkName(event)">
                                </div>
                                <div class="columna columna-5d">
                                    <p>Rubro de negocio</p>
                                    @inject('bussiness','App\Services\Bussiness')
                                    <select name="cust_business_id" id="cust_business_id">
                                        <option value="">Todos los rubros</option>
                                        @foreach ($bussiness->get() as $index => $business)
                                        <option value="{{ $index }}">{{ $business }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="fila">
                        <div class="space"></div>
                        <center>
                        <button type="submit" class="btn-effie"><i class="fa fa-search"></i>&nbsp;Buscar</button>
                        <a onclick="clearFormCust()" class="btn-effie-inv"><i class="fa fa-paint-brush"></i>&nbsp;Limpiar</a>
                        </center>
                    </div>
                </form>
                <div class="fila">
                    <div class="space"></div>
                    <h6 class="title3">Resultados</h6>
                </div>
                <div class="fila">
                    <table id="tbl-customers" class="tablealumno" style="width:100%">
                        <thead>
                            <th width="15%">Código</th>    
                            <th width="15%">Nombre</th>
                            <th width="15%">Referencia</th>
                            <th width="15%">R.&nbsp;U.&nbsp;C.</th>
                            <th width="15%">Representante</th>
                            <th width="15%">Rubro</th>
                            <th></th>
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