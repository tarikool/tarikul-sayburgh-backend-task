<?php

namespace App\Http\Requests;


use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;


class ApiRequest extends FormRequest
{

    protected function failedValidation(Validator $validator)
    {
        $response = new Response(array(
            'status' => 0,
            'errors' => collect($validator->errors())->collapse()
        ), 422);

        throw new ValidationException($validator, $response);
    }

}
