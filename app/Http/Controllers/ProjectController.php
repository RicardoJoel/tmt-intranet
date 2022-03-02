<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Proposal;
use App\Project;
use App\Task;
use App\User;
use Carbon\Carbon;
use Redirect;
use Response;
use DB;

class ProjectController extends Controller
{
    protected const MSG_SCS_CRTPRJ = 'El proyecto con código <b>value</b> ha sido registrado de manera exitosa.';
    protected const MSG_ERR_CRTPRJ = 'Lo sentimos, ocurrió un problema mientras se intentaba registrar el proyecto.';
    protected const MSG_SCS_UPDPRJ = 'El proyecto con código <b>value</b> ha sido actualizado de manera exitosa.';
    protected const MSG_ERR_UPDPRJ = 'Lo sentimos, ocurrió un problema mientras se intentaba actualizar el proyecto.';
    protected const MSG_SCS_DLTPRJ = 'El proyecto con código <b>value</b> ha sido eliminado de manera exitosa.';
    protected const MSG_ERR_DLTPRJ = 'Lo sentimos, ocurrió un problema mientras se intentaba eliminar el proyecto.';
    protected const MSG_NOT_FNDPRJ = 'El proyecto solicitado no ha sido encontrado.';

    protected const MSG_NOT_FNDPRP = 'La propuesta solicitada no ha sido encontrada.';
    protected const MSG_ERR_DIFCST = 'No puedes cambiar el cliente de la propuesta seleccionada.';
    protected const MSG_ERR_CNTPRJ = 'La propuesta seleccionada ya tiene un proyecto asociado.';
    protected const MSG_ERR_GTDATE = 'La fecha de inicio del proyecto no puede ser anterior a la fecha de propuesta.';
    protected const MSG_SCS_CLOSED = 'El estado del proyecto con código <b>value</b> ha sido cambiado a COMPLETADO.';
    protected const MSG_SCS_CANCEL = 'El estado del proyecto con código <b>value</b> ha sido cambiado a CANCELADO.';
    protected const MSG_ERR_STATUS = 'Lo sentimos, ocurrió un problema mientras se intentaba actualizar el estado del proyecto.';
    protected const MSG_NOT_EDITAB = 'El proyecto solicitado tiene el estado value y no puede ser editado.';
    protected const MSG_NOT_DELETE = 'El proyecto solicitado tiene el estado value y no puede ser eliminado.';

    protected const MSG_SCS_CRTTSK = 'La tarea con código <b>value</b> ha sido registrada de manera exitosa.';
    protected const MSG_ERR_CRTTSK = 'Lo sentimos, ocurrió un problema mientras se intentaba registrar una tarea.';
    protected const MSG_SCS_UPDTSK = 'La tarea con código <b>value</b> ha sido actualizada de manera exitosa.';
    protected const MSG_ERR_UPDTSK = 'Lo sentimos, ocurrió un problema mientras se intentaba actualizar una tarea.';
    protected const MSG_SCS_DLTTSK = 'La tarea con código <b>value</b> ha sido eliminada de manera exitosa.';
    protected const MSG_ERR_DLTTSK = 'Lo sentimos, ocurrió un problema mientras se intentaba eliminar una tarea.';
    protected const MSG_NOT_FNDTSK = 'La tarea solicitada no ha sido encontrada.';

    protected const MSG_SCS_CRTRSC = 'El recurso con código <b>value</b> ha sido registrado de manera exitosa.';
    protected const MSG_ERR_CRTRSC = 'Lo sentimos, ocurrió un problema mientras se intentaba registrar un recurso.';
    protected const MSG_SCS_UPDRSC = 'El recurso con código <b>value</b> ha sido actualizado de manera exitosa.';
    protected const MSG_ERR_UPDRSC = 'Lo sentimos, ocurrió un problema mientras se intentaba actualizar un recurso.';
    protected const MSG_SCS_DLTRSC = 'El recurso con código <b>value</b> ha sido eliminado de manera exitosa.';
    protected const MSG_ERR_DLTRSC = 'Lo sentimos, ocurrió un problema mientras se intentaba eliminar un recurso.';
    protected const MSG_NOT_FNDRSC = 'El recurso solicitado no ha sido encontrado.';

