<?php

namespace App\Http\Requests\Post\Image;

use Illuminate\Foundation\Http\FormRequest;
//use Symfony\Contracts\Service\Attribute\Required;

class StoreRequest extends FormRequest
{
	/**
	 * Determine if the user is authorized to make this request.
	 */
	public function authorize(): bool
	{
		return true; // В ПЕРВУЮ ОЧЕРЕДЬ
	}

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
	 */
	public function rules(): array
	{
		return [
			'image' => 'required|file',
		];
	}
}
