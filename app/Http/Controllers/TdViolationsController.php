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
        /** @var TdViolation $tdViolation */
        $tdViolation = TdViolation::with('tdDiff')->find(665);

        return $tdViolation;
    }
}
