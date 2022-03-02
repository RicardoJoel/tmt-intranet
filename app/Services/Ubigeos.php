<?php

namespace App\Services;

use App\Ubigeo;

class Ubigeos
{
    public function get()
    {
        $ubigeos = Ubigeo::where('department','La Libertad')->orderByRaw('province','district')->get();
        $array = [];
        foreach ($ubigeos as $ubigeo) {
            $array[$ubigeo->id] = $ubigeo->name;
        }
        return $array;
    }
}