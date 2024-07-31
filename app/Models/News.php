<?php

namespace App\Models;

use Fomvasss\LaravelMetaTags\Traits\Metatagable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\HasTranslatableSlug;
use Spatie\Sluggable\SlugOptions;
use Spatie\Translatable\HasTranslations;

class News extends Model
{
    use HasFactory;
    use HasTranslations;
    use HasTranslatableSlug;
    use Metatagable;

    protected $fillable = [
        'meta_title',
        'meta_description',
        'meta_keywords',
        'title',
        'slug',
        'short_content',
        'content',
        'image',
        'status',
    ];
    public $translatable = ['meta_title',
        'meta_description',
        'meta_keywords','title', 'short_content', 'content', 'slug'];

    public function getSlugOptions(): SlugOptions
    {
//        return SlugOptions::create()
//            ->generateSlugsFrom('title')
//            ->saveSlugsTo('slug')
//            ->usingSeparator('_');
        return SlugOptions::createWithLocales(config('translatable.locales'))
            ->generateSlugsFrom(function ($model, $locale) {
                return "{$model->title}";
            })
            ->saveSlugsTo('slug')
            ->usingSeparator('-');
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }
}
