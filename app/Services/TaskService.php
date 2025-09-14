<?php

namespace App\Services;

use Mail;
use InvalidArgumentException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Facades\Log;
use App\Models\Task;
use App\Http\Resources\TaskResource;
use Carbon\Carbon;

class TaskService
{        
    public function findAll()
    {            
        $query = Task::query();           
        $query->orderBy('created_at', 'DESC');
        
        return $query->get();        
    }      
    
    public function create($request): TaskResource 
    {
        $task = Task::create(
        [
            'title' => $request['title'],
            'is_done' => false
        ]);         

        // Asignar palabras clave existentes por IDs
        if (array_key_exists('keywords', $request)) {
            $task->keywords()->sync($request['keywords']);
        }            

        $taskData = new TaskResource($task);

        return $taskData;
    }

    public function update(array $values): TaskResource
    {
        $task = Task::find($values['id']);  // obtener un solo modelo, no get()

        if ($task) {
            $task->update([
                'title' => $values['title'],
                'is_done' => $values['is_done'],
            ]);

            if (array_key_exists('keywords', $values) && is_array($values['keywords'])) {
                $task->keywords()->sync($values['keywords']);
            }
        }        
        
        return new TaskResource($task);
    }
    
    public function toggle($id)
    {
        try{
            $task = Task::findOrFail($id);
            $task->is_done = !$task->is_done;
            $task->save();            
            
            if ($task) {
                $code = 200;
                $message = 'Cambio de estado en Tarea correctamente';
                $data = $task;
            } else {
                $code = 503;
                $message = 'No se pudo cambiar el estado de la tarea: ' . $id;
                $data = [];
            }            
        } catch (Exception $ex) {
            $code    = 503;
            $message = $e->getMessage();
            $data = [];
        }    
        
        $response = [
            "codigoRetorno" => $code,
            "glosaRetorno"  => $message,
            "timestamp"     => new \DateTime('NOW'),
            "respuesta"     => $data,
        ];
        return $response;      
    }
    
    public function delete($id)
    {
        try{
            $model = Task::where('id','=', $id)->delete();    
            
            if ($model) {
                $code = 200;
                $message = 'Tarea eliminada correctamente';
                $data = $model;
            } else {
                $code = 503;
                $message = 'No se pudo eliminar la tarea: ' . $id;
                $data = [];
            }            
        } catch (Exception $ex) {
            $code    = 503;
            $message = $e->getMessage();
            $data = [];
        }    
        
        $response = [
            "codigoRetorno" => $code,
            "glosaRetorno"  => $message,
            "timestamp"     => new \DateTime('NOW'),
            "respuesta"     => $data,
        ];
        return $response;
    }      
}
