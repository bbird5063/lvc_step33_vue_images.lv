<?php

namespace App\Http\Resources\Image;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage; // ДОБАВИЛОСЬ

class ImageResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
					'url' => $this->url,
					'preview_url' => $this->preview_url,
					'size' => Storage::disk('public')->size($this->path), // ДОБАВИЛИ
					'name' => str_replace('/images', '', $this->path), // ДОБАВИЛИ
				];
    }
}
