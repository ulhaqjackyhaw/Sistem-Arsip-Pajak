<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BulkUploadRequest extends FormRequest
{
    public function authorize(): bool
    {
        // sudah dilindungi middleware role officer/admin
        return true;
    }

    public function rules(): array
    {
        return [
            'files'   => ['required','array','max:300'], // batasi biar aman
            'files.*' => ['file','mimes:pdf','max:20480'], // max 20MB per file; ubah sesuai kebutuhan
        ];
    }

    public function messages(): array
    {
        return [
            'files.required' => 'Pilih minimal satu file PDF.',
            'files.*.mimes'  => 'Hanya PDF yang diperbolehkan.',
            'files.*.max'    => 'Ukuran maksimum tiap file 20 MB.',
        ];
    }
}
