<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class criterion extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'type', 'weight', 'description'];

    public function scores(): HasMany
    {
        return $this->hasMany(Score::class);
    }

    public function subCriteria(): HasMany
    {
        return $this->hasMany(SubCriterion::class, 'criterion_id');
    }

    protected static function booted()
    {
        static::saved(function ($criterion) {
            self::normalizeWeights();
        });

        static::deleted(function ($criterion) {
            self::normalizeWeights();
        });
    }

    public static function normalizeWeights()
    {
        $criteria = self::all();
        $total = $criteria->sum('weight');
        if ($total > 0 && abs($total - 1.0) > 0.00001) {
            foreach ($criteria as $criterion) {
                self::withoutEvents(function () use ($criterion, $total) {
                    $criterion->update([
                        'weight' => $criterion->weight / $total
                    ]);
                });
            }
        }
    }
}
