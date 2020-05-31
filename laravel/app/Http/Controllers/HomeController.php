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

        $array = Document::selectRaw('status, count(*) as count')->groupBy('status')->get();
        $total = 0;
        $stats = [];
        foreach ($array as $item) {
            $count = $item->count;
            $kpi = (object) ['type' => $item->status];
            $kpi->value = $count;
            array_push($stats, $kpi);
            $total += $count;
        }
        $kpi = (object) ['type' => 'total'];
        $kpi->value = $total;
        array_push($stats, $kpi);

        return view('home', [
            'latestDocuments' => $documents,
            'reviews' => $reviews,
            'stats' => $stats,
            'searchterm' => ''
        ]);
    }
    
}
