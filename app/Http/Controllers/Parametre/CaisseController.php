<?php

namespace App\Http\Controllers\Parametre;

use Exception;
use Illuminate\Http\Request;
use App\Models\Parametre\Caisse;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class CaisseController extends Controller
{
    public function index(){
        
        $countries = DB::table('countries')
                        ->select('libelle_country','id')
                        ->orderBy('libelle_country', 'ASC')
                        ->get();

        $menuPrincipal = "Paramètre";
        $titleControlleur = "Caisse";
        $btnModalAjout = Auth::user()->role == "Administrateur" ? "TRUE" : "FALSE";

        if(Auth::user()->role == "Administrateur" or Auth::user()->role == "Gerant"){
            return view('parametre.caisse.index', compact('countries','menuPrincipal', 'titleControlleur', 'btnModalAjout'));
        }else{
            return abort(404);
        }
    }

    public function listCaisse(){
        $caisses = Caisse::with('country','city','agency')
                            ->select('caisses.*')
                            ->orderBy('libelle_caisse', 'ASC')
                            ->get();

        $jsonData["rows"] = $caisses->toArray();
        $jsonData["total"] = $caisses->count();
        return response()->json($jsonData);
    }

    public function listCaisseByCountry($country){
        $caisses = Caisse::with('country','city','agency')
                            ->select('caisses.*')
                            ->orderBy('libelle_caisse', 'ASC')
                            ->where('country_id',$country)
                            ->get();

        $jsonData["rows"] = $caisses->toArray();
        $jsonData["total"] = $caisses->count();
        return response()->json($jsonData);
    }

    public function listCaisseByCity($city){
        $caisses = Caisse::with('country','city','agency')
                            ->select('caisses.*')
                            ->orderBy('libelle_caisse', 'ASC')
                            ->where('city_id',$city)
                            ->get();

        $jsonData["rows"] = $caisses->toArray();
        $jsonData["total"] = $caisses->count();
        return response()->json($jsonData);
    }

    public function listCaisseByAgency($agency){
        $caisses = Caisse::with('country','city','agency')
                            ->select('caisses.*')
                            ->orderBy('libelle_caisse', 'ASC')
                            ->where('agency_id',$agency)
                            ->get();

        $jsonData["rows"] = $caisses->toArray();
        $jsonData["total"] = $caisses->count();
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
        if ($request->isMethod('post') && $request->input('libelle_caisse')) {

                $data = $request->all();

            try {

                $caisse = $data['id'] ? Caisse::findOrFail($data['id']) : new Caisse;
                $caisse->libelle_caisse = $data['libelle_caisse'];
                $caisse->country_id = $data['country_id'];
                $caisse->city_id = isset($data['city_id']) ? $data['city_id'] : NULL;
                $caisse->agency_id = isset($data['agency_id']) ? $data['agency_id'] : NULL;
                $caisse->save();

                $jsonData["data"] = json_decode($caisse);
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
        $caisse = Caisse::find($id);

        $jsonData = ["code" => 1, "msg" => "Opération effectuée avec succès."];
            if($caisse){
                try {
                   
                    $caisse->delete();
                    $jsonData["data"] = json_decode($caisse);
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
