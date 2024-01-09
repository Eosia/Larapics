<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\{
    Source,
    Photo,
};
use Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Source>
 */
class SourceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {

        $width = 640;
        $height = 480;
        $path = $this->faker->image('public/storage/photos', $width, $height, null, true, true, true, false);

        return [
            // 
            'photo_id' => Photo::factory(),
            'path' => $path,
            'url' => config('app.url').'/'.Str::after($path, 'public/'),
            'size' => rand(1111,9999),
            'width' => $width,
            'height' => $height,
        ];
    }
}
