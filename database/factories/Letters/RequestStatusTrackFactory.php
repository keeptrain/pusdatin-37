<?php

namespace Database\Factories\Letters;

use App\Models\User;
use App\Models\Letters\Letter;
use App\Models\Letters\RequestStatusTrack;
use Illuminate\Database\Eloquent\Factories\Factory;

class RequestStatusTrackFactory extends Factory
{
    protected $models = RequestStatusTrack::class;

    public function definition()
    {
        return [
            'letter_id' => Letter::factory(),
            'action' => 'blabla',
            'notes' => 'blabla',
            'created_by' => User::factory(),
        ];
    }
}