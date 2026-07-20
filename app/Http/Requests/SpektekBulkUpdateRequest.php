<?php

namespace App\Http\Requests;

use App\Helpers\Response;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

class SpektekBulkUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.`
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
            '*.id' => 'required|exists:tp_8_spekteks,id',
            '*.name' => 'sometimes|string|max:255',
            '*.type' => 'sometimes|in:hardware,software',
            '*.qty_total' => 'sometimes|integer|min:0',
            '*.qty_received' => 'sometimes|integer|min:0',
            '*.qty_nominal' => 'sometimes|integer|min:0',
            '*.total_nominal' => 'sometimes|integer|min:0',
            '*.progress_percentage' => 'sometimes|numeric|min:0|max:100',
            '*.detail' => 'sometimes|string',
            '*.note' => 'sometimes|string',
            '*.project_id' => 'sometimes|exists:tp_1_projects,id',
        ];
    }

    public function messages(): array
    {
        return [
            '*.id.required' => 'ID spektek harus diisi',
            '*.id.exists' => 'ID spektek tidak valid',

            '*.name.string' => 'Nama spektek harus berupa string',
            '*.name.max' => 'Nama spektek tidak boleh lebih dari 255 karakter',

            '*.qty_received.integer' => 'Jumlah diterima harus berupa angka',
            '*.qty_received.min' => 'Jumlah diterima tidak boleh kurang dari 0',

            '*.qty_total.integer' => 'Jumlah total harus berupa angka',
            '*.qty_total.min' => 'Jumlah total tidak boleh kurang dari 0',

            '*.progress_percentage.min' => 'Persentase kemajuan tidak boleh kurang dari 0',
            '*.progress_percentage.max' => 'Persentase kemajuan tidak boleh lebih dari 100',

            '*.project_id.exists' => 'ID proyek tidak valid',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new ValidationException($validator, Response::handler(
            400,
            'Gagal membuat spektek',
            [],
            [],
            $validator->errors()
        ));
    }
}
