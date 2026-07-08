<?php

namespace App\Http\Requests;

use App\Helpers\Response;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

class SpektekBulkCreateRequest extends FormRequest
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
            '*.name' => 'required|string|max:255',
            '*.type' => 'required|in:hardware,software',
            '*.qty_total' => 'required|integer|min:0',
            '*.qty_recived' => 'nullable|integer|min:0',
            '*.qty_nominal' => 'nullable|integer|min:0',
            '*.total_nominal' => 'required|integer|min:0',
            '*.progress_percentage' => 'nullable|numeric|min:0|max:100',
            '*.detail' => 'nullable|string',
            '*.note' => 'nullable|string',
            '*.project_id' => 'required|exists:tp_1_projects,id',
        ];
    }

    public function messages(): array
    {
        return [
            '*.name.required' => 'Nama spektek harus diisi',

            '*.qty_recived.integer' => 'Jumlah diterima harus berupa angka',
            '*.qty_recived.min' => 'Jumlah diterima tidak boleh kurang dari 0',

            '*.qty_total.required' => 'Jumlah total harus diisi',
            '*.qty_total.integer' => 'Jumlah total harus berupa angka',
            '*.qty_total.min' => 'Jumlah total tidak boleh kurang dari 0',

            '*.progress_percentage.min' => 'Persentase kemajuan tidak boleh kurang dari 0',
            '*.progress_percentage.max' => 'Persentase kemajuan tidak boleh lebih dari 100',

            '*.project_id.required' => 'ID proyek harus diisi',
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
