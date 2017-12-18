<?php

namespace App\Http\Controllers;

use App\Libraries\Reporter;
use App\Repo;
use App\TdViolation;
use App\TdViolationEvaluation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TdViolationsController extends Controller
{
    public function __construct()
    {

    }

    public function next(Request $request)
    {
        $author = $request->get('a');
        if(!$author) {
            abort(400);
        }

        $project = $request->get('p');
        if(!$project) {
            abort(400);
        }

        $token = $request->get('t');
        if(!$token) {
            abort(400);
        }

        if(md5($author . $project . $_ENV['APP_KEY']) != $token) {
            abort(403);
        }

        $resp = [];

        $tdViolation = $this->getNextViolationForEvaluation($author, $project);
        if(!$tdViolation) {
            return [];
        }

        $componentSource = $tdViolation->componentSource;
        $lines = unserialize($componentSource->sources);

        $reporter = new Reporter();

        $file_modifications_ranking = $reporter->fileModificationsRanking($tdViolation, $componentSource);

        $file_corrections_ranking = $reporter->fileCorrectionRanking($tdViolation, $componentSource);

        $file_sqaleindex_ranking = $reporter->fileSqaleIndexRanking($tdViolation, $componentSource);

        $resp = [

            'violation' => $tdViolation->id,
            'rule_name' => $tdViolation->rule->name,
            'severity' => $tdViolation->rule->severity,
            'tags' => implode(', ', unserialize($tdViolation->tags)),
            'author' => $tdViolation->author,
            'filename' => $componentSource->filename,
            'line' => $tdViolation->line,
            'message' => $tdViolation->message,
            'source' => $lines,
            'revision' => $componentSource->revision,
            'tdpayment' => $tdViolation->debt_string,
            'fileModificationsRank' => $file_modifications_ranking,
            'fileCorrectionsRank' => $file_corrections_ranking,
            'fileSqaleIndexRank' => $file_sqaleindex_ranking,
            'fileSqaleIndex' => $componentSource->sqale_index

        ];

        return $resp;
    }

    private function getNextViolationForEvaluation($author, $project)
    {
        $repo = Repo::where('name', '=', $project)->first();
        if(!$repo) {
            return null;
        }

        $tdViolations = TdViolation::select('td_violations.*', 'td_violation_evaluations.evaluator')
            ->leftJoin('td_violation_evaluations', function ($join) use ($author) {
                $join->on('td_violation_evaluations.td_violation_id', '=', 'td_violations.id')
                    ->where('evaluator', '=', $author);
            })
            ->where('repo_id', '=', $repo->id)
            ->get();

        if(!$tdViolations->count()) {
            return null;
        }

        $candidate_rules = [];

        $count = 0;

        /** @var TdViolation $tdViolation */
        foreach ($tdViolations as $tdViolation) {
            if($tdViolation->evaluator != $author) {
                $candidate_rules[$tdViolation->rule_id][] = $tdViolation->id;
                $count ++;
            }
        }

        //dd($count, $candidate_rules);

        $pick_rule = array_rand($candidate_rules);
        $candidate_violations = $candidate_rules[$pick_rule];
        $pick_violation = $candidate_violations[array_rand($candidate_violations)];

        $tdViolation = TdViolation::find($pick_violation);

        return $tdViolation;

    }

    public function evaluate(Request $request)
    {
        $e = $request->get('e');
        if($e < 0 || !is_numeric($e)) {
            $e = 0;
        }
        if(!$e) {
            abort(400);
        }
        if($e > 5) {
            abort(400);
        }

        $v = $request->get('v');
        if(!$v) {
            abort(400);
        }

        $a = $request->get('a');
        if(!$a) {
            abort(400);
        }

        $t = $request->get('t');
        if(!$t) {
            abort(400);
        }

        /** @var TdViolation $tdViolation */
        $tdViolation = TdViolation::find($v);
        if(!$tdViolation) {
            abort(404);
        }

        $c = $request->get('c');
        if(!$c) {
            $c = null;
        }

        /** @var Repo $repo */
        $repo = Repo::find($tdViolation->repo_id);

        if(md5($a . $repo->name . $_ENV['APP_KEY']) != $t) {
            abort(403);
        }

        $tdEvaluation = null;

        $tdEvaluation = TdViolationEvaluation::where('td_violation_id', '=', $tdViolation->id)
            ->where('evaluator', '=', $a)
            ->first();

        if(!$tdEvaluation) {
            $tdEvaluation = new TdViolationEvaluation();
        }

        $tdEvaluation->td_violation_id = $tdViolation->id;
        $tdEvaluation->evaluator = $a;
        $tdEvaluation->evaluation = $e;
        $tdEvaluation->comment = $c;
        $tdEvaluation->save();

        return $tdEvaluation;

    }
}
