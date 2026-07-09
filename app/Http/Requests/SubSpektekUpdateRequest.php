<?php

namespace App\Http\Requests;

use App\Helpers\Response;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

class SubSpektekUpdateRequest extends FormRequest
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
            'name' => 'sometimes|required|string|max:255',
            'type' => 'sometimes|required|in:hardware,software',
            'qty_total' => 'sometimes|required|integer|min:0',
            'qty_recived' => 'sometimes|nullable|integer|min:0',
            'qty_nominal' => 'sometimes|nullable|integer|min:0',
            'total_nominal' => 'sometimes|required|integer|min:0',
            'progress_percentage' => 'sometimes|nullable|numeric|min:0|max:100',
            'detail' => 'sometimes|nullable|string',
            'note' => 'sometimes|nullable|string',
            'spektek_id' => 'sometimes|required|exists:tp_8_spekteks,id',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Nama sub spektek harus diisi',

            'qty_recived.integer' => 'Jumlah diterima harus berupa angka',
            'qty_recived.min' => 'Jumlah diterima tidak boleh kurang dari 0',

            'qty_total.required' => 'Jumlah total harus diisi',
            'qty_total.integer' => 'Jumlah total harus berupa angka',
            'qty_total.min' => 'Jumlah total tidak boleh kurang dari 0',

            'progress_percentage.min' => 'Persentase kemajuan tidak boleh kurang dari 0',
            'progress_percentage.max' => 'Persentase kemajuan tidak boleh lebih dari 100',

            'spektek_id.required' => 'ID spektek harus diisi',
            'spektek_id.exists' => 'ID spektek tidak valid',
        ];
    }

    public function prepareForValidation(): void
    {
        $data = [];

        if ($this->filled('name')) {
            $data['name'] = strip_tags($this->name);
        }
        if ($this->filled('type')) {
            $data['type'] = strip_tags($this->type);
        }
        if ($this->filled('qty_total')) {
            $data['qty_total'] = strip_tags($this->qty_total);
        }
        if ($this->filled('qty_recived')) {
            $data['qty_recived'] = strip_tags($this->qty_recived);
        }
        if ($this->filled('qty_nominal')) {
            $data['qty_nominal'] = strip_tags($this->qty_nominal);
        }
        if ($this->filled('total_nominal')) {
            $data['total_nominal'] = strip_tags($this->total_nominal);
        }
        if ($this->filled('progress_percentage')) {
            $data['progress_percentage'] = strip_tags($this->progress_percentage);
        }
        if ($this->filled('detail')) {
            $data['detail'] = strip_tags($this->detail);
        }
        if ($this->filled('note')) {
            $data['note'] = strip_tags($this->note);
        }
        if ($this->filled('spektek_id')) {
            $data['spektek_id'] = strip_tags($this->spektek_id);
        }
        $this->merge($data);
    }

    public function failedValidation(Validator $validator): void
    {
        throw new ValidationException($validator, Response::handler(
            400,
            'Gagal mengubah sub spektek',
            [],
            [],
            $validator->errors()
        ));
    }
}
