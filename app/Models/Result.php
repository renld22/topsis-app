<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Result extends Model
{
    use HasFactory;

    protected $fillable = ['alternative_id', 'preference_score', 'rank'];

    public function alternative()
    {
        return $this->belongsTo(Alternative::class);
    }
}
