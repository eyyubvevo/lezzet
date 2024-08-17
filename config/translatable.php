<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Translatable Models
    |--------------------------------------------------------------------------
    |
    | Eloquent models located in these namespaces will be treated as translatable models.
    */

    'locales' => [
        'az', 'en' , 'ru'
    ],

    'locale_separator' => '-',

    'locale' => null,

    'use_fallback' => false,

    'use_property_fallback' => true,

    'fallback_locale' => 'en',

    'hide_fallback' => false,

    'fallback_empty' => false,

    'translatable_attributes_key' => ':attributes',

    'translation_suffix' => 'Translation',

    'empty_translations' => false,

    'rule_factory' => '',

    'class_name' => Spatie\Translatable\Translatable::class,

    'locale_key' => 'locale',

    'model_namespace' => 'Spatie\Translatable\Test',

    'guard_name' => 'web',

    'use_properties' => false,

    'locales_table' => 'language',

    'locale_key_type' => 'string',

    'locale_key_length' => 2,

    'locale_key_modifier' => '',

    'use_attribute_fallback' => true,

    'attribute_fallback_locale' => 'en',

];
