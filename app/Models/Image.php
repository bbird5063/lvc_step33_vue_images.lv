<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    use HasFactory;
		protected $table = 'images'; // LV и так привязывает, но так принято
		protected $guarded = false;  // Отключаем защиту массового присвоения
}
