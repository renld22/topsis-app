<?php

namespace App\Policies;

use App\Models\SubCriterion;
use App\Models\User;

class SubCriterionPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->role === 'admin';
    }

    public function view(User $user, SubCriterion $subCriterion): bool
    {
        return $user->role === 'admin';
    }

    public function create(User $user): bool
    {
        return $user->role === 'admin';
    }

    public function update(User $user, SubCriterion $subCriterion): bool
    {
        return $user->role === 'admin';
    }

    public function delete(User $user, SubCriterion $subCriterion): bool
    {
        return $user->role === 'admin';
    }

    public function restore(User $user, SubCriterion $subCriterion): bool
    {
        return $user->role === 'admin';
    }

    public function forceDelete(User $user, SubCriterion $subCriterion): bool
    {
        return $user->role === 'admin';
    }
}
