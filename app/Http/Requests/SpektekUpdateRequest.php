<?php

namespace App\Http\Requests;

use App\Helpers\Response;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

class SpektekUpdateRequest extends FormRequest
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
            'qty_nominal' => 'sometimes|required|integer|min:0',
            'total_nominal' => 'sometimes|required|integer|min:0',
            'percentage' => 'sometimes|nullable|numeric|min:0|max:100',
            'detail' => 'sometimes|nullable|string',
            'note' => 'sometimes|nullable|string',
            'project_id' => 'sometimes|required|exists:tp_1_projects,id',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Nama spektek harus diisi',

            'qty_recived.integer' => 'Jumlah diterima harus berupa angka',
            'qty_recived.min' => 'Jumlah diterima tidak boleh kurang dari 0',

            'qty_total.required' => 'Jumlah total harus diisi',
            'qty_total.integer' => 'Jumlah total harus berupa angka',
            'qty_total.min' => 'Jumlah total tidak boleh kurang dari 0',

            'percentage.min' => 'Persentase tidak boleh kurang dari 0',
            'percentage.max' => 'Persentase tidak boleh lebih dari 100',

            'project_id.required' => 'ID proyek harus diisi',
            'project_id.exists' => 'ID proyek tidak valid',
        ];
    }

    public function prepareForValidation()
    {
        $data = [];

        if ($this->has('name')) {
            $data['name'] = $this->input('name');
        }
        if ($this->has('type')) {
            $data['type'] = $this->input('type');
        }
        if ($this->has('qty_total')) {
            $data['qty_total'] = $this->input('qty_total');
        }
        if ($this->has('qty_nominal')) {
            $data['qty_nominal'] = $this->input('qty_nominal');
        }
        if ($this->has('total_nominal')) {
            $data['total_nominal'] = $this->input('total_nominal');
        }
        if ($this->has('percentage')) {
            $data['percentage'] = $this->input('percentage');
        }
        if ($this->has('qty_recived')) {
            $data['qty_recived'] = $this->input('qty_recived');
        }
        if ($this->has('detail')) {
            $data['detail'] = $this->input('detail');
        }
        if ($this->has('note')) {
            $data['note'] = $this->input('note');
        }
        if ($this->has('project_id')) {
            $data['project_id'] = $this->input('project_id');
        }

        $this->merge($data);
    }

    protected function failedValidation(Validator $validator)
    {
        throw new ValidationException($validator, Response::handler(
            400,
            'Gagal memperbarui spektek',
            [],
            [],
            $validator->errors()
        ));
    }
}
