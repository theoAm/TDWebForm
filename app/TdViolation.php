<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\TdViolation
 *
 * @property int $id
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property int|null $component_source_id
 * @property int|null $rule_id
 * @property-read \App\ComponentSource|null $componentSource
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TdViolation whereComponentSourceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TdViolation whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TdViolation whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TdViolation whereRuleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TdViolation whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property int|null $line
 * @property string|null $message
 * @property string|null $tags
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TdViolation whereLine($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TdViolation whereMessage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TdViolation whereTags($value)
 * @property int|null $repo_id
 * @property string|null $author
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TdViolation whereAuthor($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TdViolation whereRepoId($value)
 */
class TdViolation extends Model
{
    public function componentSource()
    {
        return $this->belongsTo(ComponentSource::class);
    }
}
