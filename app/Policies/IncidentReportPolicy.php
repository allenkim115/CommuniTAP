<?php

namespace App\Policies;

use App\Models\User;
use App\Models\UserIncidentReport;
use Illuminate\Auth\Access\HandlesAuthorization;

class IncidentReportPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any incident reports.
     */
    public function viewAny(User $user)
    {
        return true; // Users can view their own reports, admins can view all
    }

    /**
     * Determine whether the user can view the incident report.
     */
    public function view(User $user, UserIncidentReport $incidentReport)
    {
        // Users can view their own reports, admins can view all
        return $user->isAdmin() || $incidentReport->FK1_reporterId === $user->userId;
    }

    /**
     * Determine whether the user can create incident reports.
     */
    public function create(User $user)
    {
        return true; // All authenticated users can create reports
    }

    /**
     * Determine whether the user can update the incident report.
     */
    public function update(User $user, UserIncidentReport $incidentReport)
    {
        // Only admins can update incident reports
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can delete the incident report.
     */
    public function delete(User $user, UserIncidentReport $incidentReport)
    {
        // Only admins can delete incident reports
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can restore the incident report.
     */
    public function restore(User $user, UserIncidentReport $incidentReport)
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can permanently delete the incident report.
     */
    public function forceDelete(User $user, UserIncidentReport $incidentReport)
    {
        return $user->isAdmin();
    }
}
