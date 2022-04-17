<?php

namespace App\Http\Requests\Meetings;

use Illuminate\Foundation\Http\FormRequest;

class StoreMeetRequest extends FormRequest
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
            'title' => 'required|min:6',
            'start-dateTime' => 'required|date',
            'during-time'=> 'required|integer',
            'recording'  => 'nullable',
            'need_pass'  => 'nullable',
        ];
    }
}
