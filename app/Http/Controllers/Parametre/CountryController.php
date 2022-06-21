<?php

namespace App\Http\Controllers\Parametre;

use Exception;
use Illuminate\Http\Request;
use App\Models\Parametre\Country;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Parametre\City;
use Illuminate\Support\Facades\Auth;

class CountryController extends Controller
{
    public function index(){

        $menuPrincipal = "Paramètre";
        $titleControlleur = "Pays";
        $btnModalAjout = Auth::user()->role == "Administrateur" ? "TRUE" : "FALSE";

        if(Auth::user()->role == "Administrateur" or Auth::user()->role == "Gerant"){
            return view('parametre.country.index', compact('menuPrincipal', 'titleControlleur', 'btnModalAjout'));
        }else{
            return abort(404);
        }
    }

    public function listCountries(){

        $countries = DB::table('countries')
                        ->select('libelle_country','id')
                        ->orderBy('libelle_country', 'ASC')
                        ->get();

        $jsonData["rows"] = $countries->toArray();
        $jsonData["total"] = $countries->count();
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
        if ($request->isMethod('post') && $request->input('libelle_country')) {

                $data = $request->all();

            try {

                $Country = Country::where([['libelle_country', $data['libelle_country']],['id','!=',$data['id']]])->first();
                if($Country){
                    return response()->json(["code" => 0, "msg" => "Cet enregistrement existe déjà dans la base", "data" => NULL]);
                }

                $country = $data['id'] ? Country::findOrFail($data['id']) : new Country;
                $country->libelle_country = $data['libelle_country'];
                $country->save();

                $jsonData["data"] = json_decode($country);
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
        $country = Country::find($id);

        $jsonData = ["code" => 1, "msg" => "Opération effectuée avec succès."];
            if($country){
                try {

                    //if you want delet one country you must delet first all cities of this country
                    $cities = City::where('country_id', $id)->get();
                    if($cities->count()>0){
                        return response()->json(["code" => 0, "msg" => "Impossible de supprimer cet enregistrement !", "data" => NULL]);
                    }
                    
                    $country->delete();
                    $jsonData["data"] = json_decode($country);
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
