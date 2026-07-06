<?php

namespace App\Services;

use App\Models\Criterion;
use App\Models\Score;

class WeightService
{
    /**
     * Compute automatic, normalized criterion weights from student assessments.
     *
     * For every criterion the average of all student-given values is taken, then
     * each average is divided by the sum of all averages so the weights always
     * add up to 1 — regardless of how many criteria or sub-criteria exist.
     *
     * @return array<int, float> [criterion_id => weight]
     */
    public function criteriaWeights(): array
    {
        $criteria = Criterion::all();

        if ($criteria->isEmpty()) {
            return [];
        }

        $averages = [];
        foreach ($criteria as $criterion) {
            $averages[$criterion->id] = (float) Score::where('criterion_id', $criterion->id)->avg('value');
        }

        $total = array_sum($averages);

        if ($total <= 0) {
            // No assessments yet — fall back to equal weights so the total is still 1.
            $equal = 1 / $criteria->count();

            return array_fill_keys($criteria->pluck('id')->all(), $equal);
        }

        $weights = [];
        foreach ($averages as $criterionId => $average) {
            $weights[$criterionId] = $average / $total;
        }

        return $weights;
    }

    public function weightFor(int $criterionId): float
    {
        return $this->criteriaWeights()[$criterionId] ?? 0.0;
    }
}
