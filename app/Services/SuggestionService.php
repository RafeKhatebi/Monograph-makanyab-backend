<?php

namespace App\Services;

use App\Enums\PlaceStatus;
use App\Enums\SuggestionStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class SuggestionService
{
    public function createSuggestion(string $modelClass, array $data): Model
    {
        $categoryField = $modelClass === \App\Models\PlaceSuggestion::class
            ? 'place_category_id'
            : 'service_category_id';

        $duplicate = $modelClass::query()
            ->where($categoryField, $data[$categoryField])
            ->where('city', $data['city'])
            ->whereRaw('LOWER(name) = LOWER(?)', [$data['name']])
            ->whereIn('suggestion_status', [
                SuggestionStatus::Pending->value,
                SuggestionStatus::Approved->value,
            ])
            ->exists();

        if ($duplicate) {
            throw ValidationException::withMessages([
                'name' => 'A suggestion for this name, category, and city already exists.',
            ]);
        }

        $data['user_id'] = Auth::id();
        $data['submitted_by_name'] = Auth::check()
            ? Auth::user()->name
            : $data['submitted_by_name'] ?? null;
        $data['submitted_by_email'] = Auth::check()
            ? Auth::user()->email
            : $data['submitted_by_email'] ?? null;
        $data['status'] = $data['status'] ?? PlaceStatus::Open->value;
        $data['suggestion_status'] = SuggestionStatus::Pending->value;

        return $modelClass::create($data);
    }
}
