<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Contact extends Model
{
    use SoftDeletes;

    protected $table = 'contacts';
    
    protected $fillable = [
        'contact_type_id', 'customer_id', 'country_id', 'name', 'lastname', 'position', 'mobile', 'phone', 'annex', 'email'
    ];

    public function getFullnameAttribute() {
        return $this->lastname.', '.$this->name;
    }

    public function getCodeMobileAttribute() {
        return ($this->country->code ?? '').' '.$this->mobile;
    }

    public function contactType()
    {
    	return $this->belongsTo(ContactType::class);
    }
    
    public function customer()
    {
    	return $this->belongsTo(Customer::class);
    }
        
    public function country()
    {
    	return $this->belongsTo(Country::class);
    }
}
