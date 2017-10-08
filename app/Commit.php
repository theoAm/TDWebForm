<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Commit
 *
 * @property int $id
 * @property int $repo_id
 * @property int|null $pull_id
 * @property string $sha
 * @property string $author
 * @property string $committer
 * @property string|null $message
 * @property string $authored_at
 * @property string $committed_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Commit whereAuthor($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Commit whereAuthoredAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Commit whereCommittedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Commit whereCommitter($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Commit whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Commit whereMessage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Commit wherePullId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Commit whereRepoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Commit whereSha($value)
 * @mixin \Eloquent
 */
class Commit extends Model
{

}
