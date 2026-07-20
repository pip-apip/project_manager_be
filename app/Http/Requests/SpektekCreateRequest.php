<?php

namespace App\Http\Requests;

use App\Helpers\Response;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

class SpektekCreateRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'type' => 'required|in:hardware,software',
            'qty_total' => 'required|integer|min:0',
            'qty_received' => 'nullable|integer|min:0',
            'qty_nominal' => 'nullable|integer|min:0',
            'total_nominal' => 'required|integer|min:0',
            'progress_percentage' => 'nullable|numeric|min:0|max:100',
            'detail' => 'nullable|string',
            'note' => 'nullable|string',
            'project_id' => 'required|exists:tp_1_projects,id',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Nama spektek harus diisi',

            'qty_received.integer' => 'Jumlah diterima harus berupa angka',
            'qty_received.min' => 'Jumlah diterima tidak boleh kurang dari 0',

            'qty_total.required' => 'Jumlah total harus diisi',
            'qty_total.integer' => 'Jumlah total harus berupa angka',
            'qty_total.min' => 'Jumlah total tidak boleh kurang dari 0',

            'progress_percentage.min' => 'Persentase kemajuan tidak boleh kurang dari 0',
            'progress_percentage.max' => 'Persentase kemajuan tidak boleh lebih dari 100',

            'project_id.required' => 'ID proyek harus diisi',
            'project_id.exists' => 'ID proyek tidak valid',
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'name' => $this->name ?? null,
            'type' => $this->type ?? null,
            'qty_total' => $this->qty_total ?? null,
            'qty_nominal' => $this->qty_nominal ?? null,
            'total_nominal' => $this->total_nominal ?? null,
            'progress_percentage' => $this->progress_percentage ?? null,
            'qty_received' => $this->qty_received ?? null,
            'detail' => $this->detail ?? null,
            'note' => $this->note ?? null,
            'project_id' => $this->project_id ?? null,
        ]);
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
