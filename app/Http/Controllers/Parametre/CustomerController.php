<?php

namespace App\Http\Controllers\Parametre;

use Exception;
use Illuminate\Http\Request;
use App\Models\Parametre\Customer;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class CustomerController extends Controller
{
    public function index(){
        
        $countries = DB::table('countries')
                        ->select('libelle_country','id')
                        ->orderBy('libelle_country', 'ASC')
                        ->get();

        $menuPrincipal = "Paramètre";
        $titleControlleur = "Client";
        $btnModalAjout = "TRUE";

        if(Auth::user()->role == "Administrateur"){
            return view('parametre.customer.index', compact('countries','menuPrincipal', 'titleControlleur', 'btnModalAjout'));
        }else{
            return abort(404);
        }
    }

    public function listCustomers(){
        $customers = Customer::with('country')
                            ->select('customers.*')
                            ->orderBy('name', 'ASC')
                            ->get();

        $jsonData["rows"] = $customers->toArray();
        $jsonData["total"] = $customers->count();
        return response()->json($jsonData);
    }

    public function listCustomersByCountry($country){
        $customers = Customer::with('country')
                            ->select('customers.*')
                            ->where('country_id',$country)
                            ->orderBy('name', 'ASC')
                            ->get();

        $jsonData["rows"] = $customers->toArray();
        $jsonData["total"] = $customers->count();
        return response()->json($jsonData);
    }

    public function findCustomer($id){
        $customer = Customer::select('customers.*')
                            ->where('id',$id)
                            ->get();

        $jsonData["rows"] = $customer->toArray();
        $jsonData["total"] = $customer->count();
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

                $customer = $data['id'] ? Customer::findOrFail($data['id']) : new Customer;
                $customer->name = $data['name'];
                $customer->surname = $data['surname'];
                $customer->contact = $data['contact'];
                $customer->country_id = $data['country_id'];
                $customer->adress = isset($data['adress']) ? $data['adress'] : NULL;
                $customer->save();

                $jsonData["data"] = json_decode($customer);
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
        $customer = Customer::find($id);

        $jsonData = ["code" => 1, "msg" => "Opération effectuée avec succès."];
            if($customer){
                try {
                   
                    $customer->delete();
                    $jsonData["data"] = json_decode($customer);
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
