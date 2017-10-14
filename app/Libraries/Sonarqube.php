<?php

namespace App\Libraries;

use App\CommitFile;
use App\ComponentSource;
use App\TdRule;
use Exception;
use Illuminate\Support\Facades\Log;

class Sonarqube
{
    protected $host;
    protected $project;

    function __construct($project)
    {
        $this->host = $_ENV['SONARQUBE_HOST'];
        $this->project = $project;
    }

    public function getFileMetrics(CommitFile $file, $sha)
    {
        $metricKeys = 'sqale_index,sqale_debt_ratio,blocker_violations,' .
            'critical_violations,major_violations,minor_violations,info_violations';
        $componentKey = $this->project . ':' . $sha . ':' . $file->filename;
        $url = $this->host . '/api/measures/component?metricKeys=' . $metricKeys . '&componentKey=' . $componentKey;

        try {

            $json = json_decode(file_get_contents($url));

            /**
             * SONAR returns TD in minutes
             */
            if(!isset($json->component) || !isset($json->component->measures)) {

                throw new Exception("Could not fetch TD for component: " . $componentKey);

            }

            $sonar_metrics = array();

            $measures = $json->component->measures;
            foreach ($measures as $measure) {

                $metric = $measure->metric;
                $value = $measure->value;

                $sonar_metrics[$metric] = $value;

            }

            return $sonar_metrics;


        } catch (Exception $ex) {

            Log::error($ex->getMessage());
            return [];

        }

    }

    public function getFileViolations(CommitFile $file, $sha, $file_metrics)
    {
        $componentKey = $this->project . ':' . $sha . ':' . $file->filename;
        $url = $this->host . '/api/issues/search?componentKeys=' . $componentKey . '&s=FILE_LINE&asc=true';

        try {

            $violations = [];

            $json = json_decode(file_get_contents($url));

            if(!$json->total) {
                return $violations;
            }

            foreach ($json->issues as $i) {

                $component_source = ComponentSource::select('id')
                    ->where('component_key', '=', $componentKey)
                    ->first();

                if(!$component_source) {

                    $sources = $this->getFileContent($file, $sha);
                    if($sources) {

                        $component_source = new ComponentSource();
                        $component_source->component_key = $componentKey;
                        $component_source->sources = serialize($sources);
                        $component_source->filename = $file->filename;
                        $component_source->revision = $sha;
                        $component_source->sqale_index = $file_metrics['sqale_index'];
                        $component_source->save();

                    }

                }

                $rule = TdRule::select('id')
                    ->where('rule_key', '=', $i->rule)
                    ->first();

                if(!$rule) {

                    $rule_info = $this->getRuleInfo($i->rule);
                    if($rule_info) {

                        $rule = new TdRule();
                        $rule->rule_key = $rule_info->key;
                        $rule->name = $rule_info->name;
                        $rule->description = $rule_info->htmlDesc;
                        $rule->severity = $rule_info->severity;
                        $rule->default_debt_char = (isset($rule_info->defaultDebtChar)) ? $rule_info->defaultDebtChar : null;
                        $rule->save();

                    }

                }

                $line = (isset($i->line)) ? $i->line : 0;

                $violations[$line . 'yyy' . $rule->id] = [

                    'key' => $i->key,
                    'line' => $line,
                    'message' => $i->message,
                    'tags' => serialize($i->tags),
                    'component_source_id' => $component_source->id,
                    'rule_id' => $rule->id,
                    'debt_string' => (isset($i->debt)) ? $i->debt : null

                ];

            }

            return $violations;

        } catch (Exception $ex) {

            Log::error($ex->getMessage());
            return [];

        }

    }

    public function getFileContent(CommitFile $file, $sha, $from = null, $to = null)
    {
        $componentKey = $this->project . ':' . $sha . ':' . $file->filename;
        $url = $this->host . '/api/sources/show?key=' . $componentKey;
        if(!is_null($from)) {
            $url .= '&from=' . $from;
        }
        if(!is_null($to)) {
            $url .= '&to=' . $to;
        }

        try {

            $json = json_decode(file_get_contents($url));

            if(!$json || !$json->sources) {
                return [];
            }

            return $json->sources;

        } catch (Exception $ex) {

            Log::error($ex->getMessage());
            return [];

        }

    }

    private function getRuleInfo($rule_key)
    {
        $url = $this->host . '/api/rules/search?rule_key=' . $rule_key;
        $json = json_decode(file_get_contents($url));

        if(!$json->total) {
            return false;
        }
        $rule = $json->rules[0];

        return $rule;
    }
}