<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Schema;

trait ConditionalSoftDeletes
{
    /**
     * Boot the trait
     */
    public static function bootConditionalSoftDeletes()
    {
        // Only use SoftDeletes if the deleted_at column exists
        if (static::hasDeletedAtColumn()) {
            static::bootSoftDeletes();
        }
    }

    /**
     * Check if the deleted_at column exists
     */
    public static function hasDeletedAtColumn(): bool
    {
        try {
            $instance = new static;
            return Schema::hasColumn($instance->getTable(), 'deleted_at');
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Get the name of the "deleted at" column.
     */
    public function getDeletedAtColumn()
    {
        return static::hasDeletedAtColumn() ? 'deleted_at' : null;
    }

    /**
     * Determine if the model instance has been soft-deleted.
     */
    public function trashed()
    {
        return static::hasDeletedAtColumn() && !is_null($this->{$this->getDeletedAtColumn()});
    }

    /**
     * Force a hard delete on a soft deleted model.
     */
    public function forceDelete()
    {
        if (static::hasDeletedAtColumn()) {
            return parent::forceDelete();
        }
        
        return $this->delete();
    }

    /**
     * Restore a soft-deleted model instance.
     */
    public function restore()
    {
        if (static::hasDeletedAtColumn()) {
            return parent::restore();
        }
        
        return true;
    }

    /**
     * Perform the actual delete query on this model instance.
     */
    protected function performDeleteOnModel()
    {
        if (static::hasDeletedAtColumn()) {
            return $this->runSoftDelete();
        }
        
        return $this->newModelQuery()->where($this->getKeyName(), $this->getKey())->delete();
    }

    /**
     * Perform the actual soft delete query on this model instance.
     */
    protected function runSoftDelete()
    {
        $query = $this->setKeysForSaveQuery($this->newModelQuery());

        $time = $this->freshTimestamp();

        $columns = [$this->getDeletedAtColumn() => $this->fromDateTime($time)];

        $this->{$this->getDeletedAtColumn()} = $time;

        if ($this->timestamps && ! is_null($this->getUpdatedAtColumn())) {
            $this->{$this->getUpdatedAtColumn()} = $time;

            $columns[$this->getUpdatedAtColumn()] = $this->fromDateTime($time);
        }

        $query->update($columns);

        $this->syncOriginalAttributes(array_keys($columns));

        $this->fireModelEvent('trashed', false);
    }
}
