<?php

namespace App\Http\Requests\Campaign;

use Illuminate\Foundation\Http\FormRequest;

class StartCampaignRequest extends FormRequest
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
            // 'audience' => 'required|int',
            // 'discount_type' => ['required', Rule::in(['1', '2'])],
            // 'discount_amount' => ['required', 'regex:/^\$?([0-9]+)%?$/'],
            // 'discount_prefix' => ['required', 'string'],
            'project_id' => 'required|alpha_num',
            'thumbnail_url' => ['required', 'url'],
            // 'max_sends' => ['int', 'min:1', 'nullable']
        ];
    }

    public function messages()
    {
        return [
            'thumbnail_url.required' => 'You must save your postcard design before proceeding',
            'project_id.required' => 'You must select a postcard template before proceeding',
            // 'discount_type.required' => 'Discount type is required',
            // 'discount_amount.required' => 'Discount amount is required',
            // 'discount_amount.regex' => 'Please enter a valid discount account',
            // 'max_sends.min' => 'Leave blank or enter a number great than 0',
            // 'max_sends.int' => 'You can only enter numbers for max sends',
            // 'audience.required' => 'You must select your audience size'
        ];
    }
}
