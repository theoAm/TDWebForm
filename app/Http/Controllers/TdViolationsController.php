<?php

namespace App\Http\Controllers;

use App\Libraries\Reporter;
use App\Repo;
use App\TdViolation;
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
            return false;
        }

        $project = $request->get('p');
        if(!$project) {
            return false;
        }

        $token = $request->get('t');
        if(!$token) {
            return false;
        }

        if(md5($author . $project . $_ENV['APP_KEY']) != $token) {
            abort(403);
        }

        $resp = [];

        $tdViolation = $this->getNextViolationForEvaluation($author, $project);
        if(!$tdViolation) {
            return null;
        }

        $componentSource = $tdViolation->componentSource;
        $lines = unserialize($componentSource->sources);

        $reporter = new Reporter();

        $file_modifications_ranking = $reporter->fileModificationsRanking($tdViolation, $componentSource);

        $file_corrections_ranking = $reporter->fileCorrectionRanking($tdViolation, $componentSource);

        $file_sqaleindex_ranking = $reporter->fileSqaleIndexRanking($tdViolation, $componentSource);

        $resp = [

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

        $rules_count = [];

        $rules = DB::table('td_violations')
            ->select(DB::raw('DISTINCT td_violations.rule_id'))
            ->where('td_violations.repo_id', '=', $repo->id)
            ->where('td_violations.author', '=', $author)
            ->orderBy('td_violations.rule_id')
            ->get();

        foreach ($rules as $item) {

            $rule_id = $item->rule_id;

            $count = DB::table('td_violations')
                ->select(DB::raw('COUNT(*) AS count'))
                ->join('td_violation_evaluations', 'td_violation_evaluations.td_violation_id', '=', 'td_violations.id')
                ->where('td_violations.repo_id', '=', $repo->id)
                ->where('td_violations.author', '=', $author)
                ->where('td_violation_evaluations.evaluator', '=', $author)
                ->where('td_violations.rule_id', '=', $rule_id)
                ->first();

            $rules_count[$rule_id] = $count->count;

        }

        $candidate_rules = array_keys($rules_count, min($rules_count));

        $pick_rule = $candidate_rules[array_rand($candidate_rules)];

        $tdViolation = TdViolation::where('author', '=', $author)
            ->where('rule_id', '=', $pick_rule)
            ->where('repo_id', '=', $repo->id)
            ->whereRaw('id NOT IN (SELECT td_violation_id FROM td_violation_evaluations)')
            ->first();

        return $tdViolation;

    }
}
