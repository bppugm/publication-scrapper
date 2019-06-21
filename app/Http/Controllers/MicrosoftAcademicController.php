<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Jobs\MicrosoftAcademicScrapper;

class MicrosoftAcademicController extends Controller
{
    public function index(Request $request)
    {
        return view('microsoft-academic.index');
    }

    public function store(Request $request)
    {
        $request->validate([
            'year' => 'required',
            'email' => 'required|email'
        ]);

        MicrosoftAcademicScrapper::dispatch($request->email, $request->year);

        return redirect()->route('microsoft-academic.index')
        ->with('success', 'Your request has been processed. Please check your email in a couple minutes.');
    }
}
