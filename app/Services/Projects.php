<?php

namespace App\Services;

use App\Project;
use Auth;

class Projects
{
    public function getOpen()
    {
        if (Auth::user()->is_admin) {
            $projects = Project::where('code','not like','OTR000000000%')->whereIn('status',['DEFINIDO','EN EJECUCIÓN','SUSPENDIDO'])->orderBy('name')->get();
        }
        else {
            $projects = Auth::user()->projects->whereIn('status',['DEFINIDO','EN EJECUCIÓN','SUSPENDIDO']);
            $incharge = Project::where('user_id', Auth::user()->id)->whereIn('status',['DEFINIDO','EN EJECUCIÓN','SUSPENDIDO'])->get();
            $projects = $projects->merge($incharge)->sortBy('name');
        }
        $others = Project::where('code','like','OTR000000000%')->orderBy('name')->get();
        $array = [];
        foreach ($projects as $project) {
            $array[$project->id] = $project->nameCode;
        }
        foreach ($others as $other) {
            $array[$other->id] = $other->name;
        }
        return $array;
    }
}