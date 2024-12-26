<?php

namespace App\Http\Controllers\Post\Image; // + '\Image'

use App\Http\Controllers\Controller;
use App\Http\Requests\Post\Image\StoreRequest; // + '\Image'
//use App\Models\Image;
//use App\Models\Post;
use Carbon\Carbon;
//use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
//use Intervention\Image\ImageManager;

class StoreController extends Controller
{
	public function __invoke(StoreRequest $request)
	{
		$data = $request->validated();
		$image = $data['image']; // ДОБАВИЛИ
		$name = md5(Carbon::now() . '_' . $image->getClientOriginalName()) . '.' . $image->getClientOriginalExtension(); // СКОПИРОВАЛИ ИЗ Post\StoreController
		/**
		 * Сделаем еще папку content, что различать где шо(LVC ее не делал)
		 */
		$filePath = Storage::disk('public')->putFileAs('/images/content', $image, $name);  // СКОПИРОВАЛИ ИЗ Post\StoreController

		return response()->json(['url' => url('/storage/' . $filePath)]);
	}
}
