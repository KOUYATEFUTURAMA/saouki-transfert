<?php

namespace App\Http\Controllers\Parametre;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Parametre\Partenair;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class PartenairController extends Controller
{
    public function index(){
        
        $countries = DB::table('countries')
                        ->select('libelle_country','id')
                        ->orderBy('libelle_country', 'ASC')
                        ->get();

        $menuPrincipal = "Paramètre";
        $titleControlleur = "Client";
        $btnModalAjout = Auth::user()->role == "Administrateur" ? "TRUE" : "FALSE";

        if(Auth::user()->role == "Administrateur" or Auth::user()->role == "Gerant"){
            return view('parametre.partenair.index', compact('countries','menuPrincipal', 'titleControlleur', 'btnModalAjout'));
        }else{
            return abort(404);
        }
    }

    public function listPartenairs(){
        $partenairs = Partenair::with('country')
                            ->select('partenairs.*')
                            ->orderBy('name', 'ASC')
                            ->get();

        $jsonData["rows"] = $partenairs->toArray();
        $jsonData["total"] = $partenairs->count();
        return response()->json($jsonData);
    }
    public function findPartenair($id){
        $partenairs = Partenair::select('partenairs.contact')
                            ->where('partenairs.id',$id)
                            ->orderBy('name', 'ASC')
                            ->get();

        $jsonData["rows"] = $partenairs->toArray();
        $jsonData["total"] = $partenairs->count();
        return response()->json($jsonData);
    }
    public function listPartenairsByCountry($country){
        $partenairs = Partenair::with('country')
                            ->select('partenairs.*')
                            ->where('country_id',$country)
                            ->orderBy('name', 'ASC')
                            ->get();

        $jsonData["rows"] = $partenairs->toArray();
        $jsonData["total"] = $partenairs->count();
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
        if ($request->isMethod('post') && $request->input('name')) {

                $data = $request->all();

            try {

                $partenair = $data['id'] ? Partenair::findOrFail($data['id']) : new Partenair;
                $partenair->name = $data['name'];
                $partenair->contact = $data['contact'];
                $partenair->country_id = $data['country_id'];
                $partenair->adress = isset($data['adress']) ? $data['adress'] : NULL;
                $partenair->save();

                $jsonData["data"] = json_decode($partenair);
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
        $partenair = Partenair::find($id);

        $jsonData = ["code" => 1, "msg" => "Opération effectuée avec succès."];
            if($partenair){
                try {
                   
                    $partenair->delete();
                    $jsonData["data"] = json_decode($partenair);
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
