<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DNATest extends Model
{
    use HasFactory;

    protected $table = 'dna_tests';

    protected $fillable = [
        'dna',
        'is_mutant',
    ];

    protected $casts = [
        'dna' => 'json'
    ];

    public function scopeIsHuman(Builder $query): Builder
    {
        return $query->where('is_mutant', false);
    }

    public function scopeIsMutant(Builder $query): Builder
    {
        return $query->where('is_mutant', true);
    }
}
