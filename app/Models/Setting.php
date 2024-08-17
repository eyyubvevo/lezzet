<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'value',
        'display_name',
        'order',
        'type',
        'group',
    ];

    public static function getValueByKey($key)
    {
        return self::where('key', $key)->value('value');
    }
}
