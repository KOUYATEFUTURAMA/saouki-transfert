<?php

namespace App\Http\Controllers\Parametre;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Parametre\Municipality;

class MunicipalityController extends Controller
{
    public function index(){
        
        $cities = DB::table('cities')
                        ->select('libelle_city','id')
                        ->orderBy('libelle_city', 'ASC')
                        ->get();

        $menuPrincipal = "Paramètre";
        $titleControlleur = "Commune";
        $btnModalAjout = "TRUE";

        if(Auth::user()->role == "Administrateur"){
            return view('parametre.municipality.index', compact('cities','menuPrincipal', 'titleControlleur', 'btnModalAjout'));
        }else{
            return abort(404);
        }
    }

    public function listMunicipalities(){
        $municipalities = Municipality::with('city')
                            ->select('municipalities.*')
                            ->orderBy('libelle_municipality', 'ASC')
                            ->get();

        $jsonData["rows"] = $municipalities->toArray();
        $jsonData["total"] = $municipalities->count();
        return response()->json($jsonData);
    }

    public function listMunicipalitiesByCity($city){
        $municipalities = Municipality::with('city')
                            ->select('municipalities.*')
                            ->where('city_id',$city)
                            ->orderBy('libelle_municipality', 'ASC')
                            ->get();

        $jsonData["rows"] = $municipalities->toArray();
        $jsonData["total"] = $municipalities->count();
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
        if ($request->isMethod('post') && $request->input('libelle_municipality')) {

                $data = $request->all();

            try {

                $municipality = $data['id'] ? Municipality::findOrFail($data['id']) : new Municipality;
                $municipality->libelle_municipality = $data['libelle_municipality'];
                $municipality->city_id = $data['city_id'];
                $municipality->save();

                $jsonData["data"] = json_decode($municipality);
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
        $municipality = Municipality::find($id);

        $jsonData = ["code" => 1, "msg" => "Opération effectuée avec succès."];
            if($municipality){
                try {
                    
                    $municipality->delete();
                    $jsonData["data"] = json_decode($municipality);
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
