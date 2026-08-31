<?php

namespace App\Enums;

enum SuggestionStatus: string
{
    case Draft = 'draft';
    case Sent = 'sent';
    case UnderReview = 'under_review';
    case Pending = 'pending';
    case Approved = 'approved';
    case Published = 'published';
    case Rejected = 'rejected';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    public function label(): string
    {
        return __('suggestions.status.'.$this->value);
    }
}
