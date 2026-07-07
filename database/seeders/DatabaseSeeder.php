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
    $criteriaData = [
        ['name' => 'Penguasaan materi', 'type' => 'benefit', 'weight' => 0.30],
        ['name' => 'Interaksi Komunikasi', 'type' => 'benefit', 'weight' => 0.25],
        ['name' => 'Kedisiplinan', 'type' => 'benefit', 'weight' => 0.15],
        ['name' => 'Etika Profesional', 'type' => 'benefit', 'weight' => 0.15],
        ['name' => 'Kemampuan Materi', 'type' => 'benefit', 'weight' => 0.15],
    ];

    $criteria = [];
    foreach ($criteriaData as $crit) {
        $criteria[] = Criterion::create($crit);
    }

    // 3. Sub-Kriteria untuk masing-masing kriteria (skala 5 ke 1)
    $subCriteriaData = [
        // Penguasaan materi (kriteria index 0)
        0 => [
            5 => 'Dosen menguasai materi secara menyeluruh, menjelaskan dengan sangat jelas, tepat, dan mampu menjawab seluruh pertanyaan mahasiswa dengan baik.',
            4 => 'Dosen menguasai materi dengan baik, penyampaian jelas, serta mampu menjawab sebagian besar pertanyaan mahasiswa.',
            3 => 'Dosen cukup menguasai materi, namun masih terdapat beberapa bagian yang kurang jelas dalam penyampaian.',
            2 => 'Dosen kurang menguasai materi sehingga penjelasan sering kurang jelas dan kurang mampu menjawab pertanyaan mahasiswa.',
            1 => 'Dosen tidak menguasai materi dengan baik, penyampaian tidak jelas, dan tidak mampu menjawab pertanyaan mahasiswa.'
        ],
        // Interaksi Komunikasi (kriteria index 1)
        1 => [
            5 => 'Dosen mampu membangun komunikasi yang sangat baik, aktif berdiskusi, memberikan kesempatan bertanya, dan merespons pertanyaan dengan jelas.',
            4 => 'Dosen mampu berkomunikasi dengan baik, memberikan kesempatan bertanya, serta menjawab pertanyaan mahasiswa dengan baik.',
            3 => 'Dosen berkomunikasi dengan cukup baik, namun interaksi dengan mahasiswa masih terbatas.',
            2 => 'Dosen kurang membangun komunikasi dengan mahasiswa dan jarang memberikan kesempatan berdiskusi.',
            1 => 'Dosen tidak membangun komunikasi yang baik, tidak memberikan kesempatan berdiskusi, serta kurang merespons pertanyaan mahasiswa.'
        ],
        // Kedisiplinan (kriteria index 2)
        2 => [
            5 => 'keterlambatan 5 kali',
            4 => 'keterlambatan 5 sampai 10 kali',
            3 => 'keterlambatan 10 sampai 15 kali',
            2 => 'keterlambatan 15 sampai 20 kali',
            1 => 'keterlambatan 20 sampai 25 kali'
        ],
        // Etika Profesional (kriteria index 3)
        3 => [
            5 => 'Dosen selalu bersikap sopan, menghargai mahasiswa tanpa membedakan, bertanggung jawab, serta menjadi teladan dalam proses pembelajaran.',
            4 => 'Dosen bersikap sopan, menghargai mahasiswa, dan menjalankan tugas mengajar dengan penuh tanggung jawab.',
            3 => 'Dosen umumnya bersikap sopan dan bertanggung jawab, namun masih terdapat beberapa aspek profesionalisme yang perlu ditingkatkan.',
            2 => 'Dosen kurang menunjukkan sikap profesional, kurang menghargai mahasiswa, atau kurang bertanggung jawab dalam proses pembelajaran.',
            1 => 'Dosen tidak menunjukkan sikap profesional, kurang sopan, tidak menghargai mahasiswa, dan tidak bertanggung jawab dalam proses pembelajaran.'
        ],
        // Kemampuan Materi (kriteria index 4)
        4 => [
            5 => 'Dosen menguasai materi secara sangat baik, menjelaskan dengan jelas dan sistematis, serta mampu menjawab seluruh pertanyaan mahasiswa dengan tepat.',
            4 => 'Dosen menguasai materi dengan baik, memberikan penjelasan yang jelas, dan mampu menjawab sebagian besar pertanyaan mahasiswa dengan tepat.',
            3 => 'Dosen cukup menguasai materi, namun penjelasan atau jawaban terhadap pertanyaan mahasiswa masih perlu ditingkatkan.',
            2 => 'Dosen kurang menguasai materi sehingga penjelasan kurang jelas dan kesulitan menjawab pertanyaan mahasiswa.',
            1 => 'Dosen tidak menguasai materi, penjelasan tidak jelas, dan tidak mampu menjawab pertanyaan mahasiswa dengan baik.'
        ]
    ];

    $subCriteriaMap = []; // criterion_id => [ value => subCriterionModel ]
    foreach ($subCriteriaData as $critIndex => $subs) {
        $criterion = $criteria[$critIndex];
        foreach ($subs as $value => $subName) {
            $subModel = \App\Models\SubCriterion::create([
                'criterion_id' => $criterion->id,
                'name' => $subName,
                'value' => $value,
            ]);
            $subCriteriaMap[$criterion->id][$value] = $subModel;
        }
    }

    // 4. Skor (Nilai Matriks) berdasarkan Tabel 3.6 (Dipetakan ke Sub-Kriteria)
    // alternative_id => [criterion_id => value]
    $matrixValues = [
        // Alex (A1)
        1 => [1 => 5, 2 => 4, 3 => 5, 4 => 4, 5 => 2],
        // Michael (A2)
        2 => [1 => 4, 2 => 5, 3 => 4, 4 => 5, 5 => 1],
        // Chaniago (A3)
        3 => [1 => 3, 2 => 4, 3 => 3, 4 => 3, 5 => 4],
        // Alexander (A4)
        4 => [1 => 2, 2 => 2, 3 => 4, 4 => 2, 5 => 3],
    ];

    foreach ($matrixValues as $altId => $critValues) {
        foreach ($critValues as $critIndex => $val) {
            $criterion = $criteria[$critIndex - 1];
            $sub = $subCriteriaMap[$criterion->id][$val] ?? null;
            if ($sub) {
                Score::create([
                    'alternative_id' => $altId,
                    'criterion_id' => $criterion->id,
                    'sub_criterion_id' => $sub->id,
                    'value' => $val,
                ]);
            }
        }
    }
}
}