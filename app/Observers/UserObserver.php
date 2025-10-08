<?php

namespace App\Observers;

use App\Models\User;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;

class UserObserver
{
    /**
     * Handle the User "created" event.
     */
    public function created(User $user): void
    {
        $actor = Auth::user(); 

        ActivityLog::create([
            'user_id' => $actor ? $actor->id : null,
            'action' => 'CREATE USER',
            'description' => "User '{$user->name}' (Role: {$user->role}) telah dibuat oleh '{$actor->name}'.",
        ]);
    }

    public function updated(User $user): void
    {
        $actor = Auth::user();

        $changedFields = $user->getDirty(); 
        unset($changedFields['updated_at']);

        if (empty($changedFields)) {
            return; 
        }

        $description = "Data user '{$user->name}' telah diperbarui oleh '{$actor->name}'. Perubahan: ";
        foreach ($changedFields as $field => $newValue) {
            $oldValue = $user->getOriginal($field);
            $description .= "{$field} dari '{$oldValue}' menjadi '{$newValue}'; ";
        }

        ActivityLog::create([
            'user_id' => $actor ? $actor->id : null,
            'action' => 'UPDATE USER',
            'description' => rtrim($description, '; '), 
        ]);
    }

    /**
     * Handle the User "deleted" event.
     */
    public function deleted(User $user): void
    {
        $actor = Auth::user();

        ActivityLog::create([
            'user_id' => $actor ? $actor->id : null,
            'action' => 'DELETE USER',
            'description' => "User '{$user->name}' telah dihapus oleh '{$actor->name}'.",
        ]);
    }

    /**
     * Handle the User "restored" event.
     */
    public function restored(User $user): void
    {
        
    }

    /**
     * Handle the User "force deleted" event.
     */
    public function forceDeleted(User $user): void
    {
        
    }
}