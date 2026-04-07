<?php

namespace App\Policies;

use App\Models\ClinicalRecord;
use App\Models\User;

class ClinicalRecordPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->canAccessModule('clinical');
    }

    public function view(User $user, ClinicalRecord $record): bool
    {
        return $user->canAccessModule('clinical');
    }

    public function create(User $user): bool
    {
        return $user->canAccessModule('clinical');
    }

    public function delete(User $user, ClinicalRecord $record): bool
    {
        return $user->canAccessModule('configuration');
    }
}
