<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\TdViolationEvaluation
 *
 * @property int $id
 * @property int|null $td_violation_id
 * @property string|null $evaluator
 * @property int|null $evaluation
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TdViolationEvaluation whereEvaluation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TdViolationEvaluation whereEvaluator($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TdViolationEvaluation whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TdViolationEvaluation whereTdViolationId($value)
 * @mixin \Eloquent
 */
class TdViolationEvaluation extends Model
{
    
}
