<?php

namespace App\Http\Requests\Report;

use Illuminate\Foundation\Http\FormRequest;

class ExportRequest extends FormRequest
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
            'startYear'  => 'required',
            'startMonth' => 'required',
            'startDay'   => 'required',
            'endYear'    => 'required',
            'endMonth'   => 'required',
            'endDay'     => 'required',
        ];
    }

    public function attributes()
    {
        return [
            'startYear'  => '開始年',
            'startMonth' => '開始月',
            'startDay'   => '開始日',
            'endYear'    => '終了年',
            'endMonth'   => '終了月',
            'endDay'     => '終了日',
        ];
    }
}
