<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateDeviceRequest extends FormRequest
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
        $deviceId = $this->route('device');

        return [
            'company_id'       => ['required', 'exists:companies,id'],
            // 'device_type_id' => ['required', 'in:pc,laptop,tablet,phone'],
            'device_type_id'   => ['required', 'exists:device_types,id'],
            'brand'            => ['nullable', 'string', 'max:100'],
            'model'            => ['nullable', 'string', 'max:100'],
            'serial'           => [
                'nullable',
                'string',
                'max:150',
                Rule::unique('devices', 'serial')->ignore($deviceId),
            ],
            'imei'             => [
                'nullable',
                'string',
                'max:20',
                Rule::unique('devices', 'imei')->ignore($deviceId),
            ],
            'os'               => ['nullable', 'string', 'max:50'],
            'cpu'              => ['nullable', 'string', 'max:100'],
            'ram_gb'           => ['nullable', 'integer', 'min:1', 'max:1024'],
            'storage_gb'       => ['nullable', 'integer', 'min:1', 'max:8192'],
            'purchase_date'    => ['nullable', 'date'],
            'warranty_months'  => ['nullable', 'integer', 'min:0', 'max:120'],
            'status'           => ['nullable', 'in:active,in_repair,lost,retired'],
            'asset_tag'        => [
                'nullable',
                'string',
                'max:100',
                Rule::unique('devices', 'asset_tag')->ignore($deviceId),
            ],
            'notes'            => ['nullable', 'string'],

            // ✅ Permitir arreglo dinámico de especificaciones
            'specs'            => ['array'],
            'specs.*'          => ['nullable'],
        ];
    }
}
