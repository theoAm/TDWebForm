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
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property string|null $comment
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TdViolationEvaluation whereComment($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TdViolationEvaluation whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TdViolationEvaluation whereEvaluation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TdViolationEvaluation whereEvaluator($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TdViolationEvaluation whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TdViolationEvaluation whereTdViolationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TdViolationEvaluation whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class TdViolationEvaluation extends Model
{
    
}
