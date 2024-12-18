<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;
		protected $table = 'posts'; // LV и так привязывает, но так принято
		protected $guarded = false;  // Отключаем защиту массового присвоения

		public function images() // ДОБАВИЛИ
		{
			return $this->hasMany(Image::class, 'post_id', 'id');
		}
}
