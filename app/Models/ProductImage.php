<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductImage extends Model
{
    use HasFactory;

    protected $guarded = ['id', 'created_at', 'updated_at'];

    protected $fillable = [
        'product_id',
        'image'
    ];

    public function products(){
        return $this->belongsToMany(\App\Models\Product::class,"product_id","id");
    }
}
