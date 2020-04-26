<?php

namespace App\Http\Controllers;

use App\Document;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $documents = Document::where('status', 'published')->latest()->limit(5)->get();

        $reviews = Document::where('status', '<>', 'published')->latest()->limit(5)->get();

        return view('home', [
            'latestDocuments' => $documents,
            'reviews' => $reviews,
            'searchterm' => ''
        ]);
    }
    
}
