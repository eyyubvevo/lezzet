<?php

namespace Outhebox\TranslationsUI\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Outhebox\TranslationsUI\Models\TranslationFile;

class TranslationFileFactory extends Factory
{
    protected $model = TranslationFile::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->randomElement(['app', 'auth', 'pagination', 'passwords', 'validation']),
            'extension' => $this->faker->randomElement(['json', 'php']),
        ];
    }
}
