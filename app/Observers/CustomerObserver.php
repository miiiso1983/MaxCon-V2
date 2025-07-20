<?php

namespace App\Observers;

use App\Models\Customer;

class CustomerObserver
{
    /**
     * Handle the Customer "created" event.
     */
    public function created(Customer $customer): void
    {
        // Update tenant customer count
        if ($customer->tenant) {
            $customer->tenant->updateCustomerCount();
        }
    }

    /**
     * Handle the Customer "creating" event.
     */
    public function creating(Customer $customer): void
    {
        // Check if tenant can add more customers
        if ($customer->tenant && !$customer->tenant->canAddCustomers()) {
            throw new \Exception('تم الوصول للحد الأقصى من العملاء المسموح به لهذا المستأجر');
        }
    }

    /**
     * Handle the Customer "deleted" event.
     */
    public function deleted(Customer $customer): void
    {
        // Update tenant customer count
        if ($customer->tenant) {
            $customer->tenant->updateCustomerCount();
        }
    }

    /**
     * Handle the Customer "restored" event.
     */
    public function restored(Customer $customer): void
    {
        // Check if tenant can add more customers when restoring
        if ($customer->tenant && !$customer->tenant->canAddCustomers()) {
            throw new \Exception('تم الوصول للحد الأقصى من العملاء المسموح به لهذا المستأجر');
        }

        // Update tenant customer count
        if ($customer->tenant) {
            $customer->tenant->updateCustomerCount();
        }
    }

    /**
     * Handle the Customer "force deleted" event.
     */
    public function forceDeleted(Customer $customer): void
    {
        // Update tenant customer count
        if ($customer->tenant) {
            $customer->tenant->updateCustomerCount();
        }
    }
}
