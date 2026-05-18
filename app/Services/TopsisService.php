<?php

namespace App\Services;

use App\Models\Alternative;
use App\Models\Criterion;
use App\Models\Score;
use App\Models\Result;

class TopsisService
{
    public function calculateTopsis()
    {
        $alternatives = Alternative::all();
        $criteria = Criterion::all();
        $scores = Score::all();

        if ($alternatives->isEmpty() || $criteria->isEmpty()) {
            return [];
        }

        $decisionMatrix = $this->buildDecisionMatrix($alternatives, $criteria, $scores);
        $normalizedMatrix = $this->normalizeMatrix($decisionMatrix);
        $weightedMatrix = $this->applyWeights($normalizedMatrix, $criteria);
        $idealPositive = $this->calculateIdealPositive($weightedMatrix, $criteria);
        $idealNegative = $this->calculateIdealNegative($weightedMatrix, $criteria);
        $distances = $this->calculateDistances($weightedMatrix, $idealPositive, $idealNegative);
        $preferences = $this->calculatePreferences($distances);
        $results = $this->rankAlternatives($preferences, $alternatives);

        Result::truncate();
        foreach ($results as $result) {
            Result::create([
                'alternative_id' => $result['alternative_id'],
                'preference_score' => $result['preference_score'],
                'rank' => $result['rank'],
            ]);
        }

        return $results;
    }

    private function buildDecisionMatrix($alternatives, $criteria, $scores)
    {
        $matrix = [];
        foreach ($alternatives as $alt) {
            $row = [];
            foreach ($criteria as $crit) {
                $score = $scores->where('alternative_id', $alt->id)->where('criterion_id', $crit->id)->first();
                $row[$crit->id] = $score ? $score->value : 0;
            }
            $matrix[$alt->id] = $row;
        }
        return $matrix;
    }

    private function normalizeMatrix($matrix)
    {
        $normalized = [];
        $sums = [];
        foreach ($matrix as $row) {
            foreach ($row as $critId => $value) {
                $sums[$critId] = ($sums[$critId] ?? 0) + pow($value, 2);
            }
        }
        foreach ($matrix as $altId => $row) {
            $normalized[$altId] = [];
            foreach ($row as $critId => $value) {
                $normalized[$altId][$critId] = $sums[$critId] > 0 ? $value / sqrt($sums[$critId]) : 0;
            }
        }
        return $normalized;
    }

    private function applyWeights($normalizedMatrix, $criteria)
    {
        $weighted = [];
        foreach ($normalizedMatrix as $altId => $row) {
            $weighted[$altId] = [];
            foreach ($row as $critId => $value) {
                $weight = $criteria->find($critId)->weight;
                $weighted[$altId][$critId] = $value * $weight;
            }
        }
        return $weighted;
    }

    private function calculateIdealPositive($weightedMatrix, $criteria)
    {
        $idealPositive = [];
        foreach ($criteria as $crit) {
            $values = array_column($weightedMatrix, $crit->id);
            $idealPositive[$crit->id] = $crit->type === 'benefit' ? max($values) : min($values);
        }
        return $idealPositive;
    }

    private function calculateIdealNegative($weightedMatrix, $criteria)
    {
        $idealNegative = [];
        foreach ($criteria as $crit) {
            $values = array_column($weightedMatrix, $crit->id);
            $idealNegative[$crit->id] = $crit->type === 'benefit' ? min($values) : max($values);
        }
        return $idealNegative;
    }

    private function calculateDistances($weightedMatrix, $idealPositive, $idealNegative)
    {
        $distances = [];
        foreach ($weightedMatrix as $altId => $row) {
            $positiveDistance = 0;
            $negativeDistance = 0;
            foreach ($row as $critId => $value) {
                $positiveDistance += pow($value - $idealPositive[$critId], 2);
                $negativeDistance += pow($value - $idealNegative[$critId], 2);
            }
            $distances[$altId] = [
                'positive' => sqrt($positiveDistance),
                'negative' => sqrt($negativeDistance),
            ];
        }
        return $distances;
    }

    private function calculatePreferences($distances)
    {
        $preferences = [];
        foreach ($distances as $altId => $dist) {
            $denom = $dist['positive'] + $dist['negative'];
            $preferences[$altId] = $denom > 0 ? $dist['negative'] / $denom : 0;
        }
        return $preferences;
    }

    private function rankAlternatives($preferences, $alternatives)
    {
        arsort($preferences);
        $results = [];
        $rank = 1;
        foreach ($preferences as $altId => $score) {
            $results[] = [
                'alternative_id' => $altId,
                'name' => $alternatives->find($altId)->name,
                'preference_score' => round($score, 4),
                'rank' => $rank++,
            ];
        }
        return $results;
    }
}