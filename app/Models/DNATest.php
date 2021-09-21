<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DNATest extends Model
{
    use HasFactory;

    /**
     * @var string
     */
    protected $table = 'dna_tests';

    /**
     * @var string[]
     */
    protected $fillable = [
        'dna',
        'is_mutant',
    ];

    /**
     * @var string[]
     */
    protected $casts = [
        'dna' => 'json',
        'is_mutant' => 'bool'
    ];

    /**
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeIsHuman(Builder $query): Builder
    {
        return $query->where('is_mutant', false);
    }

    /**
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeIsMutant(Builder $query): Builder
    {
        return $query->where('is_mutant', true);
    }

    /**
     * @param bool $value
     * @return $this
     */
    public function updateIsMutant(bool $value): self
    {
        $this->is_mutant = $value;
        $this->save();

        return $this;
    }

    /**
     * @param $dna
     * @return static
     */
    public static function createNew($dna): self
    {
        return self::create(['dna' => $dna]);
    }

    /**
     * @return bool
     */
    public function isMutant(): bool
    {
        return $this->is_mutant;
    }
}
