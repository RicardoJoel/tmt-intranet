<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
    use SoftDeletes;

    protected $table = 'customers';
    
    protected $fillable = [
        'business_id', 'ubigeo_id', 'name', 'alias', 'ruc', 'ref', 'representative', 'address'
    ];
    
    protected $guarded = [
        'id', 'code'
    ];

    public function getNameCodeAttribute() {
        return $this->name.' ('.$this->code.')';
    }
    
    public function getAliasCodeAttribute() {
        return $this->alias.' ('.$this->code.')';
    }

    public function business()
    {
    	return $this->belongsTo(Business::class);
    }

    public function ubigeo()
    {
    	return $this->belongsTo(Ubigeo::class);
    }

    public function contacts()
    {
    	return $this->hasMany(Contact::class);
    }
    
    public function proposals()
    {
    	return $this->hasMany(Proposal::class);
    }
    
    public function projects()
    {
    	return $this->hasMany(Project::class);
    }

    public function visits()
    {
    	return $this->hasMany(Project::class);
    }
    
    protected static function boot()
    {
        parent::boot();
        
        self::creating(function(Customer $supplier) {
            $maxCod = Customer::where('code','like','C'.date('Y').'%')->max(\DB::raw('substr(code,6,3)'));
            $supplier->code = 'C'.date('Y').str_pad(++$maxCod,3,'0',STR_PAD_LEFT);
            return true;
        });
    }
}
