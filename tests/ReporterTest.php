<?php

use App\Libraries\Reporter;
use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;

class ReporterTest extends TestCase
{
    public function testReporter()
    {
        $reporter = new Reporter();
        $reporter->compareCommitsTd();
    }
}
