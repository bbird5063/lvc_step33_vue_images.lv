<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class IndexController extends Controller
{
  public function __invoke()
	{
		//return 111111;
		return view('index');
	}
}
