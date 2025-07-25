<?php

/**
 * This file contains PHPDoc annotations for IDE support
 * These annotations help IDEs understand dynamic properties and methods
 */

namespace App\Models;

/**
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string $phone
 * @property string $address
 * @property int $tenant_id
 * @property bool $is_active
 * @property float $credit_limit
 * @property float $total_debt
 * @property float $available_credit
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * 
 * @method bool hasPermissionTo(string $permission)
 * @method bool isOverCreditLimit()
 * @method \Illuminate\Database\Eloquent\Relations\HasMany salesOrders()
 */
class Customer extends \Illuminate\Database\Eloquent\Model
{
    // This class is for documentation purposes only
}

/**
 * @property int $id
 * @property string $name
 * @property string $domain
 * @property int $max_customers
 * @property int $remaining_customer_slots
 * @property float $customer_usage_percentage
 * @property string $customer_limit_status
 * @property string $customer_limit_color
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * 
 * @method \Illuminate\Database\Eloquent\Relations\HasMany customers()
 */
class Tenant extends \Illuminate\Database\Eloquent\Model
{
    // This class is for documentation purposes only
}

/**
 * @property int $id
 * @property string $invoice_number
 * @property int $customer_id
 * @property float $total_amount
 * @property string $status
 * @property \Carbon\Carbon $invoice_date
 * @property \Carbon\Carbon $due_date
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * 
 * @method \Illuminate\Database\Eloquent\Relations\HasMany items()
 * @method \Illuminate\Database\Eloquent\Relations\BelongsTo customer()
 */
class Invoice extends \Illuminate\Database\Eloquent\Model
{
    // This class is for documentation purposes only
}

/**
 * @property int $id
 * @property float $amount
 * @property string $payment_method
 * @property string $status
 * @property \Carbon\Carbon $payment_date
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 */
class Payment extends \Illuminate\Database\Eloquent\Model
{
    // This class is for documentation purposes only
}

/**
 * Extended Request class with common form fields
 * 
 * @property string $date_from
 * @property string $date_to
 * @property string $status
 * @property string $payment_method
 * @property string $search
 * @property int $per_page
 */
class ExtendedRequest extends \Illuminate\Http\Request
{
    // This class is for documentation purposes only
}
