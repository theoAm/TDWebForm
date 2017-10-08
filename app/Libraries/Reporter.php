<?php

namespace App\Libraries;


use App\Commit;
use App\CommitFile;
use App\Repo;
use App\TdViolation;
use Illuminate\Support\Facades\DB;

class Reporter
{
    protected $repo_owner;
    protected $repo_name;
    protected $sonarqube;

    function __construct()
    {
        $this->repo_owner = $_ENV['REPO_OWNER'];
        $this->repo_name = $_ENV['REPO_NAME'];
        $this->sonarqube = new Sonarqube($this->repo_name);
    }

    public function compareCommitsTd()
    {
        $repo = Repo::where('name', $this->repo_name)
            ->where('owner', $this->repo_owner)
            ->first();
        if(!$repo) {
            return false;
        }

        $progress = 0;

        $committers = $this->getCommiters($repo);
        if(!$committers) {
            return false;
        }

        foreach ($committers as $json) {

            $commits = $this->getCommits($json, $repo);
            if(!$commits->count()) {
                continue;
            }

            /** @var Commit $commit */
            foreach ($commits as $commit) {

                $progress ++;

                $commit_files = $this->getCommitFiles($commit, $repo);
                if(!$commit_files) {
                    continue;
                }

                /** @var CommitFile $commit_file */
                foreach ($commit_files as $commit_file) {

                    $violations = $this->sonarqube->getFileViolations($commit_file, $commit->sha);

                    foreach ($violations as $array) {

                        $td_violation = new TdViolation();
                        $td_violation->component_source_id = $array['component_source_id'];
                        $td_violation->rule_id = $array['rule_id'];
                        $td_violation->line = $array['line'];
                        $td_violation->message = $array['message'];
                        $td_violation->save();

                    }

                }

                print ($progress % 10 == 0) ? $progress : ".";
            }

        }
    }

    private function getCommiters($repo)
    {
        $committers = Commit::select('author', DB::raw('COUNT(*) as count'))
            ->where('repo_id', $repo->id)
            ->groupBy('author')
            ->havingRaw('COUNT(*) >= 10')
            ->orderBy('count', 'DESC')
            ->get();
        return $committers;
    }

    private function getCommits($json, $repo)
    {
        $commits = Commit::where('author', $json->author)
            ->where('repo_id', $repo->id)
            ->orderBy('committed_at', 'ASC')
            ->get();
        return $commits;
    }

    private function getCommitFiles($commit, $repo)
    {
        $commit_files = CommitFile::where('commit_id', $commit->id)
            ->where('repo_id', $repo->id)
            ->where('filename', 'like', '%.php')
            ->get();
        return $commit_files;
    }
}