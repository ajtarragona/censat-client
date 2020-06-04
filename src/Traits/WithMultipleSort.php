<?php

namespace Ajtarragona\Censat\Traits;

trait WithMultipleSort
{
    /** Scope para añadir varios sort de forma dinamica*/
    public function scopeWithOrder($query, $sort)
    {
        foreach ($sort as $column => $direction) {
            $query->orderBy($column, $direction);
        }

        return $query;
    }
}