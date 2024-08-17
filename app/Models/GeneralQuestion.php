<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class GeneralQuestion extends Model
{
    use HasFactory;
    use HasTranslations;

    public $table = 'general_questions';
    protected $fillable = [
        'question',
        'answer',
        'status'
    ];
    public $timestamps = true;
    protected $guarded = ['id', 'created_at', 'updated_at'];

    public $translatable = ['question','answer'];
}
