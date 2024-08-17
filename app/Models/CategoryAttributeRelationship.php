<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategoryAttributeRelationship extends Model
{
    use HasFactory;

    protected $fillable = ['attribute_id', 'category_id'];
    public $table = 'category_attribute_relationships';
    protected $guarded = ['id', 'created_at', 'updated_at'];
    public function attribute()
    {
        return $this->belongsTo(Attribute::class, 'attribute_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }
}

