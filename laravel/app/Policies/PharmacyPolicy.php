<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class PharmacyPolicy
{
    use HandlesAuthorization;

    /**
     * Determine if the user can view any pharmacy data.
     */
    public function viewAny(User $user)
    {
        return $user->isPharmacy() || $user->hasRole('pharmacy');
    }

    /**
     * Determine if the user can view medicine inventory.
     */
    public function viewInventory(User $user)
    {
        return $this->viewAny($user);
    }

    /**
     * Determine if the user can manage medicine inventory.
     */
    public function manageInventory(User $user)
    {
        return $user->isPharmacy() || $user->hasRole('pharmacy');
    }

    /**
     * Determine if the user can view prescriptions.
     */
    public function viewPrescriptions(User $user)
    {
        return $this->viewAny($user);
    }

    /**
     * Determine if the user can dispense prescriptions.
     */
    public function dispensePrescriptions(User $user)
    {
        return $user->isPharmacy() || $user->hasRole('pharmacy');
    }

    /**
     * Determine if the user can generate pharmacy reports.
     */
    public function generateReports(User $user)
    {
        return $user->isPharmacy() || $user->hasRole('pharmacy');
    }

    /**
     * Determine if the user can add new medicines.
     */
    public function addMedicine(User $user)
    {
        return $user->isPharmacy() || $user->hasRole('pharmacy');
    }

    /**
     * Determine if the user can update medicine stock.
     */
    public function updateStock(User $user)
    {
        return $user->isPharmacy() || $user->hasRole('pharmacy');
    }

    /**
     * Determine if the user can manage suppliers.
     */
    public function manageSuppliers(User $user)
    {
        return $user->isPharmacy() || $user->hasRole('pharmacy');
    }

    /**
     * Determine if the user can manage medicine categories.
     */
    public function manageCategories(User $user)
    {
        return $user->isPharmacy() || $user->hasRole('pharmacy');
    }

    /**
     * Determine if the user can view dispensing history.
     */
    public function viewDispensingHistory(User $user)
    {
        return $this->viewAny($user);
    }

    /**
     * Determine if the user can view low stock alerts.
     */
    public function viewLowStockAlerts(User $user)
    {
        return $this->viewAny($user);
    }

    /**
     * Determine if the user can view expiring medicines.
     */
    public function viewExpiringMedicines(User $user)
    {
        return $this->viewAny($user);
    }

    /**
     * Determine if the user can adjust medicine prices.
     */
    public function adjustPrices(User $user)
    {
        // Only senior pharmacists or pharmacy managers
        return ($user->isPharmacy() && $user->hasPermission('adjust_prices')) ||
               $user->hasRole('pharmacy_manager');
    }

    /**
     * Determine if the user can delete medicines.
     */
    public function deleteMedicine(User $user)
    {
        // Only pharmacy managers
        return $user->hasRole('pharmacy_manager');
    }

    /**
     * Determine if the user can process returns.
     */
    public function processReturns(User $user)
    {
        return $user->isPharmacy() || $user->hasRole('pharmacy');
    }

    /**
     * Determine if the user can view patient billing.
     */
    public function viewBilling(User $user)
    {
        return $this->viewAny($user);
    }

    /**
     * Determine if the user can generate financial reports.
     */
    public function generateFinancialReports(User $user)
    {
        return $user->hasRole('pharmacy_manager') || $user->hasRole('admin');
    }
}
