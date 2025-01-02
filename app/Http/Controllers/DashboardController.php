<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Zobrazí dashboard pre autentifikovaných používateľov.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Prípadne môžete načítať dáta z databázy, ktoré chcete zobraziť na dashboarde.
        return view('dashboard.index');
    }
}

