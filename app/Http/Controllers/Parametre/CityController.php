<?php

namespace App\Http\Controllers\Parametre;

use Exception;
use Illuminate\Http\Request;
use App\Models\Parametre\City;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Parametre\Municipality;
use Illuminate\Support\Facades\Auth;

class CityController extends Controller
{
    public function index(){
        
        $countries = DB::table('countries')
                        ->select('libelle_country','id')
                        ->orderBy('libelle_country', 'ASC')
                        ->get();

        $menuPrincipal = "Paramètre";
        $titleControlleur = "Ville";
        $btnModalAjout = "TRUE";

        if(Auth::user()->role == "Administrateur"){
            return view('parametre.city.index', compact('countries','menuPrincipal', 'titleControlleur', 'btnModalAjout'));
        }else{
            return abort(404);
        }
    }

    public function listCities(){
        $cities = City::with('country')
                            ->select('cities.*')
                            ->orderBy('libelle_city', 'ASC')
                            ->get();

        $jsonData["rows"] = $cities->toArray();
        $jsonData["total"] = $cities->count();
        return response()->json($jsonData);
    }

    public function listCitiesByCountry($country){
        $cities = City::with('country')
                            ->select('cities.*')
                            ->where('country_id',$country)
                            ->orderBy('libelle_city', 'ASC')
                            ->get();

        $jsonData["rows"] = $cities->toArray();
        $jsonData["total"] = $cities->count();
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
        if ($request->isMethod('post') && $request->input('libelle_city')) {

                $data = $request->all();

            try {

                $city = $data['id'] ? City::findOrFail($data['id']) : new City;
                $city->libelle_city = $data['libelle_city'];
                $city->country_id = $data['country_id'];
                $city->save();

                $jsonData["data"] = json_decode($city);
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
        $city = City::find($id);

        $jsonData = ["code" => 1, "msg" => "Opération effectuée avec succès."];
            if($city){
                try {
                   
                    //if you want delet one city you must delet first all municipalities of this city
                    $municipalities = Municipality::where('city_id', $id)->get();
                    if($municipalities->count()>0){
                        return response()->json(["code" => 0, "msg" => "Impossible de supprimer cet enregistrement !", "data" => NULL]);
                    }

                    $city->delete();
                    $jsonData["data"] = json_decode($city);
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
