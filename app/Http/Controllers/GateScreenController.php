<?php

namespace App\Http\Controllers;

use App\Models\Parking;

class GateScreenController extends Controller
{
    /**
     * Show the fullscreen gate display screen.
     */
    public function index()
    {
        $activeCount = Parking::active()->count();

        return view('gate-screen', compact('activeCount'));
    }
}
