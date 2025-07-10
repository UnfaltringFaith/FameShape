<?php

namespace App\Policies;

use App\Models\Post;
use App\Models\User;
use Illuminate\Auth\Access\Response;
use Illuminate\Support\Facades\Log;
use LDAP\Result;

class PostPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Post $post)
    {
        // Отладка
        Log::info('Policy debug', [
            'user_id' => $user->id,
            'post_user_id' => $post->user_id,
            'is_published' => $post->is_published,
            'post_id' => $post->id
        ]);
        
        // Свои посты всегда можно смотреть
        if ($post->user_id == $user->id) {
            Log::info('Access granted: own post');
            return true;
        }
        
        // Чужие посты - только если опубликованы
        if ($post->is_published) {
            Log::info('Access granted: published post');
            return true;
        }
        
        Log::info('Access denied: unpublished foreign post');
        return Response::deny('You do not have permission to view this post.');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Post $post): bool
    {
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Post $post): bool
    {
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Post $post): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Post $post): bool
    {
        return false;
    }
}
