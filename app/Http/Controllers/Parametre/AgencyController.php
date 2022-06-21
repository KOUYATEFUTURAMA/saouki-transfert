<?php

namespace App\Http\Controllers\Parametre;

use Exception;
use Illuminate\Http\Request;
use App\Models\Parametre\Agency;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Parametre\Caisse;
use Illuminate\Support\Facades\Auth;

class AgencyController extends Controller
{
    public function index(){
        
        $countries = DB::table('countries')
                        ->select('libelle_country','id')
                        ->orderBy('libelle_country', 'ASC')
                        ->get();

         $cities = DB::table('cities')
                        ->select('libelle_city','id')
                        ->orderBy('libelle_city', 'ASC')
                        ->get();

        $menuPrincipal = "Paramètre";
        $titleControlleur = "Agence";
        $btnModalAjout = Auth::user()->role == "Administrateur" ? "TRUE" : "FALSE";

        if(Auth::user()->role == "Administrateur" or Auth::user()->role == "Gerant"){
            return view('parametre.agency.index', compact('countries','cities','menuPrincipal', 'titleControlleur', 'btnModalAjout'));
        }else{
            return abort(404);
        }
    }

    public function listAgencies(){
        $agencies = Agency::with('country','city','municipality')
                            ->select('agencies.*')
                            ->orderBy('libelle_agency', 'ASC')
                            ->get();

        $jsonData["rows"] = $agencies->toArray();
        $jsonData["total"] = $agencies->count();
        return response()->json($jsonData);
    }

    public function listAgenciesByCountry($country){
        $agencies = Agency::with('country','city','municipality')
                            ->select('agencies.*')
                            ->orderBy('libelle_agency', 'ASC')
                            ->where('country_id',$country)
                            ->get();

        $jsonData["rows"] = $agencies->toArray();
        $jsonData["total"] = $agencies->count();
        return response()->json($jsonData);
    }
    public function listAgenciesByCity($city){
        $agencies = Agency::with('country','city','municipality')
                            ->select('agencies.*')
                            ->orderBy('libelle_agency', 'ASC')
                            ->where('city_id',$city)
                            ->get();

        $jsonData["rows"] = $agencies->toArray();
        $jsonData["total"] = $agencies->count();
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
        if ($request->isMethod('post') && $request->input('libelle_agency')) {

                $data = $request->all();

            try {

                $agency = $data['id'] ? Agency::findOrFail($data['id']) : new Agency;
                $agency->libelle_agency = $data['libelle_agency'];
                $agency->phone_agency = $data['phone_agency'];
                $agency->country_id = $data['country_id'];
                $agency->city_id = $data['city_id'];
                $agency->municipality_id = isset($data['municipality_id']) ? $data['municipality_id'] : NULL;
                $agency->adress_agency = isset($data['adress_agency']) ? $data['adress_agency'] : NULL;
                $agency->save();

                $jsonData["data"] = json_decode($agency);
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
        $agency = Agency::find($id);

        $jsonData = ["code" => 1, "msg" => "Opération effectuée avec succès."];
            if($agency){
                try {
                    
                    //if you want delet one agency you must delet first all caisse of this agency
                    $caisses = Caisse::where('agency_id', $id)->get();
                    if($caisses->count()>0){
                        return response()->json(["code" => 0, "msg" => "Impossible de supprimer cet enregistrement !", "data" => NULL]);
                    }

                    $agency->delete();
                    $jsonData["data"] = json_decode($agency);
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
