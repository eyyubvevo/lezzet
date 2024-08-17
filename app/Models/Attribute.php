<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Attribute extends Model
{
    use HasFactory;
    use HasTranslations;

    public $table = 'attributes';

    protected $fillable = [
        'name'
    ];

    protected $guarded = ['id', 'created_at', 'updated_at'];

    public $translatable = ['name'];

    public $timestamps = true;

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'category_attribute_relationships');
    }
}
