<?php

namespace App\Filament\Resources\Penilaians\Pages;

use App\Filament\Resources\Penilaians\PenilaianResource;
use App\Models\Alternative;
use App\Models\Criterion;
use App\Models\Score;
use Filament\Actions\CreateAction;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Html;
use Filament\Resources\Pages\ManageRecords;
use Filament\Notifications\Notification;

class ManagePenilaians extends ManageRecords
{
    protected static string $resource = PenilaianResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('New Score')
                ->modalWidth('xl')
                ->form([
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
                    Repeater::make('scores')
                        ->label('Nilai Semua Kriteria')
                        ->schema([
                            Select::make('criterion_id')
                                ->label('Kriteria')
                                ->options(Criterion::pluck('name', 'id'))
                                ->searchable()
                                ->required()
                                ->disableOptionsWhenSelectedInSiblingRepeaterItems()
                                ->helperText(function (callable $get) {
                                    $criterion = Criterion::find($get('criterion_id'));

                                    if (!$criterion) {
                                        return 'Pilih kriteria terlebih dahulu sebelum mengisi nilai.';
                                    }

                                    return $criterion->description ?: 'Pilih kriteria terlebih dahulu sebelum mengisi nilai.';
                                })
                                ->rules(function (callable $get) {
                                    return [
                                        function ($attribute, $value, $fail) use ($get) {
                                            $alternativeId = $get('alternative_id');

                                            if (!$alternativeId || !$value) {
                                                return;
                                            }

                                            if (Score::where('alternative_id', $alternativeId)
                                                ->where('criterion_id', $value)
                                                ->exists()) {
                                                $fail('Kombinasi Dosen & Kriteria ini sudah pernah Anda nilai! Tidak boleh menilai yang sama dua kali.');
                                            }
                                        },
                                    ];
                                }),
                              TextInput::make('value')
                        ->label('Penilaian')
                        ->required()
                        ->numeric()
                        ->belowContent(
                            Html::make(new \Illuminate\Support\HtmlString('<div class="text-sm text-gray-500">Nilai harus antara 1-5.
                             <br>keterangan penilaian :<br>5: Baik sekali, 4: Baik,<br>3: Cukup, 2: Kurang,<br>1: Sangat kurang.</div>'))
                        )
                        ->rules([
                            'numeric',
                            'between:1,5',
                        ])
                        ->validationMessages([
                            'required' => 'Nilai harus antara 1-5',
                            'numeric' => 'Nilai harus antara 1-5',
                            'between' => 'Nilai harus antara 1-5',
                        ])
                        ])
                        ->columns(2)
                        ->minItems(1)
                        ->maxItems(5)
                        ->createItemButtonLabel('Tambah Kriteria'),
                ])
                ->action(function (array $data): void {
                    // Cek duplikat criterion_id di dalam repeater sebelum menyimpan
                    $ids = array_column($data['scores'], 'criterion_id');
                    if (count($ids) !== count(array_unique($ids))) {
                        Notification::make()
                            ->danger()
                            ->title('Duplikat nilai')
                            ->body('Terdapat kriteria duplikat dalam daftar nilai.')
                            ->send();

                        throw \Illuminate\Validation\ValidationException::withMessages([
                            'scores' => ['Terdapat kriteria duplikat dalam daftar nilai.']
                        ]);
                    }

                    // Cek apakah kombinasi alternative_id + criterion_id sudah ada di DB
                    if (Score::where('alternative_id', $data['alternative_id'])->exists()) {
                        Notification::make()
                            ->danger()
                            ->title('Duplikat dosen')
                            ->body('Dosen ini sudah pernah dinilai. Pilih dosen lain.')
                            ->send();

                        throw \Illuminate\Validation\ValidationException::withMessages([
                            'alternative_id' => ['Dosen ini sudah pernah dinilai. Pilih dosen lain.'],
                        ]);
                    }

                    foreach ($data['scores'] as $index => $item) {
                        $exists = Score::where('alternative_id', $data['alternative_id'])
                            ->where('criterion_id', $item['criterion_id'])
                            ->exists();

                        if ($exists) {
                            Notification::make()
                                ->danger()
                                ->title('Duplikat nilai')
                                ->body('Kombinasi Dosen & Kriteria ini sudah pernah Anda nilai! Tidak boleh menilai yang sama dua kali.')
                                ->send();

                            throw \Illuminate\Validation\ValidationException::withMessages([
                                "scores.$index.criterion_id" => ['Kombinasi Dosen & Kriteria ini sudah pernah Anda nilai! Tidak boleh menilai yang sama dua kali.'],
                            ]);
                        }
                    }

                    foreach ($data['scores'] as $item) {
                        Score::create([
                            'alternative_id' => $data['alternative_id'],
                            'criterion_id' => $item['criterion_id'],
                            'value' => $item['value'],
                        ]);
                    }
                }),
        ];
    }
}
