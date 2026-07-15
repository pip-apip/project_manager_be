<?php

namespace App\Http\Requests;

use App\Helpers\Response;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class AdminDocUpdateRequest extends FormRequest
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
            'title' => 'sometimes|string',
            'file' => 'sometimes|string',
            'project_id' => [
                'sometimes',
                Rule::exists('tp_1_projects', 'id')->whereNull('deleted_at'),
            ],
            'admin_doc_category_id' => [
                'sometimes',
                Rule::exists('tm_admin_doc_categories', 'id')->whereNull('deleted_at'),
            ],
            'keyword' => 'sometimes|array',
            'keyword.*' => 'string',
        ];
    }

    public function messages(): array
    {
        return [
            'title.string' => 'Judul harus berupa teks.',

            'file.string' => 'File harus berupa teks.',

            'project_id.exists' => 'Project tidak ditemukan.',

            'admin_doc_category_id.exists' => 'Kategori tidak ditemukan.',

            'keyword.array' => 'Keyword harus berupa array.',
            'keyword.*.string' => 'Keyword harus berupa teks.',
        ];
    }

    public function prepareForValidation(): void
    {
        $data = [];

        if ($this->has('title')) {
            $data['title'] = strip_tags($this->title);
        }

        if ($this->has('file')) {
            $data['file'] = strip_tags($this->file);
        }

        if ($this->has('project_id')) {
            $data['project_id'] = strip_tags($this->project_id);
        }

        if ($this->has('admin_doc_category_id')) {
            $data['admin_doc_category_id'] = strip_tags($this->admin_doc_category_id);
        }

        if ($this->has('keyword')) {
            $data['keyword'] = $this->keyword;
        }

        $this->merge($data);
    }

    protected function failedValidation(Validator $validator)
    {
        throw new ValidationException($validator, Response::handler(
            400,
            'Gagal membuat dokumen administrasi',
            [],
            [],
            $validator->errors()
        ));
    }
}
