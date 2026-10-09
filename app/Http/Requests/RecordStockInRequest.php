<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Class RecordStockInRequest
 *
 * Validates inbound inventory shipments and quantity increments.
 */
class RecordStockInRequest extends FormRequest
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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'sku' => ['required', 'string', 'exists:products,sku'],
            'quantity' => ['required', 'integer', 'min:1'],
            'notes' => ['nullable', 'string', 'max:255'],
        ];
    }

    /**
     * Custom message definitions.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'sku.exists' => 'The specified product SKU was not found in the inventory.',
        ];
    }
}
