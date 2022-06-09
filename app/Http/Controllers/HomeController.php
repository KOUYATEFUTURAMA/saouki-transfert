<?php

namespace App\Http\Controllers;

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
    public function admin()
    {
        $menuPrincipal = "Tableau de bord";
        $titleControlleur = "";
        $btnModalAjout = "FALSE";

        return view('home-admin', compact('menuPrincipal', 'titleControlleur', 'btnModalAjout'));
    }

    public function superviseur()
    {
        $menuPrincipal = "Tableau de bord";
        $titleControlleur = "";
        $btnModalAjout = "FALSE";

        return view('home-superviseur',compact('menuPrincipal', 'titleControlleur', 'btnModalAjout'));
    }
    public function comptable()
    {
        return view('home-comptable');
    }
    public function agent()
    {
        return view('home-agent');
    }
}
