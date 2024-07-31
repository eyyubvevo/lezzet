<?php

namespace App\Models;

use Fomvasss\LaravelMetaTags\Traits\Metatagable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Spatie\Sluggable\HasTranslatableSlug;
use Spatie\Sluggable\SlugOptions;
use Spatie\Translatable\HasTranslations;

class Category extends Model
{
    use HasTranslatableSlug;
    use HasTranslations;
    use Metatagable;

    protected $fillable = [
        'meta_title',
        'meta_description',
        'meta_keywords',
        'name',
        'image',
        'home_status',
        'status',
        'slug',
        'order',
        'type',
        'parent_id'
    ];

    protected $table = 'categories';
    protected $guarded = ['id', 'created_at', 'updated_at'];
    public $translatable = ['meta_title', 'meta_description','slug','meta_keywords','name'];

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::createWithLocales(config('translatable.locales'))
            ->generateSlugsFrom(function ($model, $locale) {
                return "{$model->name}";
            })
            ->saveSlugsTo('slug')
            ->usingSeparator('-');
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }
//    protected static function boot()
//    {
//        parent::boot();
//
//        static::creating(function ($category) {
//            $category->slug = Str::slug(strtolower($category->name), '_');
//        });
//
//        static::updating(function ($category) {
//            $category->slug = Str::slug(strtolower($category->name), '_');
//        });
//    }


    public function attributes()
    {
        return $this->belongsToMany(Attribute::class, 'category_attribute_relationships');
    }


    public function products()
    {
        return $this->hasMany(Product::class, 'category_id')->orderBy('order','asc');
    }

    public function company()
    {
        return $this->hasOne(Company::class, 'category_id', 'id');
    }

    public function scopeHasDiscountedCategory($query)
    {
        return $query->whereDoesntHave('company');
    }
}
