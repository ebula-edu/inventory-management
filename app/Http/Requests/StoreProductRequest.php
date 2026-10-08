<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Class StoreProductRequest
 *
 * FILE OVERVIEW:
 * Ito ang Form Request validator kapag nagpapasok ng bagong produkto sa inventory.
 *
 * KAILAN ITO TINATAWAG:
 * - Tinatawag ito ng Laravel bago pumasok sa ProductController@store.
 *   Kung may mali sa inputs (halimbawa: may duplicate na SKU o kulang ang pangalan),
 *   awtomatikong ibabalik ang user na may kasamang malinaw na error messages.
 */
class StoreProductRequest extends FormRequest
{
    /**
     * Sinusuri kung may pahintulot ang user na mag-submit ng form na ito.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Mga validation rules para masigurong malinis at safe ang datos sa SQL database.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name'          => ['required', 'string', 'max:255'],
            'sku'           => ['required', 'string', 'max:50', 'unique:products,sku'],
            'category'      => ['required', 'string', 'max:100'],
            'supplier_id'   => ['nullable', 'integer', 'exists:suppliers,id'],
            'location'      => ['nullable', 'string', 'max:100'],
            'quantity'      => ['required', 'integer', 'min:0'],
            'reorder_point' => ['nullable', 'integer', 'min:0'],
            'price'         => ['nullable', 'numeric', 'min:0'],
        ];
    }
}
