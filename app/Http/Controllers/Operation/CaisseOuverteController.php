<?php

namespace App\Http\Controllers\Operation;

use Exception;
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
            if($caisse->city_id){
                $city = City::find($caisse->city_id);
            }
        }
        
        $menuPrincipal = "Opération";
        $titleControlleur = "Caisse";
        $btnModalAjout = "FALSE";

        return view('operation.caisse.index', compact('caisse','caisseOuverte','country','city','menuPrincipal', 'titleControlleur', 'btnModalAjout'));
    }

    public function findOpenCaisse($id){
        $caisseOuverte = CaisseOuverte::with('user')
                            ->select('caisse_ouvertes.*')
                            ->where('caisse_ouvertes.id',$id)
                            ->get();

        $jsonData["rows"] = $caisseOuverte->toArray();
        $jsonData["total"] = $caisseOuverte->count();
        return response()->json($jsonData);
    }

    public function getCaisseInfosCloture($caisse){
        $totalEntre = 0; $totalSortie = 0; 
        $caisseOuverte = CaisseOuverte::join('operations','caisse_ouvertes.id','=','operations.caisse_ouverte_id')
                            ->select('operations.amount','operations.operation_type','caisse_ouvertes.*')
                            ->where([['caisse_ouvertes.id',$caisse],['caisse_ouvertes.date_fermeture',NULL]])
                            ->orderBy('id', 'DESC')
                            ->get();
        foreach ($caisseOuverte as $caisse) {
            if($caisse->operation_type == "withdrawal"){
                $totalSortie += $caisse->amount;
            }else{
                $totalEntre += $caisse->amount;
            }
        }
        $jsonData["rows"] = $caisseOuverte->toArray();
        $jsonData["totalEntre"] = $totalEntre;
        $jsonData["totalSortie"] = $totalSortie;
        return response()->json($jsonData);
    }


    /** Open caisse */
    public function openCaisse(Request $request){
        $jsonData = ["code" => 1, "msg" => "Ouverture effectuée avec succès."];
        if ($request->isMethod('post') && $request->input('caisse_id')) {

                $data = $request->all();

            try {
                    //Si la caisse est déjà ouverte ou n'existe pas
                    $Caisse = Caisse::find($data['caisse_id']);
                    if ($Caisse->ouverte == 1 or !$Caisse) {
                        return response()->json(["code" => 0, "msg" => "Cette caisse est déjà ouverte ou n'existe pas", "data" => null]);
                    }

                    //Si une caisse est déjà ouverte part cet utilisateur
                    $caisse_ouverte_sans_fermee = CaisseOuverte::where([['caisse_id', $data['caisse_id']], ['user_id', Auth::user()->id], ['date_fermeture', null]])->first();
                    if ($caisse_ouverte_sans_fermee) {
                        return response()->json(["code" => 0, "msg" => "Vous avez une session ouverte sur cette caisse.", "data" => null]);
                    }

                    //Ouverture caisse
                    $caisseOuverte = new CaisseOuverte;
                    $caisseOuverte->montant_ouverture = $data['montant_ouverture'];
                    $caisseOuverte->date_ouverture = now();
                    $caisseOuverte->caisse_id = $Caisse->id;
                    $caisseOuverte->user_id = Auth::user()->id;
                    $caisseOuverte->save();

                    //Mise à jour caisse
                    $Caisse->ouverte = TRUE;
                    $Caisse->save();
                    
                    //Stockage en session
                    $request->session()->put('session_caisse_ouverte', $caisseOuverte->id);
                    $jsonData["data"] = json_decode($caisseOuverte);
                    return response()->json($jsonData);

            } catch (Exception $exc) {
                    $jsonData["code"] = -1;
                    $jsonData["data"] = NULL;
                    $jsonData["msg"] = $exc->getMessage();
                    return response()->json($jsonData);
            }
        }
        return response()->json(["code" => 0, "msg" => "Saisie invalide", "data" => NULL]);
    }

    /**Close caisse */
    /**
    * Fermeture de la caisse
    */
    public function closeCaisse(Request $request){
        
        $jsonData = ["code" => 1, "msg" => "Ouverture effectuée avec succès."];
        $data = $request->all();

        $caisseOuverte = CaisseOuverte::find($data['caisse_a_fermer']);

        if ($caisseOuverte && $caisseOuverte->date_fermeture == null) {

            try {
                    //On récupere la caisse pour fermer
                    $caisse = Caisse::find($caisseOuverte->caisse_id);
                    if ($caisse->ouverte == 0 or !$caisse) {
                        return response()->json(["code" => 0, "msg" => "Cette caisse est déjà fermée ou n'existe pas", "data" => null]);
                    }

                    if($data['solde_fermeture'] < 0){
                        return response()->json(["code" => 0, "msg" => "Vous avez un solde négatif ! Veillez contactez un administrateur", "data" => null]);
                    }

                    //Fermer caisse
                    $caisse->ouverte = FALSE;
                    $caisse->save();

                    //Mise à jour caisse ouverte
                    $caisseOuverte->solde_fermeture = $data['solde_fermeture'];
                    $caisseOuverte->date_fermeture = now();
                    $caisseOuverte->observation = isset($data["observation"]) ? $data["observation"] : NULL;
                    $caisseOuverte->save();
                    
                    //Destruction de la session de caisse ouverte
                    if ($request->session()->has('session_caisse_ouverte')) {
                        $request->session()->forget('session_caisse_ouverte');
                    }

                    $jsonData["data"] = json_decode($caisseOuverte);
                    return response()->json($jsonData);

            } catch (Exception $exc) {
                    $jsonData["code"] = -1;
                    $jsonData["data"] = NULL;
                    $jsonData["msg"] = $exc->getMessage();
                    return response()->json($jsonData);
            }
        }
        return response()->json(["code" => 0, "msg" => "Echec de fermeture", "data" => NULL]);
    }
}
