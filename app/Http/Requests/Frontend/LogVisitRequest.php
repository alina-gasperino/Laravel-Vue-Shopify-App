<?php

namespace App\Http\Requests\Frontend;

use Illuminate\Foundation\Http\FormRequest;

class LogVisitRequest extends FormRequest
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
        return [
            'shopName'          => 'required',
            'path'              => 'required',
            'variantId'         => '',
            'sessionId'         => 'required',
            'timestamp'         => 'required|date',
            'ip'                => 'required|ip',
            'audience_size_id'  => 'int',
            'type'              => 'required'
        ];
    }
}
