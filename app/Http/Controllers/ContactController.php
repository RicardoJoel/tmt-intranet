<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ContactType;
use App\Country;
use Response;

class ContactController extends Controller
{
    protected const MSG_ERR_INVIDX = 'El índice ingresado es inválido.';

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        self::validate($request, [
            'contact_name' => 'required|string|max:50',
            'contact_lastname' => 'required|string|max:50',
            'contact_position' => 'required|string|max:100',
            'contact_type_id' => 'required|int|min:1',
            'contact_country_id' => 'required|int|min:1',
            'contact_mobile' => 'required|string|regex:/[0-9]{3} [0-9]{3} [0-9]{3}/',
            'contact_phone' => 'nullable|string|regex:/[0-9]{2} [0-9]{3} [0-9]{4}/',
            'contact_annex' => 'nullable|string|regex:/[0-9]{4,6}/',
            'contact_email' => 'nullable|email:rfc|max:50',
        ], self::validationErrorMessages());

        $contacts = session('contacts', []);
        $contacts[] = [
            'id' => '',
            'type' => ContactType::find($request->contact_type_id)->name,
            'country' => Country::find($request->contact_country_id)->code,
            'name' => $request->contact_name,
            'lastname' => $request->contact_lastname,
            'position' => $request->contact_position,
            'mobile' => $request->contact_mobile,
            'phone' => $request->contact_phone,
            'annex' => $request->contact_annex,
            'email' => $request->contact_email,
        ];
        session(['contacts' => $contacts]);
        return json_encode($contacts);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $contacts = session('contacts', []);

        if ($id < 0 || count($contacts) <= $id)
            return Response::json(['success' => 'false', 'errors' => ['message' => self::MSG_ERR_INVIDX]], 400);
        
        $item = $contacts[$id];
        $contact = [
            'id' => $item['id'],
            'type_id' => ContactType::where('name',$item['type'])->get()->first()->id,
            'country_id' => Country::where('code',$item['country'])->get()->first()->id,
            'name' => $item['name'],
            'lastname' => $item['lastname'],
            'position' => $item['position'],
            'mobile' => $item['mobile'],
            'phone' => $item['phone'],
            'annex' => $item['annex'],
            'email' => $item['email'],
        ];
        return json_encode($contact);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        self::validate($request, [
            'contact_name' => 'required|string|max:50',
            'contact_lastname' => 'required|string|max:50',
            'contact_position' => 'required|string|max:100',
            'contact_type_id' => 'required|int|min:1',
            'contact_country_id' => 'required|int|min:1',
            'contact_mobile' => 'required|string|regex:/[0-9]{3} [0-9]{3} [0-9]{3}/',
            'contact_phone' => 'nullable|string|regex:/[0-9]{2} [0-9]{3} [0-9]{4}/',
            'contact_annex' => 'nullable|string|regex:/[0-9]{4,6}/',
            'contact_email' => 'nullable|email:rfc|max:50',
        ], self::validationErrorMessages());

        $contacts = session('contacts', []);
        
        if ($id < 0 || count($contacts) <= $id)
            return Response::json(['success' => 'false', 'errors' => ['message' => self::MSG_ERR_INVIDX]], 400);
        
        $contacts[$id] = [
            'id' => $request->contact_id,
            'type' => ContactType::find($request->contact_type_id)->name,
            'country' => Country::find($request->contact_country_id)->code,
            'name' => $request->contact_name,
            'lastname' => $request->contact_lastname,
            'position' => $request->contact_position,
            'mobile' => $request->contact_mobile,
            'phone' => $request->contact_phone,
            'annex' => $request->contact_annex,
            'email' => $request->contact_email,
        ];
        session(['contacts' => $contacts]);
        return json_encode($contacts);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $contacts = session('contacts', []);

        if ($id < 0 || count($contacts) <= $id)
            return Response::json(['success' => 'false', 'errors' => ['message' => self::MSG_ERR_INVIDX]], 400);
        
        unset($contacts[$id]);
        $contacts = array_values($contacts);
        session(['contacts' => $contacts]);
        return json_encode($contacts);
    }

    /**
     * Get the password reset validation error messages.
     *
     * @return array
     */
    protected function validationErrorMessages()
    {
        return [
            'contact_name.required' => 'Debes ingresar obligatoriamente un nombre.',
            'contact_name.max' => 'El nombre debe tener un máximo de cincuenta (50) caracteres.',
            
            'contact_lastname.required' => 'Debes ingresar obligatoriamente un apellido.',
            'contact_lastname.max' => 'El apellido debe tener un máximo de cincuenta (50) caracteres.',
            
            'contact_position.required' => 'Debes ingresar obligatoriamente un cargo.',
            'contact_position.max' => 'El cargo debe tener un máximo de cien (100) caracteres.',
            
            'contact_type_id.required' => 'Debes seleccionar obligatoriamente un tipo.',
            'contact_type_id.int' => 'El ID del tipo ingresado no tiene un formato válido.',
            'contact_type_id.min' => 'El ID del tipo ingresado es inválido.',

            'contact_country_id.required' => 'Debes seleccionar obligatoriamente un código de país.',
            'contact_country_id.int' => 'El ID del código de país ingresado no tiene un formato válido.',
            'contact_country_id.min' => 'El ID del código de país ingresado es inválido.',

            'contact_mobile.required' => 'Debes ingresar obligatoriamente un celular.',
            'contact_mobile.regex' => 'El celular debe estar compuesto por nueve (9) dígitos.',            
            
            'contact_phone.regex' => 'El teléfono fijo debe estar compuesto por el código de ciudad seguido de seis (6) o siete (7) dígitos.',
            'contact_annex.regex' => 'El anexo debe tener entre cuatro (4) y seis (6) dígitos.',
            
            'contact_email.email' => 'El correo electrónico ingresado no tiene un formato válido.',
            'contact_email.max' => 'El correo electrónico debe tener un máximo de cincuenta (50) caracteres.',
        ];
    }
}