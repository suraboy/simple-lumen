<?php

namespace App\Http\Requests\Tickets;

use App\Contracts\FormRequest;

/**
 * Class IndexTicketsRequest
 * @package App\Http\Requests\Agreement
 */
class IndexTicketsRequest extends FormRequest
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
    public function rules()
    {
        return array(#'name' => 'required'
        );
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages()
    {
        return array(#'name.required' => ':attribute is required'
        );

    }

    /**
     * @return array
     */
    public function attributes()
    {
        return array(#'name' => trans('unit.name'),
        );
    }
}
