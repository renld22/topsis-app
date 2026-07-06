<?php

namespace Database\Seeders;

use App\Models\Alternative;
use App\Models\Criterion;
use App\Models\Score;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        // Users
        User::create([
            'name' => 'Administrator',
            'email' => 'admin@spk.app',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        User::create([
            'name' => 'Mahasiswa User',
            'email' => 'mahasiswa@spk.app',
            'password' => bcrypt('password'),
            'role' => 'mahasiswa',
        ]);

        // Alternatif (Dosen)
        $alternatives = [
            ['name' => 'Alex', 'address' => 'Jl. Arya Jaya Santika'],
            ['name' => 'Michael', 'address' => 'Jl. Arya Jaya Santika'],
            ['name' => 'Chaniago', 'address' => 'Jl. Arya Jaya Santika'],
            ['name' => 'Alexander', 'address' => 'Jl. Arya Jaya Santika'],
        ];
        foreach ($alternatives as $alt) {
            Alternative::create($alt);
        }

        // Kriteria + Sub Kriteria (rubrik penilaian)
        $criteria = [
            [
                'name' => 'Penguasaan Materi',
                'type' => 'benefit',
                'sub' => [
                    ['value' => 5, 'description' => 'Dosen menguasai materi secara menyeluruh, menjelaskan dengan sangat jelas, tepat, dan mampu menjawab seluruh pertanyaan mahasiswa dengan baik.'],
                    ['value' => 4, 'description' => 'Dosen menguasai materi dengan baik, penyampaian jelas, serta mampu menjawab sebagian besar pertanyaan mahasiswa.'],
                    ['value' => 3, 'description' => 'Dosen cukup menguasai materi, namun masih terdapat beberapa bagian yang kurang jelas dalam penyampaian.'],
                    ['value' => 2, 'description' => 'Dosen kurang menguasai materi sehingga penjelasan sering kurang jelas dan kurang mampu menjawab pertanyaan mahasiswa.'],
                    ['value' => 1, 'description' => 'Dosen tidak menguasai materi dengan baik, penyampaian tidak jelas, dan tidak mampu menjawab pertanyaan mahasiswa.'],
                ],
            ],
            [
                'name' => 'Interaksi Komunikasi',
                'type' => 'benefit',
                'sub' => [
                    ['value' => 5, 'description' => 'Dosen mampu membangun komunikasi yang sangat baik, aktif berdiskusi, memberikan kesempatan bertanya, dan merespons pertanyaan dengan jelas.'],
                    ['value' => 4, 'description' => 'Dosen mampu berkomunikasi dengan baik, memberikan kesempatan bertanya, serta menjawab pertanyaan mahasiswa dengan baik.'],
                    ['value' => 3, 'description' => 'Dosen berkomunikasi dengan cukup baik, namun interaksi dengan mahasiswa masih terbatas.'],
                    ['value' => 2, 'description' => 'Dosen kurang membangun komunikasi dengan mahasiswa dan jarang memberikan kesempatan berdiskusi.'],
                    ['value' => 1, 'description' => 'Dosen tidak membangun komunikasi yang baik, tidak memberikan kesempatan berdiskusi, serta kurang merespons pertanyaan mahasiswa.'],
                ],
            ],
            [
                'name' => 'Kedisiplinan',
                'type' => 'benefit',
                'sub' => [
                    ['value' => 5, 'description' => 'keterlambatan 5 kali'],
                    ['value' => 4, 'description' => 'keterlambatan 5 sampai 10 kali'],
                    ['value' => 3, 'description' => 'keterlambatan 10 sampai 15'],
                    ['value' => 2, 'description' => 'keterlambatan 15 sampai 20'],
                    ['value' => 1, 'description' => 'keterlambatan 20 sampai 25'],
                ],
            ],
            [
                'name' => 'Etika Profesional',
                'type' => 'benefit',
                'sub' => [
                    ['value' => 5, 'description' => 'Dosen selalu bersikap sopan, menghargai mahasiswa tanpa membedakan, bertanggung jawab, serta menjadi teladan dalam proses pembelajaran.'],
                    ['value' => 4, 'description' => 'Dosen bersikap sopan, menghargai mahasiswa, dan menjalankan tugas mengajar dengan penuh tanggung jawab.'],
                    ['value' => 3, 'description' => 'Dosen umumnya bersikap sopan dan bertanggung jawab, namun masih terdapat beberapa aspek profesionalisme yang perlu ditingkatkan.'],
                    ['value' => 2, 'description' => 'Dosen kurang menunjukkan sikap profesional, kurang menghargai mahasiswa, atau kurang bertanggung jawab dalam proses pembelajaran.'],
                    ['value' => 1, 'description' => 'Dosen tidak menunjukkan sikap profesional, kurang sopan, tidak menghargai mahasiswa, dan tidak bertanggung jawab dalam proses pembelajaran.'],
                ],
            ],
            [
                'name' => 'Kemampuan Materi',
                'type' => 'benefit',
                'sub' => [
                    ['value' => 5, 'description' => 'Dosen menguasai materi secara sangat baik, menjelaskan dengan jelas dan sistematis, serta mampu menjawab seluruh pertanyaan mahasiswa dengan tepat.'],
                    ['value' => 4, 'description' => 'Dosen menguasai materi dengan baik, memberikan penjelasan yang jelas, dan mampu menjawab sebagian besar pertanyaan mahasiswa dengan tepat.'],
                    ['value' => 3, 'description' => 'Dosen cukup menguasai materi, namun penjelasan atau jawaban terhadap pertanyaan mahasiswa masih perlu ditingkatkan.'],
                    ['value' => 2, 'description' => 'Dosen kurang menguasai materi sehingga penjelasan kurang jelas dan kesulitan menjawab pertanyaan mahasiswa.'],
                    ['value' => 1, 'description' => 'Dosen tidak menguasai materi, penjelasan tidak jelas, dan tidak mampu menjawab pertanyaan mahasiswa dengan baik.'],
                ],
            ],
        ];

        foreach ($criteria as $crit) {
            $criterion = Criterion::create([
                'name' => $crit['name'],
                'type' => $crit['type'],
            ]);
            foreach ($crit['sub'] as $sub) {
                $criterion->subCriteria()->create($sub);
            }
        }

        // Contoh penilaian mahasiswa (nilai subkriteria per dosen per kriteria)
        // Baris = dosen (Alex, Michael, Chaniago, Alexander), kolom = nilai per kriteria berurutan
        $sampleValues = [
            [5, 4, 5, 4, 4],
            [4, 5, 4, 5, 5],
            [3, 4, 3, 3, 4],
            [2, 2, 4, 2, 3],
        ];

        $alts = Alternative::orderBy('id')->get();
        $crits = Criterion::orderBy('id')->get();
        foreach ($alts as $ai => $alt) {
            foreach ($crits as $ci => $criterion) {
                $value = $sampleValues[$ai][$ci] ?? 3;
                $sub = $criterion->subCriteria()->where('value', $value)->first();
                Score::create([
                    'alternative_id' => $alt->id,
                    'criterion_id' => $criterion->id,
                    'sub_criterion_id' => $sub?->id,
                    'value' => $value,
                ]);
            }
        }
    }
}
