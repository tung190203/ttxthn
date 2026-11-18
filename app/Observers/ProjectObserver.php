<?php

namespace App\Observers;

use App\Models\Project;

use App\Notifications\ProjectUpdatedNotification;

class ProjectObserver
{
    /**
     * Handle the Project "created" event.
     */
    public function created(Project $project): void
    {
        //
    }

    /**
     * Handle the Project "updated" event.
     */
    public function updated(Project $project): void
    {
        $watchedFields = [
            'name', 
            'status', 
            'legal_status_desc', 
            'is_invest', 
            'land_clearance', 
            'technical_infrastructure', 
            'investment_incentives',
            'estimated_cost'
        ];

        if ($project->wasChanged($watchedFields)) {
            $changes = [];
            foreach ($watchedFields as $field) {
                if ($project->wasChanged($field)) {
                    $changes[$field] = [
                        'old' => $project->getOriginal($field),
                        'new' => $project->{$field},
                    ];
                }
            }

            // Find all active guests who bookmarked this project
            $interests = $project->interests()->with('guest')->get();
            foreach ($interests as $interest) {
                $guest = $interest->guest;
                if ($guest && $guest->status === 'active') {
                    $guest->notify(new ProjectUpdatedNotification($project, $changes));
                }
            }
        }
    }

    /**
     * Handle the Project "deleted" event.
     */
    public function deleted(Project $project): void
    {
        //
    }

    /**
     * Handle the Project "restored" event.
     */
    public function restored(Project $project): void
    {
        //
    }

    /**
     * Handle the Project "force deleted" event.
     */
    public function forceDeleted(Project $project): void
    {
        //
    }
}
