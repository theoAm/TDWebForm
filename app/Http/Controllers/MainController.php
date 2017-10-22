<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

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
}
