<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\QuaterApplication; // Correct model name

class QuarterApplicationController extends Controller
{
    /**
     * Display the quarter application form.
     */
    public function create()
    {
        return view('quarterapplication');
    }

    /**
     * Store a newly created quarter application in storage.
     */
    public function store(Request $request)
    {
        // For now, let's just dump the request data to see what's being sent
        dd($request->all());

        // Later, you will add validation and data saving logic here.
    }
}
