<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class WarehouseLocation extends Model
{
    use HasFactory;

    protected $fillable = [
        'warehouse_id',
        'code',
        'name',
        'zone',
        'aisle',
        'shelf',
        'level',
        'position',
        'type',
        'description',
        'capacity',
        'used_capacity',
        'is_active',
        'properties',
    ];

    protected $casts = [
        'capacity' => 'decimal:2',
        'used_capacity' => 'decimal:2',
        'is_active' => 'boolean',
        'properties' => 'array',
    ];

    // Relationships
    public function warehouse(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function inventory(): HasMany
    {
        return $this->hasMany(Inventory::class, 'location_id');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    public function scopeByZone($query, $zone)
    {
        return $query->where('zone', $zone);
    }

    // Methods
    public function generateCode()
    {
        $parts = [];

        if ($this->zone) $parts[] = $this->zone;
        if ($this->aisle) $parts[] = $this->aisle;
        if ($this->shelf) $parts[] = $this->shelf;
        if ($this->level) $parts[] = $this->level;
        if ($this->position) $parts[] = $this->position;

        return implode('-', $parts);
    }

    public function getFullPath()
    {
        $path = [];

        if ($this->zone) $path[] = "منطقة {$this->zone}";
        if ($this->aisle) $path[] = "ممر {$this->aisle}";
        if ($this->shelf) $path[] = "رف {$this->shelf}";
        if ($this->level) $path[] = "مستوى {$this->level}";
        if ($this->position) $path[] = "موقع {$this->position}";

        return implode(' > ', $path);
    }

    public function getCapacityUsagePercentage()
    {
        if ($this->capacity > 0) {
            return round(($this->used_capacity / $this->capacity) * 100, 2);
        }
        return 0;
    }

    public function getAvailableCapacity()
    {
        return $this->capacity - $this->used_capacity;
    }

    public function isAvailable($requiredCapacity = 0)
    {
        return $this->is_active && $this->getAvailableCapacity() >= $requiredCapacity;
    }

    public function updateUsedCapacity()
    {
        $this->used_capacity = $this->inventory()->sum('quantity');
        $this->save();
    }

    public function getTypeLabel()
    {
        $types = [
            'zone' => 'منطقة',
            'aisle' => 'ممر',
            'shelf' => 'رف',
            'bin' => 'صندوق',
            'position' => 'موقع',
        ];

        return $types[$this->type] ?? $this->type;
    }

    public function hasProperty($property)
    {
        return isset($this->properties[$property]) && $this->properties[$property];
    }

    public function isTemperatureControlled()
    {
        return $this->hasProperty('temperature_controlled');
    }

    public function isHazardous()
    {
        return $this->hasProperty('hazardous');
    }

    public function getTemperatureRange()
    {
        return $this->properties['temperature_range'] ?? null;
    }
}
