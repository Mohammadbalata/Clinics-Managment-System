<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;

trait Filterable
{
    /**
     * Apply filters to the query builder.
     *
     * @param Builder $builder
     * @param array $filters
     */
    public function scopeFilter(Builder $builder, array $filters)
    {
        foreach ($filters as $key => $value) {
            if ($value) {
                $builder->where("{$this->getTable()}.{$key}", 'LIKE', "%{$value}%");
            }
        }
    }
}
