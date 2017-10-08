<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\ComponentSource
 *
 * @property int $id
 * @property string $component_key
 * @property string $sources
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\TdViolation[] $tdViolation
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ComponentSource whereComponentKey($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ComponentSource whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ComponentSource whereSources($value)
 * @mixin \Eloquent
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ComponentSource whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ComponentSource whereUpdatedAt($value)
 */
class ComponentSource extends Model
{
    public function tdViolation()
    {
        return $this->hasMany(TdViolation::class);
    }
}
