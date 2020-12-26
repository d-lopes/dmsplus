<?php

namespace Database\Factories;

use App\Document;
use Illuminate\Database\Eloquent\Factories\Factory;

class DocumentFactory extends Factory {

    protected $model = Document::class;

    public function definition() {
        return [
                'filename' => $this->faker->file,
                'content' => $this->faker->text,
                'path' => $this->faker->url
            ];
    }

}