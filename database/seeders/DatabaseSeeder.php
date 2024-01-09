<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\{
    User,
    Album,
    Photo,
    Source
};
use Illuminate\Database\Eloquent\Factories\Sequence;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        User::factory(5)
        ->has(Album::factory()->count(2)
            ->has(Photo::factory()->count(3)
                ->state(new Sequence(
                    ['active' => true,],
                    ['active' => false],
                    ))
                ->has(Source::factory()->count(1))))
                ->create();
    }
}
