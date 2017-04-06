<?php

namespace App\Http\Requests;

use Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Foundation\Http\FormRequest;

class SendFormRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public static function rules($type)
    {
        $rules = [
            'username' => 'required|alpha_dash',
            'password' => 'required|min:8',
        ];

        if($type == 'create'){
            $rules['zip'] = 'required|file|mimetypes:application/zip|max:10240';
        }
        if($type == 'update'){
            $rules['json'] = 'required|json';   
        }
        return $rules;
    }
}
