<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Prescription;
use App\Models\Medicine;

class DispensingRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'PrescriptionID' => 'required|exists:prescriptions,PrescriptionID',
            'QuantityDispensed' => 'required|integer|min:1',
            'Notes' => 'nullable|string|max:500',
        ];
    }

    public function messages()
    {
        return [
            'PrescriptionID.required' => 'Prescription is required.',
            'QuantityDispensed.required' => 'Dispensing quantity is required.',
            'QuantityDispensed.min' => 'Dispensing quantity must be at least 1.',
        ];
    }


        });
    }
}
