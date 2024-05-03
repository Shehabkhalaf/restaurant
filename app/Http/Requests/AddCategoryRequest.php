<?php

namespace App\Http\Requests;

use App\Traits\ApiResponse;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

class AddCategoryRequest extends FormRequest
{
    use ApiResponse;
    /**
     * Determine if the user is authorized to make this request.
     * @throws ValidationException
     */
    public function failedValidation(Validator $validator): void
    {
        if ($this->is('admin/*')) {
            $response = $this->JsonResponse(422, 'Validation Errors', $validator->errors());
            throw new ValidationException($validator, $response);
        }
    }
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
            'title' => 'required|unique:categories,title',
            'image' => 'required|file|image|mimes:png,jpg|max:2048'
        ];
    }
    public function messages(): array
    {
        return [
          'title.required' => 'يجب ادخال اسم التصنيف.',
          'title.unique' => 'يجب ان لا يتكرر اسم التصنيف.',
          'image.required' => 'يحب ادخال صورة.',
          'image.file' => 'يجب ان تكون صورة ملف.',
          'image.mime' => 'يجب ان يكون نوغ الصورة png,jpg.',
          'image.max' => 'يجب الا يتخطى حجم الصورو 2 ميجا.',
        ];
    }
}
