<?php

namespace App\Http\Controllers\Post;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class StoreController extends Controller
{
	public function __invoke()
	{
		//dd(\request()->all()); // не подходит, нужет dump
		/*
		СМОТРЕТЬ:
		Консоль
		Сеть
			Fetch/XHR
				панель слева: posts
					Предварительный просмотр
		*/
		dump(\request()->all()); // смотреть все 
		return response(['message' => 'ok']);
		//return '11111'; // можно и так
	}
}
