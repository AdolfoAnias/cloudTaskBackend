<?php

namespace App\Services;

use Mail;
use InvalidArgumentException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Facades\Log;
use App\Models\Keyword;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use App\Models\Task;
use App\Http\Resources\KeywordResource;

class KeywordService
{    
    public function findAll()
    {            
        $query = Keyword::query();           
        $query->orderBy('created_at', 'DESC');
        
        return $query->get();        
    }      
    
    public function create($request): KeywordResource 
    {
        $keyword = Keyword::create(
        [
            'name' => $request['name'],
        ]);         

        $keywordData = new KeywordResource($keyword);

        return $keywordData;
    }

    public function update(array $values): KeywordResource
    {
        $keyword = Keyword::where('id', $values['id'])->get();
        $keyword[0]->update([
            'name' => $values['name'],
        ]);             
        
        return new KeywordResource($keyword[0]);
    }   
    
    public function delete($id)
    {
        try{
            $model = Keyword::where('id','=', $id)->delete();    
            
            if ($model) {
                $code = 200;
                $message = 'Keyword eliminado correctamente';
                $data = $model;
            } else {
                $code = 503;
                $message = 'No se pudo eliminar el Keyword: ' . $id;
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

