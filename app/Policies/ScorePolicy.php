<?php

namespace App\Policies;

use App\Models\Score;
use App\Models\User;

class ScorePolicy
{
    /**
     * Izinkan Admin dan Mahasiswa melihat menu Penilaian.
     */
    public function viewAny(User $user): bool
    {
        return true; 
    }

    /**
     * Izinkan Admin dan Mahasiswa membuat penilaian baru.
     */
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * Hanya Admin yang boleh mengubah (edit) data penilaian.
     */
    public function update(User $user, Score $score): bool
    {
        return $user->role === 'admin';
    }

    /**
     * Hanya Admin yang boleh menghapus data penilaian.
     */
    public function delete(User $user, Score $score): bool
    {
        return $user->role === 'admin';
    }

    // Fungsi lainnya (restore/forceDelete) biarkan saja return false atau hapus tidak apa-apa
}