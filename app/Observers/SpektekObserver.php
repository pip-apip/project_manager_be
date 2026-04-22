<?php

namespace App\Observers;

use App\Models\ActivityCategory;
use App\Models\Project;

class SpektekObserver
{
    /**
     * Handle the ActivityCategory "created" event.
     */
    public function created(ActivityCategory $activityCategory): void
    {
        //
    }

    /**
     * Handle the ActivityCategory "updated" event.
     */
    public function updated(ActivityCategory $activityCategory): void
    {
        $project = Project::with('company', 'projectLeader')->find($activityCategory->project_id);

        $project->update([
            'progress' => $project->activityCategories()->avg('value')
        ]);
    }

    /**
     * Handle the ActivityCategory "deleted" event.
     */
    public function deleted(ActivityCategory $activityCategory): void
    {
        //
    }

    /**
     * Handle the ActivityCategory "restored" event.
     */
    public function restored(ActivityCategory $activityCategory): void
    {
        //
    }

    /**
     * Handle the ActivityCategory "force deleted" event.
     */
    public function forceDeleted(ActivityCategory $activityCategory): void
    {
        //
    }
}
