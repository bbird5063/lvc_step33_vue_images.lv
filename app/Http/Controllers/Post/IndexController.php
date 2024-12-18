<?php

namespace App\Http\Controllers\Post;

use App\Http\Controllers\Controller;
use App\Http\Resources\Post\PostResource;
//use App\Http\Requests\Post\StoreRequest;
//use App\Models\Image;
use App\Models\Post;
//use Carbon\Carbon;
//use Illuminate\Http\Request;
//use Illuminate\Support\Facades\Storage;

class IndexController extends Controller
{
	public function __invoke()
	{
		$post = Post::latest()->first(); // latest - сортировка от нового к старому

		return new PostResource($post);
	}
}
