<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Class UpdateProductRequest
 *
 * FILE OVERVIEW:
 * Form request validator kapag nag-e-edit ng existing na produkto.
 *
 * KAILAN ITO TINATAWAG:
 * - Tinatawag ito ng ProductController@update. Sinisigurado nito na valid ang bagong
 *   data at hindi nagka-conflict ang SKU sa ibang produkto.
 */
class UpdateProductRequest extends FormRequest
{
    /**
     * Pahintulot para mag-update.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Validation rules para sa pag-update ng produkto.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $productId = $this->route('product');
        if (is_object($productId)) {
            $productId = $productId->id;
        }

        return [
            'name' => ['required', 'string', 'max:255'],
            'sku'  => [
                'required',
                'string',
                'max:50',
                Rule::unique('products', 'sku')->ignore($productId),
            ],
            'category'      => ['required', 'string', 'max:100'],
            'supplier_id'   => ['nullable', 'integer', 'exists:suppliers,id'],
            'location'      => ['nullable', 'string', 'max:100'],
            'quantity'      => ['required', 'integer', 'min:0'],
            'reorder_point' => ['nullable', 'integer', 'min:0'],
            'price'         => ['nullable', 'numeric', 'min:0'],
        ];
    }
}
