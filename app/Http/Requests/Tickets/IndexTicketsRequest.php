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
        return array(
            'created_start_at' => 'required_with:created_end_at|filled|date_format:Y-m-d H:i:s|before_or_equal:created_end_at',
            'created_end_at' => 'required_with:created_start_at|filled|date_format:Y-m-d H:i:s|after_or_equal:created_start_at',

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
