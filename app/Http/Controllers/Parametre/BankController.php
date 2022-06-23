<?php

namespace App\Http\Controllers\Parametre;

use Exception;
use Illuminate\Http\Request;
use App\Models\Parametre\Bank;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class BankController extends Controller
{
    public function index(){
        $countries = DB::table('countries')
                        ->select('libelle_country','id')
                        ->orderBy('libelle_country', 'ASC')
                        ->get();

        $menuPrincipal = "Paramètre";
        $titleControlleur = "Banque";
        $btnModalAjout = Auth::user()->role == "Administrateur" ? "TRUE" : "FALSE";

        if(Auth::user()->role == "Administrateur" or Auth::user()->role == "Gerant"){
            return view('parametre.bank.index', compact('countries','menuPrincipal', 'titleControlleur', 'btnModalAjout'));
        }else{
            return abort(404);
        }
    }

    public function listBank(){
        $banks = Bank::with('country')
                            ->select('banks.*')
                            ->orderBy('libelle_bank', 'ASC')
                            ->get();

        $jsonData["rows"] = $banks->toArray();
        $jsonData["total"] = $banks->count();
        return response()->json($jsonData);
    }

    public function findBank($id){
        $banks = Bank::with('country')
                            ->select('banks.*')
                            ->where('id',$id)
                            ->get();

        $jsonData["rows"] = $banks->toArray();
        $jsonData["total"] = $banks->count();
        return response()->json($jsonData);
    }

    public function listBankByCountry($country){
        $banks = Bank::with('country')
                            ->select('banks.*')
                            ->where('country_id',$country)
                            ->orderBy('libelle_bank', 'ASC')
                            ->get();

        $jsonData["rows"] = $banks->toArray();
        $jsonData["total"] = $banks->count();
        return response()->json($jsonData);
    }

    public function listSoldeBanks($banque = null){
        $banks = Bank::with('country')
                            ->select('banks.*')
                            ->where('country_id',$banque)
                            ->orderBy('libelle_bank', 'ASC')
                            ->get();

        $jsonData["rows"] = $banks->toArray();
        $jsonData["total"] = $banks->count();
        return response()->json($jsonData);
    }

    public function listSoldeBankByCountry($country){
        $banks = Bank::with('country')
                            ->select('banks.*')
                            ->where('country_id',$country)
                            ->orderBy('libelle_bank', 'ASC')
                            ->get();

        $jsonData["rows"] = $banks->toArray();
        $jsonData["total"] = $banks->count();
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
        if ($request->isMethod('post') && $request->input('libelle_bank')) {

                $data = $request->all();

            try {

                $bank = $data['id'] ? Bank::findOrFail($data['id']) : new Bank;
                $bank->libelle_bank = $data['libelle_bank'];
                $bank->contact = $data['contact'];
                $bank->country_id = $data['country_id'];
                $bank->email = isset($data['email']) ? $data['email'] : NULL;
                $bank->adress = isset($data['adress']) ? $data['adress'] : NULL;
                $bank->save();

                $jsonData["data"] = json_decode($bank);
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
        $bank = Bank::find($id);

        $jsonData = ["code" => 1, "msg" => "Opération effectuée avec succès."];
            if($bank){
                try {
                   
                    $bank->delete();
                    $jsonData["data"] = json_decode($bank);
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
