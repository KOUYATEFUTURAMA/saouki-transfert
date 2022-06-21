<?php

namespace App\Http\Controllers\Parametre;

use Exception;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Parametre\TauxTransfert;

class TauxTransfertController extends Controller
{
    public function index(){

        $menuPrincipal = "Paramètre";
        $titleControlleur = "Taux de transfert";
        $btnModalAjout = Auth::user()->role == "Administrateur" ? "TRUE" : "FALSE";

        if(Auth::user()->role == "Administrateur" or Auth::user()->role == "Gerant"){
            return view('parametre.taux-transfert.index', compact('menuPrincipal', 'titleControlleur', 'btnModalAjout'));
        }else{
            return abort(404);
        }
    }

    public function listTauxTransfert(){
        $tauxTransferts = TauxTransfert::select('taux_transferts.*')
                            ->orderBy('interval_ligne', 'ASC')
                            ->get();
        
        $jsonData["rows"] = $tauxTransferts->toArray();
        $jsonData["total"] = $tauxTransferts->count();
        return response()->json($jsonData);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $jsonData = ["code" => 1, "msg" => "Enregistrement effectué avec succès."];
        if ($request->isMethod('post') && $request->input('montant_minimum') && $request->input('montant_maximum')) {

                $data = $request->all();

            try {

                if(empty($data['montant_fixe']) && empty($data['taux'])){
                    return response()->json(["code" => 0, "msg" => "Définissez le taux ou un montant fixe !", "data" => NULL]);
                }

                if(isset($data['montant_fixe']) && isset($data['taux'])){
                    return response()->json(["code" => 0, "msg" => "Définissez soit le taux ou le montant fixe et non les 2 en meme temps !", "data" => NULL]);
                }

                $tauxTransfert = $data['id'] ? TauxTransfert::findOrFail($data['id']) : new TauxTransfert;
                $tauxTransfert->interval_ligne = $data['interval_ligne'];
                $tauxTransfert->montant_minimum = $data['montant_minimum'];
                $tauxTransfert->montant_maximum = $data['montant_maximum'];
                $tauxTransfert->montant_fixe = isset($data['montant_fixe']) ? $data['montant_fixe'] : NULL;
                $tauxTransfert->taux = isset($data['taux']) ? ($data['taux']*0.01) : NULL;
                $tauxTransfert->save();

                $jsonData["data"] = json_decode($tauxTransfert);
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

    public function destroy($id)
    {
        $tauxTransfert = TauxTransfert::find($id);

        $jsonData = ["code" => 1, "msg" => "Opération effectuée avec succès."];
            if($tauxTransfert){
                try {
                   
                    $tauxTransfert->delete();
                    $jsonData["data"] = json_decode($tauxTransfert);
                    return response()->json($jsonData);

                } catch (Exception $exc) {
                   $jsonData["code"] = -1;
                   $jsonData["data"] = NULL;
                   $jsonData["msg"] = $exc->getMessage();
                   return response()->json($jsonData);
                }
            }
        return response()->json(["code" => 0, "msg" => "Echec de suppression", "data" => NULL]);
    }

}
