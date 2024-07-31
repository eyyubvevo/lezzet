<?php

namespace App\Models;

use Fomvasss\LaravelMetaTags\Traits\Metatagable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;
use Butschster\Head\Contracts\MetaTags\SeoMetaTagsInterface;
use Butschster\Head\Contracts\MetaTags\RobotsTagsInterface;
class About extends Model implements SeoMetaTagsInterface, RobotsTagsInterface
{
    use HasFactory;
    use HasTranslations;
    use Metatagable;

    public $table = 'abouts';
    protected $fillable = [
        'title',
        'content',
        'keywords',
        'image'
    ];
    public $timestamps = true;

    protected $guarded = ['id', 'created_at', 'updated_at'];

    public $translatable = ['title','content','keywords'];

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
}
