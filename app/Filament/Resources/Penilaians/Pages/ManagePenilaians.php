<?php

namespace App\Filament\Resources\Penilaians\Pages;

use App\Filament\Resources\Penilaians\PenilaianResource;
use App\Models\Alternative;
use App\Models\Criterion;
use App\Models\Score;
use App\Models\SubCriterion;
use Filament\Actions\CreateAction;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Html;
use Filament\Resources\Pages\ManageRecords;
use Filament\Schemas\Components\Section;
use Filament\Notifications\Notification;

class ManagePenilaians extends ManageRecords
{
    protected static string $resource = PenilaianResource::class;

    protected function getHeaderActions(): array
    {
        // Dynamically build form components based on database criteria and sub-criteria
        $formComponents = [
            Select::make('alternative_id')
                ->label('Pilih Dosen')
                ->options(function () {
                    $order = ['Marc Klok' => 0, 'Beckham' => 1, 'Haye' => 2, 'Barba' => 3];

                    return Alternative::doesntHave('scores')
                        ->get()
                        ->pluck('name', 'id')
                        ->sortBy(fn ($name) => $order[$name] ?? 999);
                })
                ->searchable()
                ->helperText('Hanya dosen yang belum dinilai yang dapat dipilih.')
                ->required(),
        ];

        try {
            $criteria = Criterion::with('subCriteria')->get();
            $assessmentFields = [];
            foreach ($criteria as $crit) {
                if ($crit->subCriteria->isEmpty()) {
                    continue;
                }
                
                // Urutkan opsi subkriteria dari nilai 5 ke 1
                $options = [];
                foreach ($crit->subCriteria->sortByDesc('value') as $sub) {
                    $options[$sub->id] = "{$sub->value} - {$sub->name}";
                }

                $assessmentFields[] = Select::make("criteria.{$crit->id}")
                    ->label($crit->name)
                    ->options($options)
                    ->native(false)
                    ->required()
                    ->helperText("Pilih subkriteria yang paling menggambarkan kinerja dosen untuk kriteria: " . $crit->name);
            }

            if (!empty($assessmentFields)) {
                $formComponents[] = Section::make('Kuesioner Evaluasi Dosen')
                    ->description('Silakan pilih salah satu subkriteria yang paling menggambarkan kinerja dosen pada masing-masing kriteria.')
                    ->schema($assessmentFields);
            }
        } catch (\Exception $e) {
            // Fallback if table does not exist during migration phase
        }

        return [
            CreateAction::make()
                ->label('New Score')
                ->modalWidth('2xl')
                ->form($formComponents)
                ->action(function (array $data): void {
                    $alternativeId = $data['alternative_id'];

                    // Cek apakah alternatif sudah pernah dinilai
                    if (Score::where('alternative_id', $alternativeId)->exists()) {
                        Notification::make()
                            ->danger()
                            ->title('Duplikat dosen')
                            ->body('Dosen ini sudah pernah dinilai. Pilih dosen lain.')
                            ->send();

                        throw \Illuminate\Validation\ValidationException::withMessages([
                            'alternative_id' => ['Dosen ini sudah pernah dinilai. Pilih dosen lain.'],
                        ]);
                    }

                    // Simpan score ke database
                    if (isset($data['criteria']) && is_array($data['criteria'])) {
                        foreach ($data['criteria'] as $criterionId => $subCriterionId) {
                            $subCriterion = \App\Models\SubCriterion::find($subCriterionId);
                            if ($subCriterion) {
                                Score::create([
                                    'alternative_id' => $alternativeId,
                                    'criterion_id' => $criterionId,
                                    'sub_criterion_id' => $subCriterionId,
                                    'value' => (float) $subCriterion->value,
                                ]);
                            }
                        }
                    }

                    // Reset cache topsis hasil agar dihitung ulang
                    \Illuminate\Support\Facades\Cache::forget('topsis_results');

                    Notification::make()
                        ->success()
                        ->title('Berhasil')
                        ->body('Penilaian dosen berhasil disimpan.')
                        ->send();
                }),
        ];
    }
}
