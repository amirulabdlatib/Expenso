<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Category;

class CategoryPolicy
{

    public function view(User $user, Category $category): bool
    {
        return $user->id === $category->user_id;
    }

    public function edit(User $user, Category $category): bool
    {
        return $user->id === $category->user_id;
    }

    public function update(User $user, Category $category): bool
    {
        return $user->id === $category->user_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Category $category): bool
    {
        return $user->id === $category->user_id;
    }
}
