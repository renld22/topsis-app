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

    protected $fillable = ['criterion_id', 'value', 'description'];

    protected $casts = [
        'value' => 'integer',
    ];

    public function criterion(): BelongsTo
    {
        return $this->belongsTo(Criterion::class);
    }

    public function scores(): HasMany
    {
        return $this->hasMany(Score::class);
    }
}
