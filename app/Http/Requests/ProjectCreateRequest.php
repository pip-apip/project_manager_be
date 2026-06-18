<?php

namespace App\Http\Requests;

use App\Helpers\Response;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class ProjectCreateRequest extends FormRequest
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
            'name' => 'required|string',
            'code' => 'required|string|max:10',
            'contract_number' => 'nullable|string|max:100',
            'contract_date' => 'nullable|date',
            'client' => 'nullable|string|max:100',
            'ppk' => 'nullable|string|max:100',
            'support_teams' => 'nullable|array',
            'support_teams.*' => 'string',
            'value' => 'nullable|numeric',
            'company_id' => [
                'required',
                Rule::exists('tm_companies', 'id')->whereNull('deleted_at'),
            ],
            'project_leader_id' => [
                'required',
                Rule::exists('tm_users', 'id')->whereNull('deleted_at'),
            ],
            'start_date' => 'nullable|date|before_or_equal:end_date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'maintenance_date' => 'nullable|date',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Nama wajib diisi.',
            'name.string' => 'Nama harus berupa teks.',

            'code.required' => 'Kode wajib diisi.',
            'code.string' => 'Kode harus berupa teks.',
            'code.max' => 'Kode maksimal 10 karakter.',

            'contract_number.required' => 'Nomor kontrak wajib diisi.',
            'contract_number.string' => 'Nomor kontrak harus berupa teks.',
            'contract_number.max' => 'Nomor kontrak maksimal 100 karakter.',

            'contract_date.required' => 'Tanggal kontrak wajib diisi.',
            'contract_date.date' => 'Tanggal kontrak harus berupa tanggal.',

            'client.required' => 'Klien wajib diisi.',
            'client.string' => 'Klien harus berupa teks.',
            'client.max' => 'Klien maksimal 100 karakter.',

            'ppk.required' => 'PPK wajib diisi.',
            'ppk.string' => 'PPK harus berupa teks.',
            'ppk.max' => 'PPK maksimal 100 karakter.',

            'support_teams.required' => 'Tim support wajib diisi.',
            'support_teams.array' => 'Tim support harus berupa array.',
            'support_teams.*.string' => 'Tim support harus berupa teks.',

            'value.required' => 'Nilai wajib diisi.',
            'value.numeric' => 'Nilai harus berupa angka.',

            'company_id.required' => 'Perusahaan wajib dipilih.',
            'company_id.exists' => 'Perusahaan tidak ditemukan atau sudah dihapus.',

            'project_leader_id.required' => 'Pemimpin proyek wajib dipilih.',
            'project_leader_id.exists' => 'Pemimpin proyek tidak ditemukan atau sudah dihapus.',

            'start_date.required' => 'Tanggal mulai wajib diisi.',
            'start_date.date' => 'Tanggal mulai harus berupa tanggal yang valid.',
            'start_date.before_or_equal' => 'Tanggal mulai harus sebelum atau sama dengan tanggal selesai.',

            'end_date.required' => 'Tanggal selesai wajib diisi.',
            'end_date.date' => 'Tanggal selesai harus berupa tanggal yang valid.',
            'end_date.after_or_equal' => 'Tanggal selesai harus setelah atau sama dengan tanggal mulai.',

            'maintenance_date.required' => 'Tanggal pemeliharaan wajib diisi.',
            'maintenance_date.date' => 'Tanggal pemeliharaan harus berupa tanggal yang valid.',
        ];
    }

    protected function prepareForValidation()
    {
        $data = [];

        if ($this->has('name')) {
            $data['name'] = strip_tags($this->name);
        }

        if ($this->has('code')) {
            $data['code'] = strip_tags($this->code);
        }

        if ($this->has('contract_number')) {
            $data['contract_number'] = strip_tags($this->contract_number);
        }

        if ($this->has('contract_date')) {
            $data['contract_date'] = strip_tags($this->contract_date);
        }

        if ($this->has('client')) {
            $data['client'] = strip_tags($this->client);
        }

        if ($this->has('ppk')) {
            $data['ppk'] = strip_tags($this->ppk);
        }

        if ($this->has('support_teams')) {
            $data['support_teams'] = is_array($this->support_teams)
            ? array_map('strip_tags', $this->support_teams)
            : strip_tags($this->support_teams);
        }

        if ($this->has('value')) {
            $data['value'] = strip_tags($this->value);
        }

        if ($this->has('status')) {
            $data['status'] = strip_tags($this->status);
        }

        if ($this->has('company_id')) {
            $data['company_id'] = strip_tags($this->company_id);
        }

        if ($this->has('project_leader_id')) {
            $data['project_leader_id'] = strip_tags($this->project_leader_id);
        }

        if ($this->has('start_date')) {
            $data['start_date'] = strip_tags($this->start_date);
        }

        if ($this->has('end_date')) {
            $data['end_date'] = strip_tags($this->end_date);
        }

        if ($this->has('maintenance_date')) {
            $data['maintenance_date'] = strip_tags($this->maintenance_date);
        }

        $this->merge($data);
    }

    protected function failedValidation(Validator $validator)
    {
        throw new ValidationException($validator, Response::handler(
            400,
            'Gagal membuat proyek',
            [],
            [],
            $validator->errors()
        ));
    }
}
