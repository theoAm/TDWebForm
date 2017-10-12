<?php

namespace App\Http\Controllers;

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
            ->find(88);

        $componentSource = $tdViolation->componentSource;
        $lines = unserialize($componentSource->sources);

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
            'tdpayment' => $tdViolation->debt_string

        ];

        return $resp;
    }
}
