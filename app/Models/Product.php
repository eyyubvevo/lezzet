<?php

namespace App\Models;

use Butschster\Head\Contracts\MetaTags\RobotsTagsInterface;
use Butschster\Head\Contracts\MetaTags\SeoMetaTagsInterface;
use Fomvasss\LaravelMetaTags\Traits\Metatagable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Search;
use Spatie\Sluggable\HasTranslatableSlug;
use Spatie\Translatable\HasTranslations;
use Spatie\Sluggable\SlugOptions;

class Product extends Model implements SeoMetaTagsInterface, RobotsTagsInterface
{
    use HasFactory;
    use HasTranslatableSlug;
    use Search;
    use HasTranslations;
    use Metatagable;
    public $table = 'products';

    protected $fillable = [
        'meta_title',
        'meta_description',
        'meta_keywords',
        'title',
        'slug',
        'content',
        'price',
        'discount',
        'order',
        'status',
        'home_status',
        'is_discountable',
        'attribute_id',
        'category_id',
        'discount_start_date',
        'discount_end_date'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'discount' => 'decimal:2',
        'status' => 'boolean',
        'home_status' => 'boolean',
        'is_discountable' => 'boolean',
        'discount_start_date' => 'datetime',
        'discount_end_date' => 'datetime'
    ];

    protected $guarded = ['id', 'created_at', 'updated_at'];

    protected $searchable = [
        'title',
        'slug'
    ];

    public $translatable = ['meta_title',
        'meta_description','slug',
        'meta_keywords','title', 'content'];

    public function getSlugOptions(): SlugOptions
    {
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
    
    public static function getMinPrice()
    {
        return self::min('price');
    }

    public static function getMaxPrice()
    {
        return self::max('price');
    }

    public function attribute()
    {
        return $this->hasOne(Attribute::class, 'id', 'attribute_id');
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function getDescription(): ?string
    {
        return $this->content;
    }

    public function getKeywords()
    {
        return $this->keywords;
    }

    public function getRobots(): ?string
    {
        return 'Shamil, Vaqif';
    }
    public function category()
    {
        return $this->hasOne(Category::class, 'id', 'category_id');
    }
    public function scopeAttributeProductCounts($query, $selectedAttributes)
    {
        return $query->whereHas('attributes', function ($query) use ($selectedAttributes) {
            $query->whereIn('id', $selectedAttributes);
        })->selectRaw('attributes.id, count(products.id) as count')
            ->groupBy('attributes.id')
            ->pluck('count', 'id');
    }
    public function getPriceWithDiscount()
    {
        if ($this->category->company) {
            return $this->price - ($this->price * $this->category->company->discount / 100);
        }
        return $this->price;
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'product_tag_relationships');
    }
}
