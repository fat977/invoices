<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class InvoiceRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            //
            'invoice_number' => ['required'],
            'invoice_date'=>['nullable'],
            'due_date'=>['nullable'],
            'amount_collection'=>['required','numeric'],
            'amount_commission'=>['required','numeric'],
            'product' => ['required'],
            'discount'=>['required','numeric'],
            'value_vat'=>['required','numeric'],
            'rate_vat'=>['required'],
            'total'=>['required','numeric'],
            'section_id'=>['required','integer','exists:sections,id'], 
            'status'=>['nullable'],
            'payment_date' => ['nullable'],
            'note' => ['nullable'],
        ];
    }
}
