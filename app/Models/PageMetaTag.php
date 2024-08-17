<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class PageMetaTag extends Model
{
    use HasTranslations;
    protected $table = "page_meta_tags";

    protected $fillable = [
        'page_name',
        'title',
        'description',
        'keywords',
        'robots',
        'favicon',
        'url'
    ];

    protected $guarded = ['id', 'created_at', 'updated_at'];
    public $translatable = ['title', 'description', 'keywords'];
}
