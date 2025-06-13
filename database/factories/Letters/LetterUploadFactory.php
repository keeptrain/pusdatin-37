<?php

namespace Database\Factories\Letters;

use App\Models\Documents\DocumentUpload;
use App\Models\Letters\Letter;
// use App\Models\Letters\LetterUpload;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class LetterUploadFactory extends Factory
{
    protected $model = DocumentUpload::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'letter_id' => Letter::factory(),
            'part_name' => 'part1',
            'file_path' => '/path/to/file.pdf',
            'version' => 1,
            'needs_revision' => false,
            'revision_note' => null,
        ];
    }

    // /**
    //  * Configure the model factory.
    //  */
    // public function configure(): static
    // {
    //     return $this->afterMaking(function (LetterUpload $letterUpload) {
    //         Letter::factory()->create(
    //             ['letterable_type' => LetterUpload::class, 'letterable_id' => $letterUpload->id]
    //         );
    //     })->afterCreating(function (Letter $letter) {
    //         // ...
    //     });
    // }


}
