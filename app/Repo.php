<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Repo
 *
 * @property int $id
 * @property string $owner
 * @property string $name
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Repo whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Repo whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Repo whereOwner($value)
 * @mixin \Eloquent
 */
class Repo extends Model
{

}
