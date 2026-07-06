<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Score extends Model
{
    use HasFactory;

    protected $fillable = ['alternative_id', 'criterion_id', 'sub_criterion_id', 'value'];

    public function alternative()
    {
        return $this->belongsTo(Alternative::class);
    }

    public function criterion()
    {
        return $this->belongsTo(Criterion::class);
    }

    public function subCriterion()
    {
        return $this->belongsTo(SubCriterion::class);
    }
}
