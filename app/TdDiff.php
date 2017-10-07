<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\TdDiff
 *
 * @property int $id
 * @property int $repo_id
 * @property string $author
 * @property string $commit_sha
 * @property string $previous_commit_sha
 * @property string $filename
 * @property float $sqale_index_diff
 * @property float $sqale_debt_ratio_diff
 * @property int $blocker_violations_diff
 * @property int $critical_violations_diff
 * @property int $major_violations_diff
 * @property int $minor_violations_diff
 * @property int $info_violations_diff
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TdDiff whereAuthor($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TdDiff whereBlockerViolationsDiff($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TdDiff whereCommitSha($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TdDiff whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TdDiff whereCriticalViolationsDiff($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TdDiff whereFilename($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TdDiff whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TdDiff whereInfoViolationsDiff($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TdDiff whereMajorViolationsDiff($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TdDiff whereMinorViolationsDiff($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TdDiff wherePreviousCommitSha($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TdDiff whereRepoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TdDiff whereSqaleDebtRatioDiff($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TdDiff whereSqaleIndexDiff($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TdDiff whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class TdDiff extends Model
{

}
