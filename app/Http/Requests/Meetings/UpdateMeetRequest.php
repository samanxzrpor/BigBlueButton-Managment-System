<?php

namespace App\Http\Requests\Meetings;

use Illuminate\Foundation\Http\FormRequest;

class UpdateMeetRequest extends FormRequest
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
            'title' => 'nullable|min:6',
            'start-dateTime' => 'nullable|date',
            'during-time'=>'nullable|integer',
            'recording'  => 'nullable',
            'need_pass'  => 'nullable',
        ];
    }
}
