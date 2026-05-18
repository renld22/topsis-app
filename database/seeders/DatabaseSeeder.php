<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Alternative; 
use App\Models\Criterion;
use App\Models\Score;
class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
{
    // 0. BUAT USER (Admin & Mahasiswa)
        \App\Models\User::create([
            'name' => 'Administrator',
            'email' => 'admin@spk.app',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        \App\Models\User::create([
            'name' => 'Mahasiswa User',
            'email' => 'mahasiswa@spk.app',
            'password' => bcrypt('password'),
            'role' => 'mahasiswa',
        ]);
    // 1. Alternatif (Dosen) berdasarkan Tabel 3.4
    $alternatives = [
        ['name' => 'Alex', 'address' => 'Jl. Arya Jaya Santika'],
        ['name' => 'Michael', 'address' => 'Jl. Arya Jaya Santika'],
        ['name' => 'Chaniago', 'address' => 'Jl. Arya Jaya Santika'],
        ['name' => 'Alexander', 'address' => 'Jl. Arya Jaya Santika'],
    ];

    foreach ($alternatives as $alt) {
        Alternative::create($alt);
    }

    // 2. Kriteria berdasarkan Tabel 3.5
    // Ingat: C5 (Tingkat Keterlambatan) adalah COST
    $criteria = [
        ['name' => 'Kedisiplinan', 'type' => 'benefit', 'weight' => 0.20],
        ['name' => 'Kemampuan materi', 'type' => 'benefit', 'weight' => 0.25],
        ['name' => 'Penguasaan materi', 'type' => 'benefit', 'weight' => 0.20],
        ['name' => 'Interaksi', 'type' => 'benefit', 'weight' => 0.20],
        ['name' => 'Tingkat keterlambatan', 'type' => 'cost', 'weight' => 0.15],
    ];

    foreach ($criteria as $crit) {
        Criterion::create($crit);
    }

   // 3. Skor (Nilai Matriks) berdasarkan Tabel 3.6
    $scores = [
        // Skor untuk Alex (A1)
        ['alternative_id' => 1, 'criterion_id' => 1, 'value' => 5],
        ['alternative_id' => 1, 'criterion_id' => 2, 'value' => 4],
        ['alternative_id' => 1, 'criterion_id' => 3, 'value' => 5],
        ['alternative_id' => 1, 'criterion_id' => 4, 'value' => 4],
        ['alternative_id' => 1, 'criterion_id' => 5, 'value' => 2], // Sudah diperbaiki ke ID 5

        // Skor untuk Michael (A2)
        ['alternative_id' => 2, 'criterion_id' => 1, 'value' => 4], // Sesuai tabel skripsi
        ['alternative_id' => 2, 'criterion_id' => 2, 'value' => 5],
        ['alternative_id' => 2, 'criterion_id' => 3, 'value' => 4],
        ['alternative_id' => 2, 'criterion_id' => 4, 'value' => 5],
        ['alternative_id' => 2, 'criterion_id' => 5, 'value' => 1],

        // Skor untuk Chaniago (A3)
        ['alternative_id' => 3, 'criterion_id' => 1, 'value' => 3],
        ['alternative_id' => 3, 'criterion_id' => 2, 'value' => 4],
        ['alternative_id' => 3, 'criterion_id' => 3, 'value' => 3],
        ['alternative_id' => 3, 'criterion_id' => 4, 'value' => 3],
        ['alternative_id' => 3, 'criterion_id' => 5, 'value' => 4],

        // Skor untuk Alexander (A4)
        ['alternative_id' => 4, 'criterion_id' => 1, 'value' => 2],
        ['alternative_id' => 4, 'criterion_id' => 2, 'value' => 2],
        ['alternative_id' => 4, 'criterion_id' => 3, 'value' => 4],
        ['alternative_id' => 4, 'criterion_id' => 4, 'value' => 2],
        ['alternative_id' => 4, 'criterion_id' => 5, 'value' => 3],
    ];

    foreach ($scores as $score) {
        Score::create($score);
    }
}
}