    protected const STATUS_PROJ = 'DEFINIDO,EN EJECUCIÓN,SUSPENDIDO,CANCELADO,COMPLETADO';
    protected const STATUS_TASK = 'STATUS_ACTIVE,STATUS_DONE,STATUS_FAILED,STATUS_SUSPENDED,STATUS_WAITING,STATUS_UNDEFINED';
    protected const DEPEND_REGX = '/^\d+(?:[ \t]*:[ \t]*\d+)?(?:[ \t]*,[ \t]*\d+(?:[ \t]*:[ \t]*\d+)?)*$/';
    protected const ASSIGN_ROLE = [
        ['id' => 'tmp_1', 'name' => 'Resp. Proyecto'],
        ['id' => 'tmp_2', 'name' => 'Servidor'],
        ['id' => 'tmp_3', 'name' => 'Stakeholder'],
        ['id' => 'tmp_4', 'name' => 'Cliente']
    ];

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $projects = Project::where('code','not like','OTR000000000%')->orderBy('code')->paginate(1000000);
        return view('projects.index', compact('projects'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $tasks[] = [
            'id' => 0,
            'row' => 0,
            'code' => 1,
            'name' => 'Nombre del proyecto',
            'start_at' => Carbon::tomorrow()->toDateString(),
            'end_at' => Carbon::tomorrow()->toDateString(),
            'start_ms' => false,
            'end_ms' => false,
            'status' => 'STATUS_UNDEFINED',
            'progress' => 0,
            'description' => null,
            'relevance' => 0,
            'duration' => 1,
            'depends' => null,
            'level' => 0,
            'progressByWorklog' => false, 
            'type' => '', 
            'typeId' => '', 
            'canWrite' => true, 
            'collapsed' => false, 
            'assigs' => [], 
            'hasChild' => true
        ];
        $resources = [];
        $roles = self::ASSIGN_ROLE;
        return view('projects.create', compact('tasks','resources','roles'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //Validación de campos del proyecto
        self::validate($request, [
            'proposal_id' => 'nullable|int|min:1',
            'code' => 'required|string|unique:projects,code,NULL,id,deleted_at,NULL|regex:/[A-Za-z0-9]{15}/',
            'happen_at' => 'required|date_format:Y-m-d',
            'status' => 'required|string|in:'.self::STATUS_PROJ,
            'customer_id' => 'required|int|min:1',
            'user_id' => 'required|int|min:1',
            'project_type_id' => 'required|int|min:1',
            'name' => 'required|string|max:200',
            'tasks' => 'required|array',
        ], self::validationErrorMessages());

        //Validación de campos de cada tarea
        foreach ($request->tasks as $task)
            Validator::make($task, [
                'row' => 'nullable|int|min:0',
                'name' => 'nullable|string|max:200',
                'code' => 'nullable|string|max:20',
                'level' => 'required|int|min:0',
                'start_at' => 'required|date_format:Y-m-d',
                'end_at' => 'required|date_format:Y-m-d|after_or_equal:start_at',
                'start_ms' => 'required|boolean',
                'end_ms' => 'required|boolean',
                'status' => 'required|string|in:'.self::STATUS_TASK,
                'relevance' => 'required|int|min:0',
                'duration' => 'required|int|min:1',
                'progress' => 'required|numeric|between:0,100',
                'description' => 'nullable|string|max:500',
                'depends' => 'nullable|string|max:100|regex:'.self::DEPEND_REGX
            ], self::validationErrorMsgTask());
        
        //validaciones del proyecto
        if ($request->proposal_id) {
            $proposal = Proposal::find($request->proposal_id);

            if (!$proposal)
                return Response::json(['success' => 'false', 'errors' => ['message' => self::MSG_NOT_FNDPRP]], 400);
            
            if (count($proposal->projects))
                return Response::json(['success' => 'false', 'errors' => ['message' => self::MSG_ERR_CNTPRJ]], 400);

            if ($proposal->customer_id != $request->customer_id)
                return Response::json(['success' => 'false', 'errors' => ['message' => self::MSG_ERR_DIFCST]], 400);
            
            if (Carbon::parse($proposal->happen_at)->gt(Carbon::parse($request->happen_at)))
                return Response::json(['success' => 'false', 'errors' => ['message' => self::MSG_ERR_GTDATE]], 400);
        }
        //registro del proyecto
        $project = Project::create($request->all());
        if (!$project)
            return Response::json(['success' => 'false', 'errors' => ['message' => self::MSG_ERR_CRTPRJ]], 400);
        
        //registro de tareas
        foreach ($request->tasks as $index => $task)
            if (!Task::create(collect($task)->all() + ['row' => $index, 'project_id' => $project->id]))
                return Response::json(['success' => 'false', 'errors' => ['message' => self::MSG_ERR_CRTTSK]], 400);
        
        //registro de recursos
        $resources = session('resources', []);
        foreach ($resources as $rsc) {
            $user = User::find($rsc['id']);
            if (!$user)
                return Response::json(['success' => 'false', 'errors' => ['message' => self::MSG_NOT_FNDRSC]], 400);
            $project->resources()->attach($user);
        }
        session()->forget('resources');
    
        //retorno exitoso
        return Response::json(['success' => 'true', 'message' => str_replace('value', $project->code, self::MSG_SCS_CRTPRJ), 'id' => $project->id], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $project = Project::find($id);
        
        if (!$project)
            return Redirect::back()->with('error', self::MSG_NOT_FNDPRJ);
        
        $tasks = $resources = $assignments = [];
        foreach ($project->tasks as $task)
            $tasks[] = [
                'id' => $task->id,
                'code' => $task->code,
                'name' => $task->name,
                'start_at' => $task->start_at,
                'end_at' => $task->end_at,
                'start_ms' => $task->start_ms,
                'end_ms' => $task->end_ms,
                'status' => $task->status,
                'progress' => $task->progress,
                'description' => $task->description,
                'relevance' => $task->relevance,
                'duration' => $task->duration,
                'depends' => $task->depends,
                'level' => $task->level,
                'progressByWorklog' => false, 
                'type' => '', 
                'typeId' => '', 
                'canWrite' => true, 
                'collapsed' => false, 
                'assigs' => [], 
                'hasChild' => true
            ];
        foreach ($project->resources as $rsc)
            $resources[] = [
                'id' => $rsc->id,
                'name' => $rsc->fullname
            ];
        $roles = self::ASSIGN_ROLE;
        return view('projects.show', compact('project','tasks','resources','assignments','roles'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $project = Project::find($id);
        
        if (!$project)
            return Redirect::back()->with('error', self::MSG_NOT_FNDPRJ);
        
        if (!in_array($project->status, ['DEFINIDO','EN EJECUCIÓN','SUSPENDIDO']))
            return Redirect::back()->with('error', str_replace('value', $project->status, self::MSG_NOT_EDITAB));

        $tasks = $resources = [];
        foreach ($project->tasks as $task)
            $tasks[] = [
                'id' => $task->id,
                'row' => $task->row,
                'code' => $task->code,
                'name' => $task->name,
                'start_at' => $task->start_at,
                'end_at' => $task->end_at,
                'start_ms' => $task->start_ms,
                'end_ms' => $task->end_ms,
                'status' => $task->status,
                'progress' => $task->progress,
                'description' => $task->description,
                'relevance' => $task->relevance,
                'duration' => $task->duration,
                'depends' => $task->depends,
                'level' => $task->level,
                'progressByWorklog' => false, 
                'type' => '', 
                'typeId' => '', 
                'canWrite' => true, 
                'collapsed' => false, 
                'assigs' => [], 
                'hasChild' => true
            ];
        foreach ($project->resources as $rsc)
            $resources[] = [
                'id' => $rsc->id,
                'name' => $rsc->fullname
            ];
        $roles = self::ASSIGN_ROLE;
        session(['resources' => $resources]);
        return view('projects.edit', compact('project','tasks','resources','roles'));
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
        //Validación de campos del proyecto
        self::validate($request, [
            'proposal_id' => 'nullable|int|min:1',
            'code' => 'required|string|unique:projects,code,'.$id.',id,deleted_at,NULL|regex:/[A-Za-z0-9]{15}/',
            'happen_at' => 'required|date_format:Y-m-d',
            'status' => 'required|string|in:'.self::STATUS_PROJ,
            'customer_id' => 'required|int|min:1',
            'user_id' => 'required|int|min:1',
            'project_type_id' => 'required|int|min:1',
            'name' => 'required|string|max:200',
            'tasks' => 'required|array',
        ], self::validationErrorMessages());

        //Validación de campos de cada tarea
        foreach ($request->tasks as $task)
            Validator::make($task, [
                'row' => 'nullable|int|min:0',
                'name' => 'nullable|string|max:200',
                'code' => 'nullable|string|max:20',
                'level' => 'required|int|min:0',
                'start_at' => 'required|date_format:Y-m-d',
                'end_at' => 'required|date_format:Y-m-d|after_or_equal:start_at',
                'start_ms' => 'required|boolean',
                'end_ms' => 'required|boolean',
                'status' => 'required|string|in:'.self::STATUS_TASK,
                'relevance' => 'required|int|min:0',
                'duration' => 'required|int|min:1',
                'progress' => 'required|numeric|between:0,100',
                'description' => 'nullable|string|max:500',
                'depends' => 'nullable|string|max:100|regex:'.self::DEPEND_REGX
            ], self::validationErrorMsgTask());

        //búsqueda del proyecto
        $project = Project::find($id);
        if (!$project)
            return Response::json(['success' => 'false', 'errors' => ['message' => self::MSG_NOT_FNDPRJ]], 400);

        //validaciones del proyecto
        if (!in_array($project->status, ['DEFINIDO','EN EJECUCIÓN','SUSPENDIDO']))
            return Response::json(['success' => 'false', 'errors' => ['message' => str_replace('value', $project->status, self::MSG_NOT_EDITAB)]], 400);

        if ($request->proposal_id) {
            $proposal = Proposal::find($request->proposal_id);

            if (!$proposal)
                return Response::json(['success' => 'false', 'errors' => ['message' => self::MSG_NOT_FNDPRP]], 400);

            if (count($proposal->projects->where('id','!=',$id)))
                return Response::json(['success' => 'false', 'errors' => ['message' => self::MSG_ERR_CNTPRJ]], 400);

            if ($proposal->customer_id != $request->customer_id)
                return Response::json(['success' => 'false', 'errors' => ['message' => self::MSG_ERR_DIFCST]], 400);

            if (Carbon::parse($proposal->happen_at)->gt(Carbon::parse($request->happen_at)))
                return Response::json(['success' => 'false', 'errors' => ['message' => self::MSG_ERR_GTDATE]], 400);
        }
        //actualización del proyecto
        if (!$project->update($request->all()))
            return Response::json(['success' => 'false', 'errors' => ['message' => self::MSG_ERR_UPDPRJ]], 400);
        
        //eliminación de tareas que no figuran en memoria
        foreach ($project->tasks as $task)
            if (!self::inArray($task->id, $request->tasks))
                $task->delete();
        
        //registro de tareas que no figuran en la BD
        foreach ($request->tasks as $index => $task)
            if (substr($task['id'],0,4) !== 'tmp_') { //tarea actualmente registrada
                if (!Task::find($task['id'])->update(collect($task)->all() + ['row' => $index, 'project_id' => $project->id]))
                    return Response::json(['success' => 'false', 'errors' => ['message' => self::MSG_ERR_UPDTSK]], 400);
            }
            else //tarea sin registrar
                if (!Task::create(collect($task)->all() + ['row' => $index, 'project_id' => $project->id]))
                    return Response::json(['success' => 'false', 'errors' => ['message' => self::MSG_ERR_CRTTSK]], 400);

        //eliminación de recursos que no figuran en memoria
        $resources = session('resources', []);
        foreach ($project->resources as $rsc)
            if (!self::inArray($rsc->id, $resources))
                DB::table('project_user')
                    ->where('project_id', $project->id)
                    ->where('user_id', $rsc->id)
                    ->update(['deleted_at' => DB::raw('now()')]);
        
        //registro de recursos que no figuran en la BD
        foreach ($resources as $rsc)
            if (!self::inArray($rsc['id'], $project->resources)) {                
                $user = User::find($rsc['id']);
                if (!$user)
                    return Response::json(['success' => 'false', 'errors' => ['message' => self::MSG_NOT_FNDRSC]], 400);
                $project->resources()->attach($user);
            }
        session()->forget('resources');
        
        //retorno exitoso
        return Response::json(['success' => 'true', 'message' => str_replace('value', $project->code, self::MSG_SCS_UPDPRJ), 'id' => $project->id], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {     
        $project = Project::find($id);

        if (!$project)
            return Redirect::back()->with('error', self::MSG_NOT_FNDPRJ);
        
        if (!in_array($project->status, ['DEFINIDO','EN EJECUCIÓN','SUSPENDIDO']))
            return Redirect::back()->with('error', str_replace('value', $project->status, self::MSG_NOT_DELETE));
        
        foreach ($project->resources as $rsc)
            if (!DB::table('project_user')
                    ->where('project_id', $project->id)
                    ->where('user_id', $rsc->id)
                    ->update(['deleted_at' => DB::raw('now()')]))
                return Redirect::back()->with('error', self::MSG_ERR_DLTRSC);
        
        foreach ($project->tasks as $task)
            if (!$task->delete())
                return Redirect::back()->with('error', self::MSG_ERR_DLTTSK);

        if (!$project->delete())
            return Redirect::back()->with('error', self::MSG_ERR_DLTPRJ);

        return Redirect::route('projects.index')->with('success', str_replace('value', $project->code, self::MSG_SCS_DLTPRJ));
    }

    public function close(Request $request, $id)
    {
        self::validate($request, [
            'changed_at' => 'required|date_format:Y-m-d|before_or_equal:today',            
        ], self::validationErrorMessages());

        $project = Project::find($id);

        if (!$project)
            return Redirect::back()->with('error', self::MSG_NOT_FNDPRJ);
            
        if (!in_array($project->status, ['DEFINIDO','EN EJECUCIÓN','SUSPENDIDO']))
            return Redirect::back()->with('error', str_replace('value', $project->status, self::MSG_NOT_EDITAB));

        if (!$project->update(['status' => 'COMPLETADO', 'changed_at' => $request->changed_at]))
            return Redirect::back()->with('error', self::MSG_ERR_STATUS);

        return Redirect::route('projects.index')->with('success', str_replace('value', $project->code, self::MSG_SCS_CLOSED));
    }
    
    public function cancel(Request $request, $id)
    {
        self::validate($request, [
            'changed_at' => 'required|date_format:Y-m-d|before_or_equal:today',            
        ], self::validationErrorMessages());

        $project = Project::find($id);

        if (!$project)
            return Redirect::back()->with('error', self::MSG_NOT_FNDPRJ);

        if (!in_array($project->status, ['DEFINIDO','EN EJECUCIÓN','SUSPENDIDO']))
            return Redirect::back()->with('error', str_replace('value', $project->status, self::MSG_NOT_EDITAB));

        if (!$project->update(['status' => 'CANCELADO', 'changed_at' => $request->changed_at]))
            return Redirect::back()->with('error', self::MSG_ERR_STATUS);

        return Redirect::route('projects.index')->with('success', str_replace('value', $project->code, self::MSG_SCS_CANCEL));
    }

    /**
     * Get the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function get($id)
    {
        $project = Project::find($id);
        
        if (!$project) 
            return Response::json(['success' => 'false', 'errors' => ['message' => self::MSG_NOT_FNDPRJ]], 400);
        
        $tasks = [];
        foreach ($project->tasks->where('level',1) as $task)
            $tasks[] = [
                'id' => $task->id,
                'name' => $task->code.' '.$task->name
            ];
        return [
            'project' => $project,
            'tasks' => $tasks
        ];
    }
    
    /**
     * Get the password reset validation error messages.
     *
     * @return array
     */
    protected function validationErrorMessages()
    {
        return [
            'proposal_id.int' => 'El ID de la propuesta ingresada no tiene un formato válido.',
            'proposal_id.min' => 'El ID de la propuesta ingresada es inválido.',

            'code.required' => 'Debes ingresar obligatoriamente un código de proyecto.',
            'code.unique' => 'El código de proyecto ingresado ya existe en el sistema.',
            'code.regex' => 'El código de proyecto debe estar compuesto por quince (15) caracteres alfanuméricos.',

            'happen_at.required' => 'Debes ingresar obligatoriamente una fecha de inicio.',
            'happen_at.date_format' => 'La fecha de inicio ingresada no tiene un formato válido.',

            'status.required' => 'Debes ingresar obligatoriamente un estado de proyecto.',
            'status.in' => 'El estado de proyecto ingresado no es válido.',

            'customer_id.required' => 'Debes ingresar obligatoriamente un cliente.',
            'customer_id.int' => 'El ID del cliente ingresado no tiene un formato válido.',
            'customer_id.min' => 'El ID del cliente ingresado es inválido.',
            
            'user_id.required' => 'Debes ingresar obligatoriamente un responsable.',
            'user_id.int' => 'El ID del responsable ingresado no tiene un formato válido.',
            'user_id.min' => 'El ID del responsable ingresado es inválido.',

            'project_type_id.required' => 'Debes ingresar obligatoriamente un tipo.',
            'project_type_id.int' => 'El ID del tipo ingresado no tiene un formato válido.',
            'project_type_id.min' => 'El ID del tipo ingresado es inválido.',
            
            'name.required' => 'Debes ingresar obligatoriamente un nombre de proyecto.',
            'name.max' => 'El nombre de la proyecto debe tener un máximo de doscientos (200) caracteres.',

            'changed_at.required' => 'Debes ingresar obligatoriamente una fecha de cambio de estado.',
            'changed_at.date_format' => 'La fecha de cambio de estado ingresada no tiene un formato válido.',
            'changed_at.before_or_equal' => 'La fecha de cambio de estado no puede ser posterior a la fecha actual.', 
        ];
    }

    /**
     * Get the password reset validation error messages.
     *
     * @return array
     */
    protected function validationErrorMsgTask()
    {
        return [
            'tsk_row.int' => 'La fila ingresada no tiene un formato válido.',
            'tsk_row.min' => 'La fila debe ser un entero no negativo.',

            'tsk_name.required' => 'Debes ingresar obligatoriamente un nombre.',
            'tsk_name.max' => 'El nombre debe tener un máximo de doscientos (200) caracteres.',

            'tsk_code.max' => 'El código debe tener un máximo de veinte (20) caracteres.',

            'tsk_start_at.required' => 'Debes ingresar obligatoriamente una fecha inicio.',
            'tsk_start_at.date_format' => 'La fecha inicio ingresada no tiene un formato válido.',

            'tsk_end_at.required' => 'Debes ingresar obligatoriamente una fecha fin.',
            'tsk_end_at.date_format' => 'La fecha fin ingresada no tiene un formato válido.',
            'tsk_end_at.after_or_equal' => 'La fecha inicio no puede ser posterior a la fecha fin.',

            'tsk_start_ms.required' => 'Debes indicar obligatoriamente si la fecha inicio es un hito.',
            'tsk_start_ms.boolean' => 'El indicador si la fecha inicio es un hito no tiene un formato válido.',

            'tsk_end_ms.required' => 'Debes indicar obligatoriamente si la fecha fin es un hito.',
            'tsk_end_ms.boolean' => 'El indicador si la fecha fin es un hito no tiene un formato válido.',

            'tsk_status.required' => 'Debes ingresar obligatoriamente un estado.',
            'tsk_status.in' => 'El estado ingresado no es válido.',

            'tsk_progress.required' => 'Debes ingresar obligatoriamente un progreso.',
            'tsk_progress.numeric' => 'El progreso ingresado no tiene un formato válido.',
            'tsk_progress.between' => 'El progreso debe estar comprendido entre cero (0) y cien (100).',

            'tsk_description.max' => 'La descripción debe tener un máximo de quinientos (500) caracteres.',

            'tsk_relevance.required' => 'Debes ingresar obligatoriamente una prioridad.',
            'tsk_relevance.int' => 'La prioridad ingresada no tiene un formato válido.',
            'tsk_relevance.min' => 'La prioridad debe ser un entero no negativo.',

            'tsk_duration.required' => 'Debes ingresar obligatoriamente una duración.',
            'tsk_duration.int' => 'La duración ingresada no tiene un formato válido.',
            'tsk_duration.min' => 'La duración debe ser un entero positivo.',

            'tsk_level.required' => 'Debes ingresar obligatoriamente un nivel.',
            'tsk_level.int' => 'El nivel ingresado no tiene un formato válido.',
            'tsk_level.min' => 'El nivel debe ser un entero no negativo.',

            'tsk_depends.max' => 'La lista de tareas predecesoras debe tener un máximo de cien (100) caracteres.',
            'tsk_depends.regex' => 'La lista de tareas predecesoras debe estar conformada por números separados por comas.',
        ];
    }
}