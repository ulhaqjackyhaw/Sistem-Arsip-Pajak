<?php
namespace App\Http\Requests;


use Illuminate\Foundation\Http\FormRequest;


class StoreDocumentRequest extends FormRequest
{
public function authorize(): bool { return $this->user()?->isOfficer() || $this->user()?->isAdmin(); }


public function rules(): array
{
return [
'vendor_id' => ['required','exists:vendors,id'],
'period' => ['required','date_format:Y-m'],
'file' => ['required','file','max:10240'], // 10MB contoh
];
}
}