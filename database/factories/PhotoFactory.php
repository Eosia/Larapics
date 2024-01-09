<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\{
    Photo,
    Album,
};

use Illuminate\Http\File;
Use Str, Storage;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Photo>
 */
class PhotoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {

        $image = $this->faker->image();
        $imageFile = new File($image);

        return [
            //
            'album_id' => Album::factory(),
            'title' => $this->faker->sentence,
            'thumbnail_path' => $path = 'storage/'.Storage::disk('public')->putFile('photos', $imageFile),
            'thumbnail_url' => config('app.url').'/'.Str::after($path, 'public/'),
        ];
    }
}
