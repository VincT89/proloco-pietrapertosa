<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class ProLocoContactRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation()
    {
        if ($this->has('email')) {
            $this->merge([
                'email' => strtolower(trim($this->email)),
            ]);
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email:rfc', 'max:190'],
            'subject' => ['nullable', 'string', 'max:120'],
            'message' => ['required', 'string', 'max:3000'],
            'website_url' => ['nullable', 'string', 'max:255'],
            'privacy' => ['accepted'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'name.required' => __('messages.contact_validation_name_required'),
            'email.required' => __('messages.contact_validation_email_required'),
            'email.email' => __('messages.contact_validation_email_required'),
            'message.required' => __('messages.contact_validation_message_required'),
            'privacy.accepted' => __('messages.contact_privacy_required'),
        ];
    }
}
