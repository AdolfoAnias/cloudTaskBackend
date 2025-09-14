<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\TaskRequest;
use App\Services\TaskService;
use App\Services\Crypto;
use App\Helpers\ResponseHelper;
use App\Http\Resources\TaskResource;

class TaskController extends Controller
{
    private $service;
    private $crypto;
    
    public function __construct(TaskService $service, Crypto $crypto) 
    {
        $this->service = $service;
        $this->crypto = $crypto;
    }
    
    public function index(Request $request)
    {
        $response =  TaskResource::collection(
            $this->service->findAll()
        )->response()->getData(true);            

        $response = ResponseHelper::returnResponse(200, "Servicio OK", $response);
        //$response = $this->crypto->cryptoJsAesEncrypt(json_encode($response));
        return response()->json($response);               
    }

    public function store(TaskRequest $request)
    {
        try {            
            $data = $this->service->create($request->all());           

            $code = 200;
            $message = "OK";                
            $data = $data;
        } catch (\Exception $e) {
            $code = 422;
            $message = $e->getMessage();
            $data = [];
        }

        $response = ResponseHelper::returnResponse($code, $message, $data);
        //$response = $this->crypto->cryptoJsAesEncrypt(json_encode($response));
        return $response;
    }

    public function update(Request $request)
    {
        try {
            $response = $this->service->update($request->all());

            $response = ResponseHelper::returnResponse(200,"Servicio OK", $response);
            //$response = $this->crypto->cryptoJsAesEncrypt(json_encode($response));
            return response()->json($response);
        } catch (\Throwable $th) {
            $response = ResponseHelper::returnResponse(400, $th->getMessage());
            //$response = $this->crypto->cryptoJsAesEncrypt(json_encode($response));                    
            return response()->json($response, 503);
        }                
    }

    public function destroy($id)
    {
        try {
            $response = $this->service->delete($id);

            $response = ResponseHelper::returnResponse($response['codigoRetorno'], $response['glosaRetorno']);
            //$response = $this->crypto->cryptoJsAesEncrypt(json_encode($response));
            return response()->json($response);
        } catch (\Throwable $th) {
            $response = ResponseHelper::returnResponse(400, $th->getMessage());
            //$response = $this->crypto->cryptoJsAesEncrypt(json_encode($response));                    
            return response()->json($response, 503);
        }        
    }
    
    public function toggle($id)
    {
        try {
            $response = $this->service->toggle($id);

            $response = ResponseHelper::returnResponse($response['codigoRetorno'], $response['glosaRetorno']);
            //$response = $this->crypto->cryptoJsAesEncrypt(json_encode($response));
            return response()->json($response);
        } catch (\Throwable $th) {
            $response = ResponseHelper::returnResponse(400, $th->getMessage());
            //$response = $this->crypto->cryptoJsAesEncrypt(json_encode($response));                    
            return response()->json($response, 503);
        }                
    }
    
}
