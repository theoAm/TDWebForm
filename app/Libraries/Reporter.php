<?php

namespace App\Libraries;


use App\Commit;
use App\CommitFile;
use App\Repo;
use App\TdDiff;
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

            $commits = $this->getCommits($json->author, $repo);
            if(!$commits->count()) {
                continue;
            }

            /** @var Commit $commit */
            foreach ($commits as $commit) {

                $progress ++;

                /** @var Commit $previous_commit */
                $previous_commit = $this->getPreviousCommit($commit);
                if(!$previous_commit) {
                    continue;
                }

                $commit_files = $this->getCommitFiles($commit, $repo);
                if(!$commit_files) {
                    continue;
                }

                /** @var CommitFile $commit_file */
                foreach ($commit_files as $commit_file) {

                    $metrics = $this->sonarqube->getFileMetrics($commit_file, $commit->sha);
                    $previous_metrics = $this->sonarqube->getFileMetrics($commit_file, $previous_commit->sha);
                    $diff_metrics = $this->compareFileMetrics($previous_metrics, $metrics);

                    if(!$diff_metrics) {
                        continue;
                    }

                    $this->saveDiffFileMetrics($diff_metrics, $repo, $json->author);

                    $violations = $this->sonarqube->getFileViolations($commit_file, $commit->sha);
                    $previous_violations = $this->sonarqube->getFileViolations($commit_file, $previous_commit->sha);
                    $diff_violations = $this->compareViolations($previous_violations, $violations);

                    if(!$diff_violations['resolved'] && !$diff_violations['added']) {
                        continue;
                    }

                    $this->saveDiffViolations($diff_violations, $repo, $json->author);

                }

                print ($progress % 10 == 0) ? $progress : ".";
            }

        }
    }

    private function getCommiters(Repo $repo)
    {
        $committers = Commit::select('author', DB::raw('COUNT(*) as count'))
            ->where('repo_id', $repo->id)
            ->groupBy('author')
            ->havingRaw('COUNT(*) >= 10')
            ->orderBy('count', 'DESC')
            ->get();
        return $committers;
    }

    private function getCommits($author, Repo $repo)
    {
        $commits = Commit::where('author', $author)
            ->where('repo_id', $repo->id)
            ->orderBy('committed_at', 'ASC')
            ->get();
        return $commits;
    }

    private function getCommitFiles(Commit $commit, Repo $repo)
    {
        $commit_files = CommitFile::where('commit_id', $commit->id)
            ->where('repo_id', $repo->id)
            ->where('filename', 'like', '%.php')
            ->get();
        return $commit_files;
    }

    private function getPreviousCommit(Commit $commit)
    {
        return Commit::where('committed_at', '<', $commit->committed_at)
            ->where('repo_id', $commit->repo_id)
            ->latest('committed_at')
            ->first();
    }

    private function compareViolations($previous_violations, $violations)
    {
        $added = [];
        $resolved = [];

        foreach ($violations as $i => $a) {
            if(!array_key_exists($i, $previous_violations)) {
                $added[$a['rule_id']] = $a;
            }
        }
        foreach ($previous_violations as $i => $r) {
            if(!array_key_exists($i, $violations)) {
                $resolved[$r['rule_id']] = $r;
            }
        }

        foreach ($added as $i => $a) {
            if(array_key_exists($i, $resolved)) {
                unset($resolved[$i]);
                unset($added[$i]);
            }
        }

        return [
            'resolved' => $resolved,
            'added' => $added
        ];
    }

    private function saveDiffViolations($diff_violations, Repo $repo, $author)
    {
        foreach ($diff_violations['added'] as $array) {

            $td_violation = new TdViolation();
            $td_violation->component_source_id = $array['component_source_id'];
            $td_violation->rule_id = $array['rule_id'];
            $td_violation->line = $array['line'];
            $td_violation->message = $array['message'];
            $td_violation->tags = $array['tags'];
            $td_violation->repo_id = $repo->id;
            $td_violation->author = $author;
            $td_violation->debt_string = $array['debt_string'];
            $td_violation->added_or_resolved = 'added';
            $td_violation->save();

        }

        foreach ($diff_violations['resolved'] as $array) {

            $td_violation = new TdViolation();
            $td_violation->component_source_id = $array['component_source_id'];
            $td_violation->rule_id = $array['rule_id'];
            $td_violation->line = $array['line'];
            $td_violation->message = $array['message'];
            $td_violation->tags = $array['tags'];
            $td_violation->repo_id = $repo->id;
            $td_violation->author = $author;
            $td_violation->debt_string = $array['debt_string'];
            $td_violation->added_or_resolved = 'resolved';
            $td_violation->save();

        }
    }

    private function compareFileMetrics($previous_metrics, $metrics)
    {
        $diff_metrics = [];

        if(!$previous_metrics || !$metrics) {
            return $diff_metrics;
        }

        if($previous_metrics['sqale_index'] == $metrics['sqale_index']) {
            return $diff_metrics;
        }

        $diff_metrics = [
            'sqale_index' => $metrics['sqale_index'] - $previous_metrics['sqale_index']
        ];

        return $diff_metrics;
    }

    private function saveDiffFileMetrics($diff_metrics, Repo $repo, $author)
    {
        $td_diff = new TdDiff();
        $td_diff->repo_id = $repo->id;
        $td_diff->author = $author;
        $td_diff->sqale_index_diff = $diff_metrics['sqale_index'];
        $td_diff->save();
    }
}