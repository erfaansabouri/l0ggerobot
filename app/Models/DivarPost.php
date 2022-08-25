<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DivarPost extends Model
{
    protected $table = 'divar_posts';
    protected $guarded = [];

    public function telegramText()
    {
        return "🗃 {$this->title}".PHP_EOL."📍 {$this->subtitle}".PHP_EOL."ℹ️ {$this->description}".PHP_EOL.PHP_EOL;
    }
}
