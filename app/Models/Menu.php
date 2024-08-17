<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Spatie\Translatable\HasTranslations;


class Menu extends Model
{
    use HasFactory;
    public $timestamps = true;
    protected $table = "menus";
    protected $fillable = [
      'name'
    ];
    protected $guarded = [];


    public function items()
    {
        return $this->hasMany(MenuItem::class);
    }


}
