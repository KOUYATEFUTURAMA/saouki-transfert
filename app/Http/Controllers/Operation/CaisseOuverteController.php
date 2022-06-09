<?php

namespace App\Http\Controllers\Operation;

use Illuminate\Http\Request;
use App\Models\Parametre\City;
use App\Models\Parametre\Caisse;
use App\Models\Parametre\Country;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Operation\CaisseOuverte;

class CaisseOuverteController extends Controller
{
     /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $caisseOuverte = null; $caisse = NULL;  $authUser = Auth::user(); $country = null;
        $city = null;
        
        //Recupértion de la caisse dans la session
        if($request->session()->has('session_caisse_ouverte')){
            $caisseOuverteId = $request->session()->get('session_caisse_ouverte');
            $caisseOuverte = CaisseOuverte::where([['id',$caisseOuverteId],['date_fermeture',null]])->first();
        }
        if($caisseOuverte == null){
            //Si la caisse n'est pas fermée et que l'user s'est déconnecté
            $caisseOuverteNonFermee = CaisseOuverte::where([['user_id',$authUser->id],['date_fermeture',null]])->first();
            if($caisseOuverteNonFermee){
                $request->session()->put('session_caisse_ouverte',$caisseOuverteNonFermee->id);
                $caisseOuverte = CaisseOuverte::find($caisseOuverteNonFermee->id);
            }
        }
        if($caisseOuverte){
            $caisse = Caisse::find($caisseOuverte->caisse_id);
            $country = Country::find($caisse->country_id);
            if($caisse->city){
                $city = City::find($caisse->city);
            }
        }
        
        $menuPrincipal = "Opération";
        $titleControlleur = "Caisse";
        $btnModalAjout = ($caisseOuverte != null) ? "TRUE" : "FALSE";

        return view('operation.caisse.index', compact('caisse','caisseOuverte','country','city','menuPrincipal', 'titleControlleur', 'btnModalAjout'));
    }
}
