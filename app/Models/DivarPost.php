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
        return "🗃 {$this->title}".PHP_EOL."📍 {$this->subtitle}".PHP_EOL.PHP_EOL."🔵 {$this->description}".PHP_EOL.PHP_EOL.$this->getMetaDataText();
    }

    public function getMetaDataText()
    {
        $text = '';
        foreach (json_decode($this->meta_data) as $meta_data)
        {
            $text .= '🔖 '.$meta_data[0] . ': ' . $meta_data[1] . PHP_EOL;
        }
        return $text;
    }
}
