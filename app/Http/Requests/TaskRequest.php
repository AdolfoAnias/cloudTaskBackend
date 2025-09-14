<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
use Carbon\Carbon;

class TaskRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'is_done' => 'nullable|boolean',
            'keyword_ids' => 'nullable|array',
            'keyword_ids.*' => 'exists:keywords,id',            
        ];
    }
    
    public function messages()
    {
        return [
            'title.required' => 'El campo título es obligatorio.',
            // otros mensajes si quieres
        ];
    }    

    protected function failedValidation(Validator $validator)
    {
        $response = response()->json([
            'codigoRetorno' => 422,
            'glosaRetorno' => 'Error de validación',
            'timestamp' => [
                'date' => Carbon::now('UTC')->toDateTimeString(),
                'timezone_type' => 3,
                'timezone' => 'UTC'
            ],
            'respuesta' => $validator->errors(),
        ], 422);

        throw new HttpResponseException($response);
    }    
}
