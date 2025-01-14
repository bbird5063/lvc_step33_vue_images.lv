<?php

namespace App\Http\Requests\Post;

use Illuminate\Foundation\Http\FormRequest;
//use Symfony\Contracts\Service\Attribute\Required;

class UpdateRequest extends FormRequest
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
			'title' => 'required|string',
			'images' => 'nullable|array',
			'content' => 'nullable|string',
			'images_ids_for_delete' => 'nullable|array', 
			'images_urls_for_delete' => 'nullable|array', // ДОБАВИЛИ
		];
	}
}
