<?php

namespace App\Models\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class JobListingScope implements Scope
{
    /**
     * Apply the scope to a given Eloquent query builder.
     */
    public function apply(Builder $builder, Model $model): void
    {
        //
    }

    public function scopeByPublisher($query, $publisher)
    {
        return $query->when($publisher, function ($query) use ($publisher) {
            return $query->where('publisher', $publisher);
        });
    }

}
