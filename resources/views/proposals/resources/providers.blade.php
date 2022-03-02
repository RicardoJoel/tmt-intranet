<div id="tabpro" class="tabcontent" style="display:none">
    <div class="sub-form">
        <a onclick="showForm('pro')">
            <h6 id="pro_subt" class="title3">Nuevo</h6>
            <p id="icn-pro" class="icn-sct"><i class="fa fa-minus fa-icon"></i></p>
        </a>
        <div id="div-pro">
            <div class="fila">
                <div class="columna columna-1">
                    <div class="span-fail" id="pro_fail_div"><span id="pro_fail_msg"></span></div>
                </div>
            </div>
            <form method="POST" action="{{ action('ProviderController@store') }}" role="form" id="pro_form">
                @csrf
                <input type="hidden" name="_method" id="pro_method">
                <input type="hidden" name="pro_id" id="pro_id" value="{{ old('pro_id') }}">
                <input type="hidden" name="pro_rate_id" id="pro_rate_id">
                <input type="hidden" name="pro_rate_date" id="pro_rate_date">
                <input type="hidden" name="pro_rate_value" id="pro_rate_value" value="1">
                <div class="fila">
                    <div class="columna columna-3">
                        <p>Perfil*</p>
                        @inject('profiles','App\Services\Profiles')
                        <select name="pro_profile_id" id="pro_profile_id" required>
                            <option selected disabled hidden value="">Selecciona un perfil</option>
                            @foreach ($profiles->get('S') as $index => $profile)
                            <option value="{{ $index }}" {{ old('pro_profile_id') == $index ? 'selected' : '' }}>
                                {{ $profile }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="columna columna-3">
                        <p>Detalle</p>
                        <input type="text" name="pro_detail" id="pro_detail" value="{{ old('pro_detail') }}" maxlength="50" onkeypress="return checkText(event)">
                    </div>
                    <div class="columna columna-9">
                        <p>Costo*</p>
                        <input type="number" name="pro_price" id="pro_price" value="{{ old('pro_price') }}" max="10000" step="any">
                    </div>
                    <div class="columna columna-9">
                        <p>Moneda*</p>
                        @inject('currencies','App\Services\Currencies')
                        <select name="pro_currency_id" id="pro_currency_id" required>
                            @foreach ($currencies->getCode() as $index => $currency)
                            <option value="{{ $index }}" {{ old('pro_currency_id') == $index ? 'selected' : '' }}>
                                {{ $currency }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="columna columna-9">
                        <p>Subt. (PEN)</p>
                        <input type="text" name="pro_subtotal" id="pro_subtotal" value="{{ old('pro_subtotal') }}" readonly>					
                    </div>
                </div>
                <div id="pro_rate_div" class="fila" style="display:none">
                    <div class="columna columna-1">
                        <span id="pro_rate_msg" class="lbl-msg"></span>
                    </div>
                </div>
                <div class="fila" style="margin-top:5px">
                    <div class="columna columna-1">
                        <center>
                        <button id="pro_submit" type="submit" class="btn-effie"><i class="fa fa-plus"></i>&nbsp;Agregar</button>
                        <a onclick="clearFormPro()" class="btn-effie-inv"><i class="fa fa-paint-brush"></i>&nbsp;Limpiar</a>
                        </center>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <table id="tbl-providers" class="tablealumno" style="margin-bottom:10px">
        <thead>
            <th width="30%">Perfil</th>
            <th width="20%">Detalle</th>
            <th width="10%">Costo</th>
            <th width="20%">Moneda</th>
            <th width="10%">Subt. (PEN)</th>
            <th width="5%">Editar</th>
            <th width="5%">Borrar</th>
        </thead>
        <tbody>
            @foreach ($providers as $index => $pro)
            <tr>
                <td>{{ $pro['profile'] }}</td>
                <td>{{ $pro['detail'] }}</td>
                <td><center>{{ $pro['price'] ? number_format($pro['price'],2) : '' }}</center></td>
                <td><center>{{ $pro['currency'] }}</center></td>
                <td><center>{{ $pro['subtotal'] ? number_format($pro['subtotal'],2) : '' }}</center></td>
                <td><center><a name="{{ $index }}" onclick="editProvider(this)"><i class="fa fa-edit"></i></a></center></td>
                <td><center><a name="{{ $index }}" onclick="removeProvider(this)"><i class="fa fa-trash"></i></a></center></td>
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