<?php

namespace App\Http\Resources\Post;

use App\Http\Resources\Image\ImageResource; // ДОБАВИЛОСЬ
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
{
	/**
	 * Transform the resource into an array.
	 *
	 * @return array<string, mixed>
	 */
	public function toArray(Request $request): array
	{
		return [
			'id' => $this->id,
			'title' => $this->title,
			'images' => ImageResource::collection($this->images),
			'content' => $this->content,
		];
	}
}
