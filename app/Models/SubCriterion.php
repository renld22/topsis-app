<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SubCriterion extends Model
{
    use HasFactory;

    protected $table = 'sub_criteria';

    protected $fillable = ['criterion_id', 'name', 'value'];

    public function criterion(): BelongsTo
    {
        return $this->belongsTo(criterion::class, 'criterion_id');
    }

    public function scores(): HasMany
    {
        return $this->hasMany(Score::class, 'sub_criterion_id');
    }

    protected static $calculatedSubWeights = null;

    protected static function booted()
    {
        // Simpan nilai lama menggunakan static array (bukan property model agar tidak tersimpan ke DB)
        $oldValues = [];

        static::saving(function ($subCriterion) use (&$oldValues) {
            $oldValues[$subCriterion->id] = $subCriterion->getOriginal('value');
        });

        static::saved(function ($subCriterion) use (&$oldValues) {
            self::$calculatedSubWeights = null;

            $oldValue = $oldValues[$subCriterion->id] ?? null;
            $newValue = (float) $subCriterion->value;

            // Hanya recalculate jika nilai benar-benar berubah
            if ($oldValue !== null && (float) $oldValue !== $newValue) {
                self::recalculateParentWeight($subCriterion->criterion_id, (float) $oldValue, $newValue);
            }

            unset($oldValues[$subCriterion->id]);
        });

        static::deleted(function ($subCriterion) {
            self::$calculatedSubWeights = null;
            self::recalculateParentWeight($subCriterion->criterion_id, (float) $subCriterion->value, 0, true);
        });
    }

    /**
     * Recalculate parent criterion weight proportionally when a sub-criterion value changes,
     * then renormalize all criteria weights so they still sum to 1.0.
     */
    private static function recalculateParentWeight(int $criterionId, float $oldValue, float $newValue, bool $deleted = false): void
    {
        $criterion = criterion::find($criterionId);
        if (!$criterion) return;

        // Get current sum after the change
        $subs    = self::where('criterion_id', $criterionId)->get();
        $newTotal = $subs->sum('value');

        // Reconstruct old total before the change
        $oldTotal = $deleted
            ? $newTotal + $oldValue
            : $newTotal - $newValue + $oldValue;

        if ($oldTotal > 0 && $oldTotal !== $newTotal) {
            $ratio     = $newTotal / $oldTotal;
            $newWeight = (float) $criterion->weight * $ratio;

            criterion::withoutEvents(function () use ($criterion, $newWeight) {
                $criterion->update(['weight' => $newWeight]);
            });

            // Renormalize semua bobot kriteria agar jumlahnya tetap 1.0
            criterion::normalizeWeights();
        }

        self::$calculatedSubWeights = null;
    }

    public static function getCalculatedWeights()
    {
        if (self::$calculatedSubWeights !== null) {
            return self::$calculatedSubWeights;
        }

        $subCriteria = self::with('criterion')->get();
        if ($subCriteria->isEmpty()) {
            return [];
        }

        // Kelompokkan sub-kriteria berdasarkan criterion_id
        $groupedSubCriteria = $subCriteria->groupBy('criterion_id');

        $weights = [];
        foreach ($groupedSubCriteria as $criterionId => $subs) {
            $firstSub        = $subs->first();
            $criterionWeight = $firstSub && $firstSub->criterion ? (float) $firstSub->criterion->weight : 0.0;
            $totalValue      = $subs->sum('value');

            foreach ($subs as $sub) {
                if ($totalValue > 0) {
                    $weights[$sub->id] = ($sub->value / $totalValue) * $criterionWeight;
                } else {
                    $weights[$sub->id] = $criterionWeight / $subs->count();
                }
            }
        }

        self::$calculatedSubWeights = $weights;
        return self::$calculatedSubWeights;
    }

    public function getWeightAttribute()
    {
        return self::getCalculatedWeights()[$this->id] ?? 0.0;
    }
}
