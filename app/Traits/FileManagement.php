<?php

namespace App\Traits;

use Illuminate\Support\Facades\Storage;

trait FileManagement
{
    /**
     * Supprimer les fichiers associés à une publication
     */
    protected function deletePublicationFiles($publication)
    {
        if ($publication->file_path && Storage::disk('public')->exists($publication->file_path)) {
            Storage::disk('public')->delete($publication->file_path);
        }
        if ($publication->cover_image && Storage::disk('public')->exists($publication->cover_image)) {
            Storage::disk('public')->delete($publication->cover_image);
        }
    }

    /**
     * Supprimer l'avatar d'un utilisateur
     */
    protected function deleteUserAvatar($user)
    {
        if ($user->avatar) {
            Storage::disk('public')->delete($user->avatar);
        }
    }
}
