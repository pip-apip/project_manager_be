<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;
use App\Helpers\Response;
use Illuminate\Validation\Rule;

class TimelinesUpdateRequest extends FormRequest
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
            'title' => 'sometimes|required|string|max:255',
            'start_date' => 'sometimes|date',
            'end_date' => 'sometimes|date|after_or_equal:start_date',
            'notes' => 'nullable|string',
            'user_id' => [
                'sometimes','required',
                Rule::exists('tm_users', 'id')->whereNull('deleted_at'),
            ],
            'project_id' => [
                'sometimes','required',
                Rule::exists('tp_1_projects', 'id')->whereNull('deleted_at'),
            ],
        ];
    }

    public function messages()
    {
        return [
            'title.required' => 'Judul wajib diisi.',
            'title.string' => 'Judul harus berupa teks.',
            'title.max' => 'Judul tidak boleh lebih dari 255 karakter.',

            'start_date.required' => 'Tanggal mulai wajib diisi.',
            'start_date.date' => 'Tanggal mulai harus berupa tanggal.',

            'end_date.required' => 'Tanggal selesai wajib diisi.',
            'end_date.date' => 'Tanggal selesai harus berupa tanggal.',
            'end_date.after_or_equal' => 'Tanggal selesai harus sama dengan atau setelah tanggal mulai.',

            'notes.string' => 'Catatan harus berupa teks.',

            'user_id.required' => 'ID pengguna wajib diisi.',
            'user_id.exists' => 'ID pengguna tidak valid.',

            'project_id.required' => 'ID proyek wajib diisi.',
            'project_id.exists' => 'ID proyek tidak valid.',
        ];
    }

    public function prepareForValidation()
    {
        $data = [];

        if ($this->has('title')) {
            $data['title'] = $this->input('title');
        }

        if ($this->has('start_date')) {
            $data['start_date'] = $this->input('start_date');
        }

        if ($this->has('end_date')) {
            $data['end_date'] = $this->input('end_date');
        }

        if ($this->has('notes')) {
            $data['notes'] = $this->input('notes');
        }

        if ($this->has('user_id')) {
            $data['user_id'] = $this->input('user_id');
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
            'Gagal mengubah data timeline',
            [],
            [],
            $validator->errors()
        ));
    }
}
