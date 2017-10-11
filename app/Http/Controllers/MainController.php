<?php

namespace App\Http\Controllers;

class MainController extends Controller
{
    public function index()
    {
        return view('violation',['ajax_host' => $_ENV['AJAX_HOST']]);
    }
}
