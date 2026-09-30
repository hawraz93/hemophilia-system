<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CodeGenerator
{
    /**
     * Generate the next sequential code for the current year, e.g. PAT-2026-0001.
     *
     * @param  class-string<Model>  $modelClass
     */
    public static function next(string $modelClass, string $column, string $prefix): string
    {
        $yearPrefix = $prefix.'-'.date('Y').'-';

        $query = $modelClass::query();
        if (in_array(SoftDeletes::class, class_uses_recursive($modelClass), true)) {
            $query->withTrashed();
        }

        $last = $query->where($column, 'like', $yearPrefix.'%')
            ->pluck($column)
            ->map(fn (string $code) => (int) substr($code, strlen($yearPrefix)))
            ->max() ?? 0;

        return $yearPrefix.str_pad((string) ($last + 1), 4, '0', STR_PAD_LEFT);
    }
}
