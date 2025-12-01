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

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $prescription = Prescription::find($this->PrescriptionID);
            $medicine = $prescription->medicine;

            if (!$prescription->canBeDispensed()) {
                $validator->errors()->add(
                    'PrescriptionID',
                    'This prescription cannot be dispensed. It may already be completed or medicine is out of stock.'
                );
            }

            if ($medicine && $this->QuantityDispensed > $prescription->getRemainingQuantity()) {
                $validator->errors()->add(
                    'QuantityDispensed',
                    "Dispensing quantity cannot exceed remaining prescribed quantity ({$prescription->getRemainingQuantity()})."
                );
            }

            if ($medicine && $this->QuantityDispensed > $medicine->StockQuantity) {
                $validator->errors()->add(
                    'QuantityDispensed',
                    "Insufficient stock. Only {$medicine->StockQuantity} units available."
                );
            }
        });
    }
}
