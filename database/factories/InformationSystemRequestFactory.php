<?php

namespace Database\Factories;

use App\States\InformationSystem\ApprovedKapusdatin;
use App\States\InformationSystem\ApprovedKasatpel;
use App\States\InformationSystem\Disposition;
use App\States\InformationSystem\Pending;
use App\States\InformationSystem\Process;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\InformationSystemRequest;
use App\Models\User;
use Carbon\Carbon;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\InformationSystemRequest>
 */
class InformationSystemRequestFactory extends Factory
{
    protected $model = InformationSystemRequest::class;

    public function definition(): array
    {
        // Get the current year's start and end
        $startOfYear = now()->startOfYear();
        $endOfYear = now()->endOfYear();

        // Generate a random timestamp between start and end of year
        $randomTimestamp = mt_rand($startOfYear->timestamp, $endOfYear->timestamp);

        // Create Carbon instance from random timestamp
        $randomDate = Carbon::createFromTimestamp($randomTimestamp);

        // Add random time
        $createdAt = $randomDate->copy()->setTime(
            mt_rand(0, 23),  // hours
            mt_rand(0, 59),  // minutes
            mt_rand(0, 59)   // seconds
        );

        // For updated_at, ensure it's after created_at but still within the current year
        $maxUpdatedAt = min($createdAt->copy()->addDays(30), $endOfYear);
        $updatedAt = $createdAt->copy()->addDays(mt_rand(0, $createdAt->diffInDays($maxUpdatedAt)))
            ->setTime(
                mt_rand($createdAt->hour, 23),
                mt_rand(0, 59),
                mt_rand(0, 59)
            );

        return [
            'user_id' => $this->faker->numberBetween(7, 9),
            'title' => $this->faker->sentence(3),
            'reference_number' => 'REF-' . $this->faker->unique()->numerify('####-####'),
            'active_checking' => 2,
            'current_division' => $this->faker->numberBetween(3, 4),
            'active_revision' => false,
            'need_review' => false,
            'created_at' => $createdAt,
            'updated_at' => $updatedAt,
        ];
    }

    public function configure()
    {
        return $this->afterCreating(function (InformationSystemRequest $request) {
            $initialTime = now()->startOfDay(); // Use now() instead of Carbon::now() for consistency
            $this->logStatusWithTimestamp($request, Pending::class, $initialTime);
            
            // Optionally transition through other states
            $daysAgo = 0;
            $statuses = [
                Disposition::class,
                ApprovedKasatpel::class,
                ApprovedKapusdatin::class,
                Process::class,
            ];
            
            foreach ($statuses as $status) {
                $daysAgo += rand(1, 7); // 1-7 days between status changes
                $this->logStatusWithTimestamp(
                    $request, 
                    $status, 
                    now()->subDays($daysAgo)
                );
            }
        });
    }

    public function transitionToStepped()
    {
        return $this->state(function (array $attributes) {
            $statuses = [
                Disposition::class,
                ApprovedKasatpel::class,
                ApprovedKapusdatin::class,
                Process::class,
            ];
            
            $currentStatus = $attributes['status'] ?? Pending::class;
            $currentIndex = array_search($currentStatus, $statuses);
            $nextIndex = $currentIndex === false ? 0 : min($currentIndex + 1, count($statuses) - 1);
            
            return [
                'status' => $statuses[$nextIndex],
                'updated_at' => now()->addDays(rand(1, 30))
            ];
        });
    }

    private function logStatusWithTimestamp(InformationSystemRequest $request, string $statusClass, Carbon $timestamp): void
    {
        $request->status->transitionTo($statusClass);

        $userName = optional(User::find($request->user_id))->name ?? 'System';

        $request->trackingHistorie()->create([
            'action' => $request->status->trackingMessage($request->current_division),
            'created_by' => $userName,
            'created_at' => $timestamp,
            'updated_at' => $timestamp,
        ]);
    }
}