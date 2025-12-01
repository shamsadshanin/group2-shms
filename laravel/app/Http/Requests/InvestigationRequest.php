<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class InvestigationRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'ResultSummary' => 'required|string|max:500',
            'DetailedResults' => 'nullable|string|max:2000',
            'DigitalReport' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120', // 5MB max
            'TestParameters' => 'nullable|array',
            'Status' => 'required|in:Processing,Completed',
        ];
    }

    public function messages()
    {
        return [
            'ResultSummary.required' => 'Result summary is required.',
            'DigitalReport.required' => 'Digital report file is required.',
            'DigitalReport.mimes' => 'Report must be a PDF, JPG, or PNG file.',
            'DigitalReport.max' => 'Report file size must not exceed 5MB.',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if ($this->Status == 'Completed' && empty($this->DetailedResults)) {
                $validator->errors()->add(
                    'DetailedResults',
                    'Detailed results are required when marking investigation as completed.'
                );
            }
        });
    }
}
