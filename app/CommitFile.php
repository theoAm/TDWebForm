<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\CommitFile
 *
 * @property int $id
 * @property int $repo_id
 * @property int $commit_id
 * @property string $filename
 * @property int|null $additions
 * @property int|null $deletions
 * @property int|null $changes
 * @property string|null $status
 * @method static \Illuminate\Database\Eloquent\Builder|\App\CommitFile whereAdditions($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\CommitFile whereChanges($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\CommitFile whereCommitId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\CommitFile whereDeletions($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\CommitFile whereFilename($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\CommitFile whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\CommitFile whereRepoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\CommitFile whereStatus($value)
 * @mixin \Eloquent
 */
class CommitFile extends Model
{

}
