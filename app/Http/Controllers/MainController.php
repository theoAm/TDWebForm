<?php

namespace App\Http\Controllers;

use App\Commit;
use App\Repo;
use App\TdDiff;
use App\TdViolation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MainController extends Controller
{
    public function index(Request $request)
    {
        $author = $request->get('a');
        $project = $request->get('p');
        $token = $request->get('t');

        if(!$author || !$project || !$token) {
            abort(404);
        }

        return view('violation',[
            'ajax_host' => $_ENV['AJAX_HOST'],
            'author' => $author,
            'project' => $project,
            'token' => $token,
        ]);
    }

    public function thankYou()
    {
        return view('thank_you');
    }

    public function report(Request $request)
    {
        $author = $request->get('a');
        $project = $request->get('p');
        $token = $request->get('t');

        if(!$author || !$project || !$token) {
            abort(404);
        }

        if(md5($author . $project . $_ENV['APP_KEY']) !== $token) {
            abort(403);
        }

        $repo = Repo::where('name', '=', $project)->first();
        if(!$repo){
            abort(404);
        }

        $total_commits = Commit::where('repo_id', '=', $repo->id)->count();
        $dev_commits = Commit::where('repo_id', '=', $repo->id)
            ->where('author', '=', $author)
            ->count();

        $total_td_added = TdDiff::where('repo_id', '=', $repo->id)->sum('sqale_index_diff');
        $dev_td_added = TdDiff::where('repo_id', '=', $repo->id)
            ->where('author', '=', $author)
            ->sum('sqale_index_diff');

        $ratio_commits = $dev_commits/$total_commits;
        $ratio_td_added = $dev_td_added/$total_td_added;

        $dev_violations_grouped = DB::table('td_violations')
            ->join('td_rules', 'td_rules.id', '=', 'td_violations.rule_id')
            ->select(DB::raw('rule_id, td_rules.name, td_rules.description, COUNT(*) AS count'))
            ->where('author', '=', $author)
            ->where('added_or_resolved', '=', 'added')
            ->groupBy('rule_id')
            ->orderBy('count', 'desc')
            ->limit(5)
            ->get();

        dd($dev_violations_grouped);

        dd($ratio_commits, $ratio_td_added);

    }
}
