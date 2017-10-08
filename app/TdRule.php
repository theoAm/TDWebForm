<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\TdRule
 *
 * @property int $id
 * @property string $rule_key
 * @property string $name
 * @property string|null $description
 * @property string|null $severity
 * @property string|null $default_debt_char
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TdRule whereDefaultDebtChar($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TdRule whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TdRule whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TdRule whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TdRule whereRuleKey($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TdRule whereSeverity($value)
 * @mixin \Eloquent
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TdRule whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TdRule whereUpdatedAt($value)
 */
class TdRule extends Model
{

}
