<?php

namespace App\Services;

use App\Enums\SuggestionStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class SuggestionService
{
    public function createSuggestion(string $modelClass, array $data): Model
    {
        $data['user_id'] = Auth::id();
        $data['submitted_by_name'] = Auth::check()
            ? Auth::user()->name
            : $data['submitted_by_name'] ?? null;
        $data['submitted_by_email'] = Auth::check()
            ? Auth::user()->email
            : $data['submitted_by_email'] ?? null;
        $data['suggestion_status'] = SuggestionStatus::Pending->value;

        return $modelClass::create($data);
    }
}
