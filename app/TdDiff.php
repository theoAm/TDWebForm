<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\TdDiff
 *
 * @property int $id
 * @property int|null $repo_id
 * @property string|null $author
 * @property int|null $sqale_index_diff
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TdDiff whereAuthor($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TdDiff whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TdDiff whereRepoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TdDiff whereSqaleIndexDiff($value)
 * @mixin \Eloquent
 */
class TdDiff extends Model
{

}
