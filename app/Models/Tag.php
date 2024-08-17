<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Sluggable\HasTranslatableSlug;
use Spatie\Sluggable\SlugOptions;
use Spatie\Translatable\HasTranslations;

class Tag extends Model
{
    use HasFactory;
    use HasTranslatableSlug;
    use HasTranslations;
    use Search;

    public $table = 'tags';

    protected $guarded = ['id', 'created_at', 'updated_at'];
    protected $searchable = [
        'name',
    ];
    protected $fillable = [
        'name',
        'slug'
    ];
    public $translatable = ['name','slug'];

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::createWithLocales(config('translatable.locales'))
            ->generateSlugsFrom(function ($model, $locale) {
                return "{$model->name}";
            })
            ->saveSlugsTo('slug')
            ->usingSeparator('_');
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_tag_relationships');
    }
}
