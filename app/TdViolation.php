<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\TdViolation
 *
 * @property int $id
 * @property int $td_diff_id
 * @property string $key
 * @property string $name
 * @property string $description
 * @property string $severity
 * @property string $defaultDebtChar
 * @property string $added_or_resolved
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TdViolation whereAddedOrResolved($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TdViolation whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TdViolation whereDefaultDebtChar($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TdViolation whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TdViolation whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TdViolation whereKey($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TdViolation whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TdViolation whereSeverity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TdViolation whereTdDiffId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TdViolation whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property-read \App\TdDiff $tdDiff
 */
class TdViolation extends Model
{
    public function tdDiff()
    {
        return $this->belongsTo(TdDiff::class);
    }
}
