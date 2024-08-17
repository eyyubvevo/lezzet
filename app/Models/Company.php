<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    protected $table = 'companies';
    public $timestamps = true;
    protected $fillable = [
        'category_id',
        'image',
        'discount',
        'discount_start_date',
        'discount_end_date'
    ];

    public function category()
    {
        return $this->hasOne(Category::class, 'id', 'category_id');
    }
}
