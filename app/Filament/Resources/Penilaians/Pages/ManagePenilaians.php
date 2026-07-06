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
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ManageRecords;
use Illuminate\Support\Facades\Cache;
use Illuminate\Validation\ValidationException;

class ManagePenilaians extends ManageRecords
{
    protected static string $resource = PenilaianResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Beri Penilaian')
                ->modalWidth('2xl')
                ->form([
                    Select::make('alternative_id')
                        ->label('Pilih Dosen')
                        ->options(function () {
                            return Alternative::doesntHave('scores')
                                ->orderBy('name')
                                ->pluck('name', 'id');
                        })
                        ->searchable()
                        ->required()
                        ->helperText('Hanya dosen yang belum dinilai yang dapat dipilih.'),

                    Repeater::make('scores')
                        ->label('Penilaian per Kriteria')
                        ->addable(false)
                        ->deletable(false)
                        ->reorderable(false)
                        ->default(fn () => Criterion::orderBy('id')->get()
                            ->map(fn (Criterion $criterion) => ['criterion_id' => $criterion->id])
                            ->all())
                        ->schema([
                            Select::make('criterion_id')
                                ->label('Kriteria')
                                ->options(Criterion::pluck('name', 'id'))
                                ->disabled()
                                ->dehydrated()
                                ->required(),

                            Select::make('sub_criterion_id')
                                ->label('Subkriteria (Penilaian)')
                                ->native(false)
                                ->options(fn (callable $get) => SubCriterion::where('criterion_id', $get('criterion_id'))
                                    ->orderByDesc('value')
                                    ->get()
                                    ->mapWithKeys(fn (SubCriterion $sub) => [
                                        $sub->id => "Nilai {$sub->value} — {$sub->description}",
                                    ]))
                                ->disabled(fn (callable $get) => empty($get('criterion_id')))
                                ->required()
                                ->helperText('Pilih pernyataan yang paling sesuai. Nilainya dipakai otomatis untuk perhitungan bobot & TOPSIS.'),
                        ])
                        ->columns(1),
                ])
                ->action(function (array $data): void {
                    if (Score::where('alternative_id', $data['alternative_id'])->exists()) {
                        Notification::make()
                            ->danger()
                            ->title('Duplikat dosen')
                            ->body('Dosen ini sudah pernah dinilai. Pilih dosen lain.')
                            ->send();

                        throw ValidationException::withMessages([
                            'alternative_id' => ['Dosen ini sudah pernah dinilai. Pilih dosen lain.'],
                        ]);
                    }

                    foreach ($data['scores'] as $item) {
                        $sub = SubCriterion::find($item['sub_criterion_id']);

                        if (! $sub) {
                            continue;
                        }

                        Score::create([
                            'alternative_id' => $data['alternative_id'],
                            'criterion_id' => $sub->criterion_id,
                            'sub_criterion_id' => $sub->id,
                            'value' => $sub->value,
                        ]);
                    }

                    Cache::forget('topsis_results');

                    Notification::make()
                        ->success()
                        ->title('Penilaian tersimpan')
                        ->body('Bobot kriteria diperbarui otomatis dari penilaian.')
                        ->send();
                }),
        ];
    }
}
