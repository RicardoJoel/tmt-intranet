<div id="tabemp" class="tabcontent" style="display:block">
    <div class="sub-form">
        <a onclick="showForm('emp')">
            <h6 id="emp_subt" class="title3">Nuevo</h6>
            <p id="icn-emp" class="icn-sct"><i class="fa fa-minus fa-icon"></i></p>
        </a>
        <div id="div-emp">
            <div class="fila">
                <div class="columna columna-1">
                    <div class="span-fail" id="emp_fail_div"><span id="emp_fail_msg"></span></div>
                </div>
            </div>
            <form method="POST" action="{{ action('EmployeeController@store') }}" role="form" id="emp_form">
                @csrf
                <input type="hidden" name="_method" id="emp_method">
                <input type="hidden" name="emp_id" id="emp_id" value="{{ old('emp_id') }}">
                <div class="fila">
                    <div class="columna columna-3">
                        <p>Perfil*</p>
                        @inject('profiles','App\Services\Profiles')
                        <select name="emp_profile_id" id="emp_profile_id" required>
                            <option selected disabled hidden value="">Selecciona un perfil</option>
                            @foreach ($profiles->get('C') as $index => $profile)
                            <option value="{{ $index }}" {{ old('emp_profile_id') == $index ? 'selected' : '' }}>
                                {{ $profile }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="columna columna-3">
                        <p>Detalle</p>
                        <input type="text" name="emp_detail" id="emp_detail" value="{{ old('emp_detail') }}" maxlength="50" onkeypress="return checkText(event)">
                    </div>
                    <div class="columna columna-9">
                        <p>Costo x hr*</p>
                        <input type="number" name="emp_hourly_rate" id="emp_hourly_rate" value="{{ old('emp_hourly_rate') }}" max="1000" step="any" required>
                    </div>
                    <div class="columna columna-9">
                        <p>N° Horas*</p>
                        <input type="number" name="emp_num_hours" id="emp_num_hours" value="{{ old('emp_num_hours') }}" max="1000" step="1" required>
                    </div>
                    <div class="columna columna-9">
                        <p>Subt. (PEN)</p>
                        <input type="text" name="emp_subtotal" id="emp_subtotal" value="{{ old('emp_subtotal') }}" readonly>					
                    </div>
                </div>
                <div class="fila" style="margin-top:5px">
                    <div class="columna columna-1">
                        <center>
                        <button id="emp_submit" type="submit" class="btn-effie"><i class="fa fa-plus"></i>&nbsp;Agregar</button>
                        <a onclick="clearFormEmp()" class="btn-effie-inv"><i class="fa fa-paint-brush"></i>&nbsp;Limpiar</a>
                        </center>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <table id="tbl-employees" class="tablealumno" style="margin-bottom:10px">
        <thead>
            <th width="30%">Perfil</th>
            <th width="20%">Detalle</th>
            <th width="20%">Costo x hr</th>
            <th width="10%">N° Horas</th>
            <th width="10%">Subt. (PEN)</th>
            <th width="5%">Editar</th>
            <th width="5%">Borrar</th>
        </thead>
        <tbody>
            @foreach ($employees as $index => $emp)
            <tr>
                <td>{{ $emp['profile'] }}</td>
                <td>{{ $emp['detail'] }}</td>
                <td><center>{{ $emp['hourly_rate'] ? number_format($emp['hourly_rate'],2) : '' }}<center></td>
                <td><center>{{ $emp['num_hours'] }}</center></td>
                <td><center>{{ $emp['subtotal'] ? number_format($emp['subtotal'],2) : '' }}<center></td>
                <td><center><a name="{{ $index }}" onclick="editEmployee(this)"><i class="fa fa-edit"></i></a></center></td>
                <td><center><a name="{{ $index }}" onclick="removeEmployee(this)"><i class="fa fa-trash"></i></a></center></td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <th colspan="4" style="text-align:right">Total (PEN):</th>
                <th colspan="1"><center></center></th>
                <th colspan="2"></th>
            </tr>
        </tfoot>			
    </table>
</div>