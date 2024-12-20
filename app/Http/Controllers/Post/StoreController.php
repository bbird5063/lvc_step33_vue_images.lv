<?php

namespace App\Http\Controllers\Post;

use App\Http\Controllers\Controller;
use App\Http\Requests\Post\StoreRequest;
use App\Models\Image;
use App\Models\Post;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

/* https://image.intervention.io/v3	*/
//require './vendor/autoload.php';
use Intervention\Image\ImageManager; // НУЖЕН
//use Intervention\Image\Drivers\Gd\Driver;

class StoreController extends Controller
{
	public function __invoke(StoreRequest $request)
	{
		$data = $request->validated(); // при отсутствии title никакой ошибки(?)
		//dump($data); // Ok
		//dd($data); // POST http://127.0.0.1:8000/api/posts 500 (Internal Server Error)...
		$images = $data['images']; // при отстуствии картинки: {message: "Undefined array key "images"", exception: "ErrorException",…}, дальше ничего не выплняется
		unset($data['images']);

		dump(phpinfo());

		$post = Post::firstOrCreate($data); // создадим пост (?)
		foreach ($images as $image) { // добавим в storage
			$name = md5(Carbon::now() . '_' . $image->getClientOriginalName()) . '.' . $image->getClientOriginalExtension(); // придумаем какое-то несусветное имя
			$filePath = Storage::disk('public')->putFileAs('/images', $image, $name); // putFileAs подчертнут красным(?)
			$previewName = 'prev_' . $name;
			//dump($filePath); // "images/cbd7cec141e7c1b69b8d2f6a545a77fb.jpg"
			//dd(url('/storage/' . $filePath));

			/* LVC(старая версия) */
			//\Intervention\Image\Facades\Image::make($image)->fit(100, 100)->save(storage_path('app/public/images/' . $previewName));

			/* https://image.intervention.io/v3	не работает */
			//$manager = new ImageManager(new Driver());
			//$img = $manager->read($image);
			//// resize image proportionally to 100px width
			//$img->scale(width: 100)->save(storage_path('app/public/images/' . $previewName));

			/* Примечания: @ВввКаа-л1к РАБОТАЕТ */
			$img = ImageManager::imagick()->read($image);
			$img->resize(100, 100)
				->save(storage_path('app/public/images/' . $previewName));
			/* можно ->save(public_path('storage/images' . $previewName)); */

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
