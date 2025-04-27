<?php

namespace Database\Factories\Letters;

use App\Models\User;
use App\Models\Letters\Letter;
use App\Models\Letters\LetterDirect;
use App\Models\Letters\LetterUpload;
use App\Models\Letters\RequestStatusTrack;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class LetterFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var class-string<\Illuminate\Database\Eloquent\Model>
     */
    protected $models = Letter::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $letterableTypes = [
            LetterUpload::class,
            LetterDirect::class,
        ];

        $status = Letter::getDefaultStateFor('status');

        $letterableType = fake()->randomElement($letterableTypes);
        $letterableId = null;

        if ($letterableType === LetterUpload::class) {
            $letterableId = LetterUpload::factory();
        } elseif ($letterableType === LetterUpload::class) {
            $letterableId = LetterUpload::factory();
        }

        return [
            'user_id' => User::factory(),
            'letterable_type' => LetterUpload::class,
            'letterable_id' => 0,
            'title' => fake()->title(),
            'responsible_person' => fake()->userName(),
            'reference_number' => fake()->numberBetween(0, 5),
            'status' => $status,
            'current_revision' => 1,
            'active_revision' => false,
            'deleted_at' => null,
        ];
    }

    /**
     * Configure the model factory.
     */
    public function configure(): static
    {
        return $this->afterCreating(function (Letter $letter) {
            LetterUpload::factory()->count(3)->create(['letter_id' => $letter->id]);
            RequestStatusTrack::factory()->create(['letter_id' => $letter->id]);
        });
    }
}
