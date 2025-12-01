<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PrescriptionRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'AppointmentID' => [
                'required',
                'exists:appointments,AppointmentID',
                Rule::exists('appointments', 'AppointmentID')->where(function ($query) {
                    $query->where('DoctorID', auth()->user()->doctor->DoctorID)
                          ->whereIn('Status', ['Confirmed', 'Completed']);
                }),
            ],
            'MedicineName' => 'required|string|max:100',
            'Dosage' => 'required|string|max:50',
            'Frequency' => 'required|string|max:50',
            'Duration' => 'required|string|max:50',
            'Instructions' => 'nullable|string|max:500',
            'Notes' => 'nullable|string|max:1000',
        ];
    }

    public function messages()
    {
        return [
            'AppointmentID.exists' => 'The selected appointment is not valid or does not belong to you.',
            'MedicineName.required' => 'Medicine name is required.',
            'Dosage.required' => 'Dosage information is required.',
            'Frequency.required' => 'Frequency of medication is required.',
            'Duration.required' => 'Duration of treatment is required.',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if ($this->checkExistingPrescription()) {
                $validator->errors()->add(
                    'AppointmentID',
                    'A prescription already exists for this appointment.'
                );
            }
        });
    }

    private function checkExistingPrescription()
    {
        return \App\Models\Prescription::where('AppointmentID', $this->AppointmentID)
            ->where('IsActive', true)
            ->exists();
    }
}
