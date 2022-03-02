<div class="fila">
    <div class="space"></div>
    <div class="columna columna-1">
        <div class="form-section">
            <a onclick="showForm('dep')">
                <h6 id="dep_subt" class="title3">Listado de contactos</h6>
                <p id="icn-dep" class="icn-sct"><i class="fa fa-minus fa-icon"></i></p>
            </a>
            <div id="div-dep">
                <div class="fila">
                    <div class="columna columna-1">
                        <div class="span-fail" id="contact_fail_div"><span id="contact_fail_msg"></span></div>
                    </div>
                </div>
                <form method="POST" action="{{ action('ContactController@store') }}" role="form" id="contact_form">
                    @csrf
                    <input type="hidden" name="_method" id="contact_method">
                    <input type="hidden" name="contact_id" id="contact_id" value="{{ old('contact_id') }}">
                    <div class="fila">
                        <div class="columna columna-4">
                            <p>Nombres*</p>
                            <input type="text" name="contact_name" id="contact_name" maxlength="50" value="{{ old('contact_name') }}" onkeypress="return checkName(event)" required>
                        </div>
                        <div class="columna columna-4">
                            <p>Apellidos*</p>
                            <input type="text" name="contact_lastname" id="contact_lastname" maxlength="50" value="{{ old('contact_lastname') }}" onkeypress="return checkName(event)" required>
                        </div>
                        <div class="columna columna-3">
                            <p>Cargo*</p>
                            <input type="text" name="contact_position" id="contact_position" maxlength="100" value="{{ old('contact_position') }}" onkeypress="return checkText(event)" required>
                        </div>
                        <div class="columna columna-6">
                            <p>Tipo*</p>
                            @inject('contactTypes','App\Services\ContactTypes')
                            <select name="contact_type_id" id="contact_type_id" required>
                                <option selected disabled hidden value="">Selecciona</option>
                                @foreach ($contactTypes->get() as $index => $contactType)
                                <option value="{{ $index }}" {{ old('contact_type_id') == $index ? 'selected' : '' }}>
                                    {{ $contactType }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="fila" style="margin-bottom:5px">
                        <div class="columna columna-6">
                            <p>Código de país*</p>
                            @inject('countries','App\Services\Countries')
                            <select name="contact_country_id" id="contact_country_id" required>
                                <option selected disabled hidden value="">Selecciona un país</option>
                                @foreach ($countries->get() as $index => $country)
                                <option value="{{ $index }}" {{ old('contact_country_id',164) == $index ? 'selected' : '' }}>
                                    {{ $country }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="columna columna-6">
                            <p>Celular*</p>
                            <input type="tel" name="contact_mobile" id="contact_mobile" maxlength="11" value="{{ old('contact_mobile') }}" onkeypress="return checkNumber(event)" required>
                        </div>
                        <div class="columna columna-6">
                            <p>Teléfono fijo</p>
                            <input type="tel" name="contact_phone" id="contact_phone" maxlength="11" value="{{ old('contact_phone') }}" onkeypress="return checkNumber(event)">
                        </div>
                        <div class="columna columna-6">
                            <p>Anexo</p>
                            <input type="tel" name="contact_annex" id="contact_annex" maxlength="6" value="{{ old('contact_annex') }}" onkeypress="return checkNumber(event)">
                        </div>
                        <div class="columna columna-3">
                            <p>Correo electrónico</p>
                            <input type="email" name="contact_email" id="contact_email" maxlength="50" value="{{ old('contact_email') }}" onkeypress="return checkEmail(event)">
                        </div>
                    </div>
                    <div class="fila">
                        <div class="columna columna-1">
                            <center>
                            <button id="contact_submit" type="submit" class="btn-effie"><i class="fa fa-plus"></i>&nbsp;Agregar</button>
                            <a onclick="clearForm()" class="btn-effie-inv"><i class="fa fa-paint-brush"></i>&nbsp;Limpiar</a>	
                            </center>
                        </div>
                    </div>
                </form>
                <div class="space"></div>
                <table id="tbl-contacts" class="tablealumno" style="margin-bottom:10px">
                    <thead>
                        <th width="20%">Nombre completo</th>
                        <th width="10%">Cargo</th>
                        <th width="10%">Tipo</th>
                        <th width="10%">Celular</th>
                        <th width="10%">Teléfono</th>
                        <th width="20%">Correo electrónico</th>
                        <th width="5%">Editar</th>
                        <th width="5%">Borrar</th>
                    </thead>
                    <tbody>
                        @foreach ($contacts as $index => $contact)
                        <tr>
                            <td>{{ $contact['lastname'].', '.$contact['name'] }}</td>
                            <td>{{ $contact['position'] }}</td>
                            <td>{{ $contact['type'] }}</td>
                            <td><center>{{ $contact['country'].' '.$contact['mobile'] }}</center></td>
                            <td><center>{{ $contact['phone'].($contact['annex'] ? ' #'.$contact['annex'] : '') }}</center></td>
                            <td><center>{{ $contact['email'] }}</center></td>
                            <td><center><a name="{{ $index }}" onclick="editContact(this)"><i class="fa fa-edit"></i></a></center></td>
                            <td><center><a name="{{ $index }}" onclick="removeContact(this)"><i class="fa fa-trash"></i></a></center></td>
                        </tr>
                        @endforeach
                    </tbody>			
                </table>
            </div>
        </div>
    </div>
</div>