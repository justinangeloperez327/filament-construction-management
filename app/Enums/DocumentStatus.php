<?php

namespace App\Enums;

use App\Enums\Concerns\ProvidesOptions;

enum DocumentStatus: string
{
    use ProvidesOptions;

    case Draft = 'draft';
    case ForReview = 'for_review';
    case ForApproval = 'for_approval';
    case Approved = 'approved';
    case ApprovedWithComments = 'approved_with_comments';
    case ReviseAndResubmit = 'revise_and_resubmit';
    case Rejected = 'rejected';
    case Superseded = 'superseded';
    case AsBuilt = 'as_built';

    public function label(): string
    {
        return match ($this) {
            self::Draft => 'Draft',
            self::ForReview => 'For Review',
            self::ForApproval => 'For Approval',
            self::Approved => 'Approved',
            self::ApprovedWithComments => 'Approved With Comments',
            self::ReviseAndResubmit => 'Revise and Resubmit',
            self::Rejected => 'Rejected',
            self::Superseded => 'Superseded',
            self::AsBuilt => 'As Built',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::Draft, self::Superseded => 'gray',
            self::ForReview, self::ForApproval => 'warning',
            self::Approved, self::AsBuilt => 'success',
            self::ApprovedWithComments => 'info',
            self::ReviseAndResubmit, self::Rejected => 'danger',
        };
    }
}
