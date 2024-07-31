<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Slider extends Model
{
    use HasFactory;
    use HasTranslations;

    protected $guarded = [];

    public $table = 'sliders';

    public $timestamps = true;

    protected $fillable = [
        'image',
        'title',
        'order',
        'status'
    ];

    public $translatable = ['title'];

}
