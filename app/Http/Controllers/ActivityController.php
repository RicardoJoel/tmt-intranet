<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Activity;
use App\Customer;
use App\Parameter;
use App\Project;
use App\Task;
use App\User;
use Carbon\Carbon;
use Auth;
use DB;
/* Export data */
use App\Exports\InvoicesExport;
use App\Exports\ResumenExport;
use Maatwebsite\Excel\Facades\Excel;

class ActivityController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $dates = Parameter::where('name','DATES')->get()->first()->value;
        $items = Activity::where('user_id',Auth::user()->id)->orderBy('start_at')->get();
        $activities = [];
        foreach ($items as $item) {
            $activities[] = [
                'id' => $item->id,
                'project' => $item->project,
                'start_at' => $item->start_at,
                'end_at' => $item->end_at,
                'description' => $item->description,
                'comment' => $item->comment,
                'finished' => $item->finished,
                'color' => $item->color,
                'task_id' => $item->task_id,
                'tasks' => self::getAncestors($item->project->tasks, $item->task_id)
            ];
        }
        return view('activities.timesheet', compact('dates','activities'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('activities.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $dates = Parameter::where('name','dates')->get()->first()->value;

        $this->validate($request, [
            'project_id' => 'required|int|min:1',
            'task_id' => 'nullable|int|min:1',
            'start_at' => 'required|date|date_format:Y-m-d H:i:s|after_or_equal:'.Carbon::today()->subDays($dates),
            'end_at' => 'required|date|date_format:Y-m-d H:i:s|after:start_at',
            'description' => 'nullable|string|max:500',
            'comment' => 'nullable|string|max:500',
            'color' => 'nullable|string|size:7',
            'finished' => 'required|boolean',
        ], $this->validationErrorMessages());

        return Activity::create($request->all() + ['user_id' => Auth::user()->id]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $activity = Activity::find($id);
        return view('activities.show', compact('activity'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $activity = Activity::find($id);
        return view('activities.edit', compact('activity'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $dates = Parameter::where('name','dates')->get()->first()->value;

        $this->validate($request, [
            'id' => 'required|int|min:1',
            'project_id' => 'required|int|min:1',
            'task_id' => 'nullable|int|min:1',
            'start_at' => 'required|date|date_format:Y-m-d H:i:s|after_or_equal:'.Carbon::today()->subDays($dates),
            'end_at' => 'required|date|date_format:Y-m-d H:i:s|after:start_at',
            'description' => 'nullable|string|max:500',
            'comment' => 'nullable|string|max:500',
            'color' => 'nullable|string|size:7',
            'finished' => 'required|boolean',
        ], $this->validationErrorMessages());

        return Activity::find($request->id)->update($request->all() + ['user_id' => Auth::user()->id]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return Activity::find($id)->delete();
    }

    public function projects(Request $request)
    {
        $customer_id = $request->customer_id;
        $user_id = Auth::user()->is_admin ? $request->user_id : Auth::user()->id;

        $projects = Project::distinct()
        ->select(['projects.id','projects.name','projects.code'])
        ->leftJoin('project_user','project_user.project_id','projects.id')
        ->where('projects.code','not like','OTR000000000%')
        ->whereNull('project_user.deleted_at')
        ->where(function ($query) use ($customer_id) {
            if ($customer_id)
                $query->where('projects.customer_id',$customer_id);
            return $query;
        })
        ->where(function ($query) use ($user_id) {
            if ($user_id)
                $query->where('project_user.user_id',$user_id);
            return $query;
        })
        ->orderByRaw('projects.name','projects.code')
        ->get();
        
        $others = Project::where('code','like','OTR000000000%')->orderBy('name')->get();

        $array = [];
        foreach ($projects as $project)
            $array[] = [
                'id' => $project->id,
                'name' => $project->nameCode
            ];
        foreach ($others as $other)
            $array[] = [
                'id' => $other->id,
                'name' => $other->name
            ];
        return json_encode($array);
    }

    public function status(Request $request)
    {
        $customer_id = $request->customer_id;
        $project_id = $request->project_id;
        $user_id = Auth::user()->is_admin ? $request->user_id : Auth::user()->id;

        $status = Project::distinct()
        ->select([
            DB::raw('projects.status as code'),
            DB::raw('projects.status as name')
        ])
        ->leftJoin('project_user','project_user.project_id','projects.id')
        ->whereNull('project_user.deleted_at')
        ->where(function ($query) use ($customer_id) {
            if ($customer_id)
                $query->where('projects.customer_id',$customer_id);
            return $query;
        })
        ->where(function ($query) use ($project_id) {
            if ($project_id)
                $query->where('projects.id',$project_id);
            return $query;
        })
        ->where(function ($query) use ($user_id) {
            if ($user_id)
                $query->where('project_user.user_id',$user_id);
            return $query;
        })
        ->orderBy('projects.status')
        ->get();

        $array = [];
        foreach ($status as $stat)
            $array[] = [
                'id' => $stat->code,
                'name' => $stat->name
            ];
        return json_encode($array);
    }

    public function users(Request $request)
    {
        $customer_id = $request->customer_id;
        $project_id = $request->project_id;
        $status = $request->status;
        $user_id = Auth::user()->is_admin ? $request->user_id : Auth::user()->id;

        $users = User::distinct()
        ->select(['users.id','users.lastname','users.name'])
        ->leftJoin('project_user','project_user.user_id','users.id')
        ->leftJoin('projects','projects.id','project_user.project_id')
        ->whereNull('project_user.deleted_at')
        ->where('users.id','!=',1)
        ->where(function ($query) use ($customer_id) {
            if ($customer_id)
                $query->where('projects.customer_id',$customer_id);
            return $query;
        })
        ->where(function ($query) use ($project_id) {
            if ($project_id)
                $query->where('projects.id',$project_id);
            return $query;
        })
        ->where(function ($query) use ($status) {
            if ($status)
                $query->where('projects.status',$status);
            return $query;
        })
        ->where(function ($query) use ($user_id) {
            if ($user_id)
                $query->where('project_user.user_id',$user_id);
            return $query;
        })
        ->orderByRaw('users.lastname','users.name')
        ->get();

        $array = [];
        foreach ($users as $user) {
            $array[] = [
                'id' => $user->id,
                'name' => $user->lastname.', '.$user->name
            ];
        }
        return json_encode($array);
    }
    
    public function report()
    {
        if (!Auth::user()->is_admin) 
            return view('activities.report');
        //Si es administrador
        $customer_id = $project_id = $user_id = $status = $title = '';
        $start_at = Carbon::today()->subMonth();
        $end_at = Carbon::today();
        $items = [];
        return view('activities.adminreport', compact('items','title','customer_id','project_id','user_id','start_at','end_at','status'));
    }

    public function generate(Request $request)
    {
        $this->validate($request, [
            'start_at' => 'required|date|before_or_equal:end_at',
            'end_at' => 'required|date|before_or_equal:today',
        ], $this->validationErrorMessages());
        
        ini_set('memory_limit','128M');
        
        $customer_id = $request->customer_id;
        $project_id = $request->project_id;
        $user_id = Auth::user()->is_admin ? $request->user_id : Auth::user()->id;
        $status = $request->status;
        $start_at = Carbon::parse($request->start_at);
        $end_at = Carbon::parse($request->end_at);
        $title = 'Reporte de actividades realizadas '.($start_at == $end_at ? 'el '.$start_at->format('d/m/Y') : 'entre el '.$start_at->format('d/m/Y').' y el '.$end_at->format('d/m/Y'));

        $items = Activity::select([
            DB::raw('concat(customers.name," (",customers.code,")") as customer'),
            DB::raw('if(substr(projects.code,1,12)="OTR000000000",projects.name,concat(projects.name," (",projects.code," • ",projects.status,")")) as project'), 
            DB::raw('concat(users.lastname,", ",users.name) as user'),
            DB::raw('customers.id as customer_id'),
            DB::raw('projects.id as project_id'),
            DB::raw('users.id as user_id'),
            DB::raw('sum(timestampdiff(MINUTE,activities.start_at,activities.end_at)) as minutes')
        ])
        ->leftJoin('projects','projects.id','activities.project_id')
        ->leftJoin('customers','customers.id','projects.customer_id')
        ->leftJoin('users','users.id','activities.user_id')
        //->where('projects.code','not like','OTR000000000%')
        ->where('activities.user_id','!=',1)
        ->where(function ($query) use ($customer_id) {
            if ($customer_id)
                $query->where('projects.customer_id',$customer_id);
            return $query;
        })
        ->where(function ($query) use ($project_id) {
            if ($project_id)
                $query->where('activities.project_id',$project_id);
            return $query;
        })
        ->where(function ($query) use ($user_id) {
            if ($user_id)
                $query->where('activities.user_id',$user_id);
            return $query;
        })
        ->where(function ($query) use ($status) {
            if ($status)
                $query->where('projects.status',$status);
            return $query;
        })
        ->where(function ($query) use ($start_at) {
            if ($start_at)
                $query->where('activities.start_at','>=',$start_at);
            return $query;
        })
        ->where(function ($query) use ($end_at) {
            if ($end_at)
                $query->where('activities.end_at','<=',Carbon::parse($end_at)->addDay());
            return $query;
        })
        ->groupBy([
            DB::raw('concat(customers.name," (",customers.code,")")'), 
            DB::raw('if(substr(projects.code,1,12)="OTR000000000",projects.name,concat(projects.name," (",projects.code," • ",projects.status,")"))'), 
            DB::raw('concat(users.lastname,", ",users.name)'),
            DB::raw('customers.id'),
            DB::raw('projects.id'),
            DB::raw('users.id')
        ])
        ->orderByRaw(
            DB::raw('concat(customers.name," (",customers.code,")")'), 
            DB::raw('if(substr(projects.code,1,12)="OTR000000000",projects.name,concat(projects.name," (",projects.code," • ",projects.status,")"))'), 
            DB::raw('concat(users.lastname,", ",users.name)'),
            DB::raw('customers.id'),
            DB::raw('projects.id'),
            DB::raw('users.id')
        )
        ->get();
        
        return view('activities.adminreport', compact('items','title','customer_id','project_id','user_id','start_at','end_at','status'));
    }
    
    public function getByProjUser(Request $request)
    {
        $this->validate($request, [
            'project_id' => 'required|int|min:1',
            'user_id' => 'required|int|min:1',
            'start_at' => 'required|date|before_or_equal:end_at',
            'end_at' => 'required|date|before_or_equal:today',
        ], $this->validationErrorMessages());

        $user_id = Auth::user()->is_admin ? $request->user_id : Auth::user()->id;

        $activities = Activity::select([
            'start_at','end_at','description','comment',
            DB::raw('timestampdiff(MINUTE,activities.start_at,activities.end_at) as minutes')
        ])
        ->where('project_id',$request->project_id)
        ->where('user_id',$request->user_id)
        ->where('start_at','>=',Carbon::parse($request->start_at))
        ->where('end_at','<=',Carbon::parse($request->end_at)->addDay())
        ->orderBy('start_at')
        ->get();

        return json_encode($activities);
    }

    public function download(Request $request)
    {
        $this->validate($request, [
            'start_at' => 'required|date|before_or_equal:end_at',
            'end_at' => 'required|date|before_or_equal:today',
        ], $this->validationErrorMessages());
        
        ini_set('memory_limit','128M');

        $customer_id = $request->customer_id;
        $project_id = $request->project_id;
        $user_id = Auth::user()->is_admin ? $request->user_id : Auth::user()->id;
        $status = $request->status;
        $start_at = Carbon::parse($request->start_at);
        $end_at = Carbon::parse($request->end_at);

        $items = Activity::select([
            DB::raw('date_format(activities.start_at,"%d/%m/%Y") as date'),
            DB::raw('concat(servers.lastname,", ",servers.name) as server'),
            //DB::raw('concat(customers.name," (",customers.code,")") as customer'),
            DB::raw('departments.name as department'),
            DB::raw('if(substr(projects.code,1,12)="OTR000000000",projects.name,concat(projects.name," (",projects.code,")")) as project'), 
            DB::raw('projects.status as project_status'),
            DB::raw('tasks.name as task'),
            DB::raw('if(activities.finished,"Terminado","No terminado") as task_status'),
            DB::raw('activities.description'),
            DB::raw('concat(managers.lastname,", ",managers.name) as manager'),
            DB::raw('date_format(activities.start_at,"%d/%m/%Y %H:%i") as start_at'),
            DB::raw('date_format(activities.end_at,"%d/%m/%Y %H:%i") as end_at'),
            DB::raw('concat(timestampdiff(HOUR,activities.start_at,activities.end_at),"h ",mod(timestampdiff(MINUTE,activities.start_at,activities.end_at),60),"m") as time'),
            DB::raw('timestampdiff(MINUTE,activities.start_at,activities.end_at) as minutes'),
            DB::raw('activities.comment')
        ])
        ->leftJoin('projects','projects.id','activities.project_id')
        ->leftJoin('tasks','tasks.id','activities.task_id')
        ->leftJoin('customers','customers.id','projects.customer_id')
        ->leftJoin('users as managers','managers.id','projects.user_id')
        ->leftJoin('users as servers','servers.id','activities.user_id')
        ->leftJoin('departments','departments.id','servers.department_id')
        //->where('projects.code','not like','OTR000000000%')
        ->where('activities.user_id','!=',1)
        ->where(function ($query) use ($customer_id) {
            if ($customer_id)
                $query->where('projects.customer_id',$customer_id);
            return $query;
        })
        ->where(function ($query) use ($project_id) {
            if ($project_id)
                $query->where('activities.project_id',$project_id);
            return $query;
        })
        ->where(function ($query) use ($user_id) {
            if ($user_id)
                $query->where('activities.user_id',$user_id);
            return $query;
        })
        ->where(function ($query) use ($status) {
            if ($status)
                $query->where('projects.status',$status);
            return $query;
        })
        ->where(function ($query) use ($start_at) {
            if ($start_at)
                $query->where('activities.start_at','>=',$start_at);
            return $query;
        })
        ->where(function ($query) use ($end_at) {
            if ($end_at)
                $query->where('activities.end_at','<=',Carbon::parse($end_at)->addDay());
            return $query;
        })
        ->orderByRaw(
            DB::raw('date_format(activities.start_at,"%Y/%m/%d")'),
            DB::raw('concat(servers.lastname,", ",servers.name)'),
            //DB::raw('customers.name'),
            DB::raw('departments.name'),
            DB::raw('projects.name'), 
            DB::raw('projects.status'),
            DB::raw('tasks.name'),
            DB::raw('activities.finished')
        )
        ->get();
        
        $customer = $customer_id ? Customer::find($customer_id) : null;
        $project = $project_id ? Project::find($project_id) : null;
        $user = $user_id ? User::find($user_id) : null;
        //$customer = $customer ? $customer->name.' ('.$customer->code.')' : 'Todos';
        $project = $project ? $project->name.' ('.$project->code.')' : 'Todos';
        $user = $user ? $user->lastname.', '.$user->name : 'Todos';
        $start_at = $start_at->format('d/m/Y');
        $end_at = $end_at->format('d/m/Y');
        $status = $status ? $request->status : 'Todos';

        $export = new InvoicesExport($items->toArray(), /*$customer,*/ $project, $user, $status, $start_at, $end_at);
        return Excel::download($export,'Reporte_tiempos_'.Carbon::today()->format('d-m-Y').'.xlsx');
    }

    public function descargar(Request $request)
    {
        $this->validate($request, [
            'start_at' => 'required|date|before_or_equal:end_at',
            'end_at' => 'required|date|before_or_equal:today',
        ], $this->validationErrorMessages());
        
        ini_set('memory_limit','128M');

        $project_id = $request->project_id;
        $status = $request->status;
        $start_at = Carbon::parse($request->start_at);
        $end_at = Carbon::parse($request->end_at);
        
        $items = Activity::select([
            DB::raw('date_format(activities.start_at,"%d-%b") as date'),
            DB::raw('date_format(activities.start_at,"%H:%i") as start_at'),
            DB::raw('date_format(activities.end_at,"%H:%i") as end_at'),
            DB::raw('concat(if(timestampdiff(HOUR,activities.start_at,activities.end_at)>0,concat(timestampdiff(HOUR,activities.start_at,activities.end_at),"h "),""),if(mod(timestampdiff(MINUTE,activities.start_at,activities.end_at),60)>0,concat(mod(timestampdiff(MINUTE,activities.start_at,activities.end_at),60),"m"),"")) as time'),
            DB::raw('projects.name as project'), 
            DB::raw('tasks.name as task'),
            DB::raw('activities.description as activity'),
            DB::raw('if(activities.finished,"Sí","No") as status'),
            DB::raw('timestampdiff(MINUTE,activities.start_at,activities.end_at) as minutes'),
        ])
        ->leftJoin('projects','projects.id','activities.project_id')
        ->leftJoin('tasks','tasks.id','activities.task_id')
        ->where('activities.user_id',Auth::user()->id)
        ->where('activities.start_at','>=',$start_at)
        ->where('activities.end_at','<=',Carbon::parse($end_at)->addDay())
        ->where(function ($query) use ($project_id) {
            if ($project_id)
                $query->where('activities.project_id',$project_id);
            return $query;
        })
        ->where(function ($query) use ($status) {
            if ($status)
                $query->where('projects.status',$status);
            return $query;
        })
        ->orderByRaw(
            DB::raw('activities.start_at'),
            DB::raw('projects.name'), 
            DB::raw('tasks.name'),
            DB::raw('activities.finished'),
        )
        ->get();

        $total = 0;
        foreach ($items as $item)
            $total += $item->minutes;

        $area = Auth::user()->department->name ?? '';
        $user = Auth::user()->fullname;
        $week = $start_at->format('d M').' - '.$end_at->format('d M');
        $hour = floor($total / 60);
        $minu = $total % 60;
        $time = ($hour ? $hour.'h ' : '').($minu ? $minu.'m' : '');

        $items = $items->map(function ($item) {
            return collect($item)->except(['minutes']);
        });

        $export = new ResumenExport($items->toArray(), $area, $user, $week, $time);
        return Excel::download($export,'Reporte_tiempos_'.Carbon::today()->format('d-m-Y').'.xlsx');
    }

    protected function getAncestors($tasks, $id)
    {
        $bros = [];
        $parent = null;
        $found = false;
        // condición de parada
        $me = Task::find($id);
        if (!$me || !$me->level || !count($tasks)) return [];
        if ($me->level == 1) {
            foreach ($tasks as $task) {
                if ($task->level == $me->level)
                    $bros[] = [
                        'id' => $task->id,
                        'name' => $task->code.' '.$task->name
                    ];
            }
            $anc[$me->level] = [
                'options' => $bros,
                'selected' => $id
            ];
            return $anc;
        }
        // parte recursiva
        foreach ($tasks as $task) {
            if ($task->level == $me->level) {
                $bros[] = [
                    'id' => $task->id,
                    'name' => $task->code.' '.$task->name
                ];
                if ($task->id == $id) $found = true;
            }
            else if ($task->level < $me->level && !$found) {
                $bros = [];
                $parent = $task->id;
            }
            else if ($task->level < $me->level) break;
        }
        $anc = self::getAncestors($tasks, $parent);
        $anc[$me->level] = [
            'options' => $bros,
            'selected' => $id
        ];
        return $anc;
    }

    /**
     * Get the password reset validation error messages.
     *
     * @return array
     */
    protected function validationErrorMessages()
    {
        $dates = Parameter::where('name','dates')->get()->first()->value;
        return [
            'start_at.required' => 'Debe ingresar una fecha inicial.',
            'start_at.after_or_equal' => 'La fecha de la actividad no puede ser anterior a los '.$dates.' calendario.',
            'end_at.required' => 'Debe ingresar una fecha final.',
            'end_at.after' => 'La hora de inicio no puede ser anterior a la hora final.'
        ];
    }
}
