<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductTagRelationship extends Model
{
    protected $fillable = ['product_id', 'tag_id'];
    public $table = 'product_tag_relationships';
    protected $guarded = ['id', 'created_at', 'updated_at'];
    public function products()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function tags()
    {
        return $this->belongsTo(Tag::class, 'tag_id');
    }
}
