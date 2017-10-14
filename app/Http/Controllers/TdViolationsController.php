<?php

namespace App\Http\Controllers;

use App\Libraries\Reporter;
use App\TdViolation;

class TdViolationsController extends Controller
{
    public function __construct()
    {

    }

    public function next($author_hash)
    {
        $resp = [];

        /** @var TdViolation $tdViolation */
        $tdViolation = TdViolation::with('rule')
            ->with('repo')
            ->find(215);

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
}
