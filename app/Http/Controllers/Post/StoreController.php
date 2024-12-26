<?php

namespace App\Http\Controllers\Post;

use App\Http\Controllers\Controller;
use App\Http\Requests\Post\StoreRequest;
use App\Models\Image;
use App\Models\Post;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;

class StoreController extends Controller
{
	public function __invoke(StoreRequest $request)
	{
		$data = $request->validated(); // при отсутствии title никакой ошибки(?)
		$images = $data['images']; // при отстуствии картинки: {message: "Undefined array key "images"", exception: "ErrorException",…}, дальше ничего не выплняется
		unset($data['images']);

		dump(phpinfo());

		$post = Post::firstOrCreate($data);
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

		return response()->json(['message' => 'success']);
	}
}
