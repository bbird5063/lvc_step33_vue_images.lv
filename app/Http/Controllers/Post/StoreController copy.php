<?php

namespace App\Http\Controllers\Post;

use App\Http\Controllers\Controller;
use App\Http\Requests\Post\StoreRequest;
use App\Models\Image;
use App\Models\Post;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;


class StoreController extends Controller
{
	public function __invoke(StoreRequest $request)
	{
		$data = $request->validated(); // при отсутствии title никакой ошибки(?)
		//dump($data); // Ok
		//dd($data); // POST http://127.0.0.1:8000/api/posts 500 (Internal Server Error)...
		$images = $data['images']; // при отстуствии картинки: {message: "Undefined array key "images"", exception: "ErrorException",…}, дальше ничего не выплняется
		unset($data['images']);


		$post = Post::firstOrCreate($data); // создадим пост (?)
		foreach ($images as $image) { // добавим в storage
			$name = md5(Carbon::now() . '_' . $image->getClientOriginalName()) . '.' . $image->getClientOriginalExtension(); // придумаем какое-то несусветное имя
			$filePath = Storage::disk('public')->putFileAs('/images', $image, $name); // putFileAs подчертнут красным(?)
			$previewName = 'prev_' . $name;
			//dump($filePath); // "images/cbd7cec141e7c1b69b8d2f6a545a77fb.jpg"
			//dd(url('/storage/' . $filePath));
			Image::create([
				'path' => $filePath,
				'url' => url('/storage/' . $filePath),
				'post_id' => $post->id
			]);

			\Intervention\Image\Facades\Image::make($image)->fit(100, 100)->save(storage_path('app/public/images/' . $previewName));
		}


		return response()->json(['message' => 'success']);
		//return view('index');
	}
}
