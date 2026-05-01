<?php

namespace App\Models\Traits;

use App\Enums\SuggestionStatus;
use Illuminate\Database\Eloquent\Builder;

trait SuggestionFilterable
{
    public function scopeFilterSuggestionStatus(Builder $query, ?string $status): Builder
    {
        if (! $status) {
            return $query;
        }

        $statusEnum = SuggestionStatus::tryFrom($status);

        if (! $statusEnum) {
            return $query;
        }

        return $query->where('suggestion_status', $statusEnum->value);
    }

    public function scopeSearchSuggestion(Builder $query, ?string $search): Builder
    {
        if (! $search || trim($search) === '') {
            return $query;
        }

        return $query->where(function (Builder $query) use ($search) {
            $query->where('name', 'like', "%{$search}%")
                ->orWhere('city', 'like', "%{$search}%")
                ->orWhere('submitted_by_name', 'like', "%{$search}%")
                ->orWhere('submitted_by_email', 'like', "%{$search}%");
        });
    }
}
