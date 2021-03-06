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
        $titleControlleur = "Administrateur";
        $btnModalAjout = "FALSE";

        return view('home-admin', compact('menuPrincipal', 'titleControlleur', 'btnModalAjout'));
    }

    public function superviseur()
    {
        $menuPrincipal = "Tableau de bord";
        $titleControlleur = "Superviseur";
        $btnModalAjout = "FALSE";

        return view('home-superviseur',compact('menuPrincipal', 'titleControlleur', 'btnModalAjout'));
    }
    public function comptable()
    {
        $menuPrincipal = "Tableau de bord";
        $titleControlleur = "Comptable";
        $btnModalAjout = "FALSE";

        return view('home-comptable',compact('menuPrincipal', 'titleControlleur', 'btnModalAjout'));
    }
    public function agent()
    {
        $menuPrincipal = "Tableau de bord";
        $titleControlleur = "Agent";
        $btnModalAjout = "FALSE";

        return view('home-agent',compact('menuPrincipal', 'titleControlleur', 'btnModalAjout'));
    }
}
