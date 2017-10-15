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
        $hash = $request->get('h');
        if(!$hash) {
            return false;
        }


        $params = $this->extractParams($this->decryptHash($hash));
        if(count($params) != 2) {
            return false;
        }

        $author = $params[0];
        $project = $params[1];
        if(!$author || !$project) {
            return false;
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

    private function decryptHash($hash)
    {
        $cipher = $_ENV['CIPHER'];
        $options = 0;
        $ivlen = openssl_cipher_iv_length($cipher);
        $key = substr($_ENV['APP_KEY'], '0', $ivlen);

        return openssl_decrypt($hash, $cipher, $key, $options, $key);
    }

    private function extractParams($decryptHash)
    {
        return explode($_ENV['STRING_DELIMITER'], $decryptHash);
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
