<?php

namespace App\Http\Controllers\Post;

use App\Http\Controllers\Controller;
use App\Http\Requests\Post\UpdateRequest;
use App\Models\Image;
use App\Models\Post;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;

class UpdateController extends Controller
{
	public function __invoke(UpdateRequest $request, Post $post)
	{
		$data = $request->validated();
		$images = isset($data['images']) ? $data['images'] : null; // ДОБАВЛЯЕМ if
		$imagesIdsForDelete = isset($data['images_ids_for_delete']) ? $data['images_ids_for_delete'] : null; // ДОБАВИЛИ
		unset($data['images'], $data['images_ids_for_delete']); // ДОБАВИЛИ

		//$post = Post::firstOrCreate($data);
		$currentImages = $post->images; // ДОБАВИЛИ
		//dd($currentImages->count()); // click Update: 3
		if ($imagesIdsForDelete) { // ДОБАВИЛИ
			foreach ($currentImages as $currentImage) {
				if (in_array($currentImage->id, $imagesIdsForDelete)) {
					Storage::disk('public')->delete($currentImage->path); // удаляем файл из Storage
					Storage::disk('public')->delete(str_replace('images/', 'images/prev_', $currentImage->path)); // удаляем prev-файл из Storage
					$currentImage->delete(); // удаляем само изображение(запись в 'images') 
				}
			}
		}

		if ($images) { // ДОБАВИЛИ
			foreach ($images as $image) {
				$name = md5(Carbon::now() . '_' . $image->getClientOriginalName()) . '.' . $image->getClientOriginalExtension();
				$filePath = Storage::disk('public')->putFileAs('/images', $image, $name); // putFileAs подчертнут красным(?)
				$previewName = 'prev_' . $name;

				$img = ImageManager::imagick()->read($image);
				$img->resize(100, 100)
					->save(storage_path('app/public/images/' . $previewName));

				Image::create([
					'path' => $filePath,
					'url' => url('/storage/' . $filePath),
					'preview_url' => url('/storage/images/' . $previewName),
					'post_id' => $post->id
				]);
			}
		}

		return response()->json(['message' => 'success']);
	}
}
