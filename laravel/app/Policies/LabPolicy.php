<?php

namespace App\Policies;

use App\Models\LabTechnician;
use App\Models\Investigation;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class LabPolicy
{
    /**
     * Determine if the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->labTechnician()->exists();
    }

    /**
     * Determine if the user can view the model.
     */
    public function view(User $user, Investigation $investigation): bool
    {
        return $user->labTechnician()->exists() &&
               ($investigation->StaffID === $user->labTechnician->StaffID ||
                is_null($investigation->StaffID));
    }

    /**
     * Determine if the user can update the investigation.
     */
    public function update(User $user, Investigation $investigation): bool
    {
        if (!$user->labTechnician()->exists()) {
            return false;
        }

        $technician = $user->labTechnician;

        // Check if investigation is assigned to this technician or unassigned
        $isAssigned = $investigation->StaffID === $technician->StaffID ||
                      (is_null($investigation->StaffID) && $investigation->Status === 'Pending');

        // Check if investigation can be updated based on status
        $canUpdateStatus = in_array($investigation->Status, ['Pending', 'Assigned', 'Processing']);

        // Check if technician is active
        $isTechnicianActive = $technician->IsActive;

        return $isAssigned && $canUpdateStatus && $isTechnicianActive;
    }

    /**
     * Determine if the user can assign investigation to themselves.
     */
    public function assign(User $user, Investigation $investigation): bool
    {
        if (!$user->labTechnician()->exists()) {
            return false;
        }

        $technician = $user->labTechnician;

        // Check conditions for assignment
        $isUnassigned = is_null($investigation->StaffID) && $investigation->Status === 'Pending';
        $isAssignedToOthers = $investigation->StaffID !== $technician->StaffID &&
                              $investigation->Status === 'Assigned';

        $isAssignable = $isUnassigned || $isAssignedToOthers;
        $isTechnicianAvailable = $technician->isAvailable();
        $isTechnicianActive = $technician->IsActive;

        return $isAssignable && $isTechnicianAvailable && $isTechnicianActive;
    }

    /**
     * Determine if the user can download the report.
     */
    public function downloadReport(User $user, Investigation $investigation): bool
    {
        if (!$user->labTechnician()->exists()) {
            return false;
        }

        $technician = $user->labTechnician;

        // Check if report exists and investigation is completed
        $hasReport = !empty($investigation->DigitalReport);
        $isCompleted = $investigation->Status === 'Completed';

        // Check if technician is assigned to this investigation or has proper access
        $hasAccess = $investigation->StaffID === $technician->StaffID ||
                     $user->hasRole('super_admin') ||
                     $user->hasRole('lab_manager');

        return $hasReport && $isCompleted && $hasAccess && $technician->IsActive;
    }

    /**
     * Determine if the user can view investigation history.
     */
    public function viewHistory(User $user): bool
    {
        return $user->labTechnician()->exists() && $user->labTechnician->IsActive;
    }

    /**
     * Determine if the user can view dashboard.
     */
    public function viewDashboard(User $user): bool
    {
        return $user->labTechnician()->exists() && $user->labTechnician->IsActive;
    }

    /**
     * Determine if the user can mark investigation as completed.
     */
    public function complete(User $user, Investigation $investigation): bool
    {
        if (!$user->labTechnician()->exists()) {
            return false;
        }

        $technician = $user->labTechnician;

        // Must be assigned to this technician
        $isAssigned = $investigation->StaffID === $technician->StaffID;

        // Must be in processing status
        $isProcessing = $investigation->Status === 'Processing';

        // Must have result summary
        $hasResults = !empty($investigation->ResultSummary);

        return $isAssigned && $isProcessing && $hasResults && $technician->IsActive;
    }

    /**
     * Determine if the user can cancel an investigation.
     */
    public function cancel(User $user, Investigation $investigation): bool
    {
        if (!$user->labTechnician()->exists()) {
            return false;
        }

        $technician = $user->labTechnician;

        // Can only cancel if assigned to technician and not already completed
        $isAssigned = $investigation->StaffID === $technician->StaffID;
        $isNotCompleted = !in_array($investigation->Status, ['Completed', 'Cancelled']);

        return $isAssigned && $isNotCompleted && $technician->IsActive;
    }

    /**
     * Determine if the user can upload report for investigation.
     */
    public function uploadReport(User $user, Investigation $investigation): bool
    {
        if (!$user->labTechnician()->exists()) {
            return false;
        }

        $technician = $user->labTechnician;

        // Must be assigned to this technician
        $isAssigned = $investigation->StaffID === $technician->StaffID;

        // Must be in processing status
        $isProcessing = $investigation->Status === 'Processing';

        return $isAssigned && $isProcessing && $technician->IsActive;
    }

    /**
     * Check if user can view sensitive patient information.
     */
    public function viewSensitiveInfo(User $user, Investigation $investigation): bool
    {
        if (!$user->labTechnician()->exists()) {
            return false;
        }

        $technician = $user->labTechnician;

        // Only show sensitive info if assigned or investigation is in process
        $hasAccess = $investigation->StaffID === $technician->StaffID ||
                     in_array($investigation->Status, ['Assigned', 'Processing', 'Completed']);

        return $hasAccess && $technician->IsActive;
    }

    /**
     * Determine if the user can reassign investigation to another technician.
     */
    public function reassign(User $user, Investigation $investigation): bool
    {
        if (!$user->labTechnician()->exists()) {
            return false;
        }

        $technician = $user->labTechnician;

        // Only lab managers or supervisors can reassign
        $hasReassignPermission = $user->hasRole('lab_manager') ||
                                 $user->hasRole('lab_supervisor');

        // Must be assigned or assignable
        $isAssigned = !is_null($investigation->StaffID);
        $isNotCompleted = !in_array($investigation->Status, ['Completed', 'Cancelled']);

        return $hasReassignPermission && $isAssigned && $isNotCompleted && $technician->IsActive;
    }
}
