<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class Alternative extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'email', 'address'];

    
    public function scores(): HasMany
    {
        return $this->hasMany(Score::class);
    }

    public function result(): HasOne
    {
        return $this->hasOne(Result::class);
    }
}
