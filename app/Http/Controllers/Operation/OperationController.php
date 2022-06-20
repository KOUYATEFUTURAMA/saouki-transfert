<?php

namespace App\Http\Controllers\Operation;

use Image;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Models\Operation\Operation;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Operation\CaisseOuverte;
include_once(app_path() . "/number-to-letters/nombre_en_lettre.php");

class OperationController extends Controller
{
    //** Partenair */
    public function vueOperationPartenair(){

        $partenairs = DB::table('partenairs')
                        ->select('name','id')
                        ->orderBy('name', 'ASC')
                        ->get();

        $menuPrincipal = "Opération";
        $titleControlleur = "des parténaires";
        $btnModalAjout = (Auth::user()->role == "Superviseur" or Auth::user()->role == "Comptable") ? "TRUE" : "FALSE";

        if(Auth::user()->role != "Agent"){
            return view('operation.operation-partenair.index', compact('partenairs','menuPrincipal', 'titleControlleur', 'btnModalAjout'));
        }else{
            return abort(404);
        }
    }
    public function listOperationsPartenairs(){
        if(Auth::user()->role == "Administrateur" or Auth::user()->role == "Gerant"){
            $operations = Operation::with('partenair','user','authorized_by')
                            ->select('operations.*',DB::raw('DATE_FORMAT(date, "%d-%m-%Y %H:%i") as dateOperation'),DB::raw('DATE_FORMAT(authorization_date, "%d-%m-%Y %H:%i") as authorizationDate'))
                            ->orderBy('id', 'DESC')
                            ->where('partenair_id','!=',NULL)
                            ->get();
        }
        if(Auth::user()->role == "Superviseur"){
            $operations = Operation::with('partenair','user','authorized_by')
                            ->join('caisse_ouvertes','caisse_ouvertes.id','=','operations.caisse_ouverte_id')
                            ->join('users','users.id','=','operations.user_id')
                            ->select('operations.*',DB::raw('DATE_FORMAT(date, "%d-%m-%Y %H:%i") as dateOperation'),DB::raw('DATE_FORMAT(authorization_date, "%d-%m-%Y %H:%i") as authorizationDate'))
                            ->orderBy('id', 'DESC')
                            ->where([['partenair_id','!=',NULL],['caisse_ouvertes.date_fermeture',NULL],['users.country_id',Auth::user()->country_id]])
                            ->get();
        }
        if(Auth::user()->role == "Comptable"){
            $operations = Operation::with('partenair','user','authorized_by')
                            ->join('caisse_ouvertes','caisse_ouvertes.id','=','operations.caisse_ouverte_id')
                            ->select('operations.*',DB::raw('DATE_FORMAT(date, "%d-%m-%Y %H:%i") as dateOperation'),DB::raw('DATE_FORMAT(authorization_date, "%d-%m-%Y %H:%i") as authorizationDate'))
                            ->orderBy('id', 'DESC')
                            ->where([['partenair_id','!=',NULL],['caisse_ouvertes.date_fermeture',NULL],['operations.user_id',Auth::user()->id]])
                            ->get();
        }

        $jsonData["rows"] = $operations->toArray();
        $jsonData["total"] = $operations->count();
        return response()->json($jsonData);
    }
    public function listOperationsByPartenairs($partenair){
        if(Auth::user()->role == "Administrateur" or Auth::user()->role == "Gerant"){
            $operations = Operation::with('partenair','user','authorized_by')
                            ->select('operations.*',DB::raw('DATE_FORMAT(date, "%d-%m-%Y %H:%i") as dateOperation'),DB::raw('DATE_FORMAT(authorization_date, "%d-%m-%Y %H:%i") as authorizationDate'))
                            ->orderBy('id', 'DESC')
                            ->where('partenair_id',$partenair)
                            ->get();
        }
        if(Auth::user()->role == "Superviseur"){
            $operations = Operation::with('partenair','user','authorized_by')
                            ->join('caisse_ouvertes','caisse_ouvertes.id','=','operations.caisse_ouverte_id')
                            ->join('users','users.id','=','operations.user_id')
                            ->select('operations.*',DB::raw('DATE_FORMAT(date, "%d-%m-%Y %H:%i") as dateOperation'),DB::raw('DATE_FORMAT(authorization_date, "%d-%m-%Y %H:%i") as authorizationDate'))
                            ->orderBy('id', 'DESC')
                            ->where([['partenair_id','!=',NULL],['caisse_ouvertes.date_fermeture',NULL],['users.country_id',Auth::user()->country_id],['partenair_id',$partenair]])
                            ->get();
        }
        if(Auth::user()->role == "Comptable"){
            $operations = Operation::with('partenair','user','authorized_by')
                            ->join('caisse_ouvertes','caisse_ouvertes.id','=','operations.caisse_ouverte_id')
                            ->select('operations.*',DB::raw('DATE_FORMAT(date, "%d-%m-%Y %H:%i") as dateOperation'),DB::raw('DATE_FORMAT(authorization_date, "%d-%m-%Y %H:%i") as authorizationDate'))
                            ->orderBy('id', 'DESC')
                            ->where([['partenair_id',$partenair],['operations.user_id',Auth::user()->id],['caisse_ouvertes.date_fermeture',NULL]])
                            ->get();
        }

        $jsonData["rows"] = $operations->toArray();
        $jsonData["total"] = $operations->count();
        return response()->json($jsonData);
    }

    //** Bank */
    public function vueOperationBank(){

        $banks = DB::table('banks')
                        ->select('libelle_bank','id')
                        ->orderBy('libelle_bank', 'ASC')
                        ->get();

        $menuPrincipal = "Opération";
        $titleControlleur = "des banques";
        $btnModalAjout = (Auth::user()->role == "Superviseur" or Auth::user()->role == "Comptable") ? "TRUE" : "FALSE";

        if(Auth::user()->role != "Agent"){
            return view('operation.operation-bank.index', compact('banks','menuPrincipal', 'titleControlleur', 'btnModalAjout'));
        }else{
            return abort(404);
        }
    }
    public function listOperationsBanks(){
        if(Auth::user()->role == "Administrateur" or Auth::user()->role == "Gerant"){
            $operations = Operation::with('bank','user','authorized_by')
                            ->join('banks','banks.id','=','operations.bank_id')
                            ->join('countries','countries.id','=','banks.country_id')
                            ->select('operations.*','countries.libelle_country',DB::raw('DATE_FORMAT(date, "%d-%m-%Y %H:%i") as dateOperation'),DB::raw('DATE_FORMAT(authorization_date, "%d-%m-%Y %H:%i") as authorizationDate'))
                            ->orderBy('id', 'DESC')
                            ->where('bank_id','!=',NULL)
                            ->get();
        }
        if(Auth::user()->role == "Superviseur" or Auth::user()->role == "Comptable"){
            $operations = Operation::with('bank','user','authorized_by')
                            ->join('caisse_ouvertes','caisse_ouvertes.id','=','operations.caisse_ouverte_id')
                            ->join('banks','banks.id','=','operations.bank_id')
                            ->join('countries','countries.id','=','banks.country_id')
                            ->select('operations.*','countries.libelle_country',DB::raw('DATE_FORMAT(date, "%d-%m-%Y %H:%i") as dateOperation'),DB::raw('DATE_FORMAT(authorization_date, "%d-%m-%Y %H:%i") as authorizationDate'))
                            ->orderBy('id', 'DESC')
                            ->where([['bank_id','!=',NULL],['operations.user_id',Auth::user()->id],['caisse_ouvertes.date_fermeture',NULL]])
                            ->get();
        }

        $jsonData["rows"] = $operations->toArray();
        $jsonData["total"] = $operations->count();
        return response()->json($jsonData);
    }
    public function listOperationsByBanks($bank){
        if(Auth::user()->role == "Administrateur" or Auth::user()->role == "Gerant"){
            $operations = Operation::with('bank','user','authorized_by')
                            ->join('banks','banks.id','=','operations.bank_id')
                            ->join('countries','countries.id','=','banks.country_id')
                            ->select('operations.*','countries.libelle_country',DB::raw('DATE_FORMAT(date, "%d-%m-%Y %H:%i") as dateOperation'),DB::raw('DATE_FORMAT(authorization_date, "%d-%m-%Y %H:%i") as authorizationDate'))
                            ->orderBy('id', 'DESC')
                            ->where('bank_id',$bank)
                            ->get();
        }
        if(Auth::user()->role == "Superviseur" or Auth::user()->role == "Comptable"){
            $operations = Operation::with('bank','user','authorized_by')
                            ->join('caisse_ouvertes','caisse_ouvertes.id','=','operations.caisse_ouverte_id')
                            ->join('banks','banks.id','=','operations.bank_id')
                            ->join('countries','countries.id','=','banks.country_id')
                            ->select('operations.*','countries.libelle_country',DB::raw('DATE_FORMAT(date, "%d-%m-%Y %H:%i") as dateOperation'),DB::raw('DATE_FORMAT(authorization_date, "%d-%m-%Y %H:%i") as authorizationDate'))
                            ->orderBy('id', 'DESC')
                            ->where([['bank_id',$bank],['operations.user_id',Auth::user()->id],['caisse_ouvertes.date_fermeture',NULL]])
                            ->get();
        }

        $jsonData["rows"] = $operations->toArray();
        $jsonData["total"] = $operations->count();
        return response()->json($jsonData);
    }

    //** Décaissement - Encaissement */
    public function vueOperationDecaissementEncaissement(){
        if(Auth::user()->role == "Superviseur"){
            $caisses = CaisseOuverte::join('caisses','caisses.id','=','caisse_ouvertes.caisse_id')
                        ->select('libelle_caisse','caisse_ouvertes.id')
                        ->where([['caisse_ouvertes.date_fermeture',NULL],['user_id','!=',Auth::user()->id],['caisses.country_id',Auth::user()->country_id],['caisses.ouverte',1],['caisses.agency_id',NULL]])
                        ->orderBy('libelle_caisse', 'ASC')
                        ->get();
        }
        if(Auth::user()->role == "Agent"){
            $caisses = CaisseOuverte::join('caisses','caisses.id','=','caisse_ouvertes.caisse_id')
                        ->select('libelle_caisse','caisse_ouvertes.id')
                        ->where([['caisse_ouvertes.date_fermeture',NULL],['user_id','!=',Auth::user()->id],['caisses.city_id',Auth::user()->city_id],['caisses.ouverte',1],['caisses.agency_id',NULL]])
                        ->orderBy('libelle_caisse', 'ASC')
                        ->get();
        }
        if(Auth::user()->role == "Comptable"){
            $caisses = CaisseOuverte::join('caisses','caisses.id','=','caisse_ouvertes.caisse_id')
                        ->select('libelle_caisse','caisse_ouvertes.id')
                        ->where([['caisse_ouvertes.date_fermeture',NULL],['user_id','!=',Auth::user()->id],['caisses.city_id',Auth::user()->citry_id],['caisses.agency_id','!=',NULL]])
                        ->orWhere([['caisse_ouvertes.date_fermeture',NULL],['user_id','!=',Auth::user()->id],['caisses.country_id',Auth::user()->country_id]])
                        ->orderBy('libelle_caisse', 'ASC')
                        ->get();
        }
        $menuPrincipal = "Opération";
        $titleControlleur = "de décaissement et encaissement";
        $btnModalAjout = "TRUE";

        return view('operation.decaissement-encaissement.index', compact('caisses','menuPrincipal', 'titleControlleur', 'btnModalAjout'));
    }
    public function listOperationsDecaissementEncaissement(){
        if(Auth::user()->role == "Administrateur" or Auth::user()->role == "Gerant"){
            $operations = Operation::with('user','authorized_by')
                            ->join('caisse_ouvertes as caisse_provenance','caisse_provenance.id','=','operations.caisse_ouverte_id')
                            ->join('caisse_ouvertes as caisse_destination','caisse_destination.id','=','operations.other_caisse_id')
                            ->join('caisses as caissP','caissP.id','=','caisse_provenance.caisse_id')
                            ->join('caisses as caissD','caissD.id','=','caisse_destination.caisse_id')
                            ->select('operations.*','caissP.libelle_caisse as libel_cais_p','caissD.libelle_caisse as lib_cais_d',DB::raw('DATE_FORMAT(date, "%d-%m-%Y %H:%i") as dateOperation'),DB::raw('DATE_FORMAT(authorization_date, "%d-%m-%Y %H:%i") as authorizationDate'))
                            ->where('other_caisse_id','!=',NULL)
                            ->orderBy('id', 'DESC')
                            ->get();
        }
        if(Auth::user()->role == "Superviseur" or Auth::user()->role == "Comptable"){
            $operations = Operation::with('user','authorized_by')
                            ->join('caisse_ouvertes as caisse_provenance','caisse_provenance.id','=','operations.caisse_ouverte_id')
                            ->join('caisse_ouvertes as caisse_destination','caisse_destination.id','=','operations.other_caisse_id')
                            ->join('caisses as caissP','caissP.id','=','caisse_provenance.caisse_id')
                            ->join('caisses as caissD','caissD.id','=','caisse_destination.caisse_id')
                            ->select('operations.*','caissP.libelle_caisse as libel_cais_p','caissD.libelle_caisse as lib_cais_d',DB::raw('DATE_FORMAT(date, "%d-%m-%Y %H:%i") as dateOperation'),DB::raw('DATE_FORMAT(authorization_date, "%d-%m-%Y %H:%i") as authorizationDate'))
                            ->where([['other_caisse_id','!=',NULL],['operations.user_id',Auth::user()->id],['caisse_provenance.date_fermeture',NULL]])
                            ->orderBy('id', 'DESC')
                            ->get();
        }
        if(Auth::user()->role == "Agent"){
            $operations = Operation::with('user','authorized_by','agency')
                            ->join('caisse_ouvertes as caisse_provenance','caisse_provenance.id','=','operations.caisse_ouverte_id')
                            ->join('caisse_ouvertes as caisse_destination','caisse_destination.id','=','operations.other_caisse_id')
                            ->join('caisses as caissP','caissP.id','=','caisse_provenance.caisse_id')
                            ->join('caisses as caissD','caissD.id','=','caisse_destination.caisse_id')
                            ->select('operations.*','caissP.libelle_caisse as libel_cais_p','caissD.libelle_caisse as lib_cais_d',DB::raw('DATE_FORMAT(date, "%d-%m-%Y %H:%i") as dateOperation'),DB::raw('DATE_FORMAT(authorization_date, "%d-%m-%Y %H:%i") as authorizationDate'))
                            ->where([['other_caisse_id','!=',NULL],['operations.user_id',Auth::user()->id],['caisse_provenance.date_fermeture',NULL]])
                            ->orderBy('id', 'DESC')
                            ->get();
        }

        $jsonData["rows"] = $operations->toArray();
        $jsonData["total"] = $operations->count();
        return response()->json($jsonData);
    }
    public function listOperationsDecaissementEncaissementByCaisse($caisse){
        if(Auth::user()->role == "Administrateur" or Auth::user()->role == "Gerant"){
            $operations = Operation::with('user','authorized_by')
                            ->join('caisse_ouvertes as caisse_provenance','caisse_provenance.id','=','operations.caisse_ouverte_id')
                            ->join('caisse_ouvertes as caisse_destination','caisse_destination.id','=','operations.other_caisse_id')
                            ->join('caisses as caissP','caissP.id','=','caisse_provenance.caisse_id')
                            ->join('caisses as caissD','caissD.id','=','caisse_destination.caisse_id')
                            ->select('operations.*','caissP.libelle_caisse as libel_cais_p','caissD.libelle_caisse as lib_cais_d',DB::raw('DATE_FORMAT(date, "%d-%m-%Y %H:%i") as dateOperation'),DB::raw('DATE_FORMAT(authorization_date, "%d-%m-%Y %H:%i") as authorizationDate'))
                            ->where('other_caisse_id',$caisse)
                            ->orderBy('id', 'DESC')
                            ->get();
        }
        if(Auth::user()->role == "Superviseur" or Auth::user()->role == "Comptable"){
            $operations = Operation::with('user','authorized_by')
                            ->join('caisse_ouvertes as caisse_provenance','caisse_provenance.id','=','operations.caisse_ouverte_id')
                            ->join('caisse_ouvertes as caisse_destination','caisse_destination.id','=','operations.other_caisse_id')
                            ->join('caisses as caissP','caissP.id','=','caisse_provenance.caisse_id')
                            ->join('caisses as caissD','caissD.id','=','caisse_destination.caisse_id')
                            ->select('operations.*','caissP.libelle_caisse as libel_cais_p','caissD.libelle_caisse as lib_cais_d',DB::raw('DATE_FORMAT(date, "%d-%m-%Y %H:%i") as dateOperation'),DB::raw('DATE_FORMAT(authorization_date, "%d-%m-%Y %H:%i") as authorizationDate'))
                            ->where([['other_caisse_id',$caisse],['operations.user_id',Auth::user()->id],['caisse_provenance.date_fermeture',NULL]])
                            ->orderBy('id', 'DESC')
                            ->get();
        }
        
        $jsonData["rows"] = $operations->toArray();
        $jsonData["total"] = $operations->count();
        return response()->json($jsonData);
    }

    /**Opération */
    public function listOperations(){
        $totalEntree = 0; $totalSortie = 0;
        if(Auth::user()->role == "Administrateur" or Auth::user()->role == "Gerant"){
            $operations = Operation::with('user','authorized_by','bank','partenair')
                            ->select('operations.*',DB::raw('DATE_FORMAT(date, "%d-%m-%Y %H:%i") as dateOperation'),DB::raw('DATE_FORMAT(authorization_date, "%d-%m-%Y %H:%i") as authorizationDate'))
                            ->orderBy('id', 'DESC')
                            ->get();
            foreach ($operations as $operation) {
                if($operation->operation_type == "deposit"){
                    $totalEntree += $operation->amount;
                }else{
                    $totalSortie += $operation->amount;
                }
            }
        }
        if(Auth::user()->role == "Superviseur"){
            $operations = Operation::with('user','authorized_by','bank','partenair')
                            ->join('caisse_ouvertes','caisse_ouvertes.id','=','operations.caisse_ouverte_id')
                            ->select('operations.*',DB::raw('DATE_FORMAT(date, "%d-%m-%Y %H:%i") as dateOperation'),DB::raw('DATE_FORMAT(authorization_date, "%d-%m-%Y %H:%i") as authorizationDate'))
                            ->where([['operations.user_id',Auth::user()->id],['caisse_ouvertes.date_fermeture',NULL]])
                            ->orderBy('id', 'DESC')
                            ->get();
            foreach ($operations as $operation) {
                if($operation->operation_type == "deposit"){
                    $totalEntree += $operation->amount;
                }else{
                    $totalSortie += $operation->amount;
                }
            }
        }
        if(Auth::user()->role == "Comptable"){
            $operations = Operation::with('user','authorized_by','bank','partenair')
                            ->join('caisse_ouvertes','caisse_ouvertes.id','=','operations.caisse_ouverte_id')
                            ->select('operations.*',DB::raw('DATE_FORMAT(date, "%d-%m-%Y %H:%i") as dateOperation'),DB::raw('DATE_FORMAT(authorization_date, "%d-%m-%Y %H:%i") as authorizationDate'))
                            ->where([['operations.user_id',Auth::user()->id],['caisse_ouvertes.date_fermeture',NULL]])
                            ->orderBy('id', 'DESC')
                            ->get();
            foreach ($operations as $operation) {
                if($operation->operation_type == "deposit"){
                    $totalEntree += $operation->amount;
                }else{
                    $totalSortie += $operation->amount;
                }
            }
        }
        if(Auth::user()->role == "Agent"){
            $operations = Operation::with('user','authorized_by','bank','partenair')
                            ->join('caisse_ouvertes','caisse_ouvertes.id','=','operations.caisse_ouverte_id')
                            ->select('operations.*',DB::raw('DATE_FORMAT(date, "%d-%m-%Y %H:%i") as dateOperation'),DB::raw('DATE_FORMAT(authorization_date, "%d-%m-%Y %H:%i") as authorizationDate'))
                            ->where([['operations.user_id',Auth::user()->id],['caisse_ouvertes.date_fermeture',NULL],['operations.state',"authorized"]])
                            ->orderBy('id', 'DESC')
                            ->get();
            foreach ($operations as $operation) {
                if($operation->operation_type == "deposit"){
                    $totalEntree += $operation->amount;
                }else{
                    $totalSortie += $operation->amount;
                }
            }
        }

        $jsonData["rows"] = $operations->toArray();
        $jsonData["total"] = $operations->count();
        $jsonData["totalEntree"] = $totalEntree;
        $jsonData["totalSortie"] = $totalSortie;
        $jsonData["totalSolde"] = ($totalEntree - $totalSortie);
        return response()->json($jsonData);
    }

    public function listOperationsByReference($reference){
        $totalEntree = 0; $totalSortie = 0;
        if(Auth::user()->role == "Administrateur" or Auth::user()->role == "Gerant"){
            $operations = Operation::with('user','authorized_by','bank','partenair')
                            ->select('operations.*',DB::raw('DATE_FORMAT(date, "%d-%m-%Y %H:%i") as dateOperation'),DB::raw('DATE_FORMAT(authorization_date, "%d-%m-%Y %H:%i") as authorizationDate'))
                            ->where('reference','like','%'.$reference.'%')
                            ->orderBy('id', 'DESC')
                            ->get();
            foreach ($operations as $operation) {
                if($operation->operation_type == "deposit"){
                    $totalEntree += $operation->amount;
                }else{
                    $totalSortie += $operation->amount;
                }
            }
        }
        if(Auth::user()->role == "Superviseur" or Auth::user()->role == "Comptable"){
            $operations = Operation::with('user','authorized_by','bank','partenair')
                            ->join('caisse_ouvertes','caisse_ouvertes.id','=','operations.caisse_ouverte_id')
                            ->select('operations.*',DB::raw('DATE_FORMAT(date, "%d-%m-%Y %H:%i") as dateOperation'),DB::raw('DATE_FORMAT(authorization_date, "%d-%m-%Y %H:%i") as authorizationDate'))
                            ->where([['operations.user_id',Auth::user()->id],['caisse_ouvertes.date_fermeture',NULL],['reference','like','%'.$reference.'%']])
                            ->orderBy('id', 'DESC')
                            ->get();
            foreach ($operations as $operation) {
                if($operation->operation_type == "deposit"){
                    $totalEntree += $operation->amount;
                }else{
                    $totalSortie += $operation->amount;
                }
            }
        }
        if(Auth::user()->role == "Agent"){
            $operations = Operation::with('user','authorized_by','bank','partenair')
                            ->join('caisse_ouvertes','caisse_ouvertes.id','=','operations.caisse_ouverte_id')
                            ->select('operations.*',DB::raw('DATE_FORMAT(date, "%d-%m-%Y %H:%i") as dateOperation'),DB::raw('DATE_FORMAT(authorization_date, "%d-%m-%Y %H:%i") as authorizationDate'))
                            ->where([['operations.user_id',Auth::user()->id],['caisse_ouvertes.date_fermeture',NULL],['operations.state',"authorized"],['reference','like','%'.$reference.'%']])
                            ->orderBy('id', 'DESC')
                            ->get();
            foreach ($operations as $operation) {
                if($operation->operation_type == "deposit"){
                    $totalEntree += $operation->amount;
                }else{
                    $totalSortie += $operation->amount;
                }
            }
        }

        $jsonData["rows"] = $operations->toArray();
        $jsonData["total"] = $operations->count();
        $jsonData["totalEntree"] = $totalEntree;
        $jsonData["totalSortie"] = $totalSortie;
        $jsonData["totalSolde"] = ($totalEntree - $totalSortie);
        return response()->json($jsonData);
    }

    public function listOperationsByType($type){
        $totalEntree = 0; $totalSortie = 0;
        if(Auth::user()->role == "Administrateur" or Auth::user()->role == "Gerant"){
            $operations = Operation::with('user','authorized_by','bank','partenair')
                            ->select('operations.*',DB::raw('DATE_FORMAT(date, "%d-%m-%Y %H:%i") as dateOperation'),DB::raw('DATE_FORMAT(authorization_date, "%d-%m-%Y %H:%i") as authorizationDate'))
                            ->where('operation_type',$type)
                            ->orderBy('id', 'DESC')
                            ->get();
            foreach ($operations as $operation) {
                if($operation->operation_type == "deposit"){
                    $totalEntree += $operation->amount;
                }else{
                    $totalSortie += $operation->amount;
                }
            }
        }
        if(Auth::user()->role == "Superviseur" or Auth::user()->role == "Comptable"){
            $operations = Operation::with('user','authorized_by','bank','partenair')
                            ->join('caisse_ouvertes','caisse_ouvertes.id','=','operations.caisse_ouverte_id')
                            ->select('operations.*',DB::raw('DATE_FORMAT(date, "%d-%m-%Y %H:%i") as dateOperation'),DB::raw('DATE_FORMAT(authorization_date, "%d-%m-%Y %H:%i") as authorizationDate'))
                            ->where([['operations.user_id',Auth::user()->id],['caisse_ouvertes.date_fermeture',NULL],['operation_type',$type]])
                            ->orderBy('id', 'DESC')
                            ->get();
            foreach ($operations as $operation) {
                if($operation->operation_type == "deposit"){
                    $totalEntree += $operation->amount;
                }else{
                    $totalSortie += $operation->amount;
                }
            }
        }
        if(Auth::user()->role == "Agent"){
            $operations = Operation::with('user','authorized_by','bank','partenair')
                            ->join('caisse_ouvertes','caisse_ouvertes.id','=','operations.caisse_ouverte_id')
                            ->select('operations.*',DB::raw('DATE_FORMAT(date, "%d-%m-%Y %H:%i") as dateOperation'),DB::raw('DATE_FORMAT(authorization_date, "%d-%m-%Y %H:%i") as authorizationDate'))
                            ->where([['operations.user_id',Auth::user()->id],['caisse_ouvertes.date_fermeture',NULL],['operations.state',"authorized"],['operation_type',$type]])
                            ->orderBy('id', 'DESC')
                            ->get();
            foreach ($operations as $operation) {
                if($operation->operation_type == "deposit"){
                    $totalEntree += $operation->amount;
                }else{
                    $totalSortie += $operation->amount;
                }
            }
        }

        $jsonData["rows"] = $operations->toArray();
        $jsonData["total"] = $operations->count();
        $jsonData["totalEntree"] = $totalEntree;
        $jsonData["totalSortie"] = $totalSortie;
        $jsonData["totalSolde"] = ($totalEntree - $totalSortie);
        return response()->json($jsonData);
    }

    public function listOperationsByState($state){
        $totalEntree = 0; $totalSortie = 0;
        if(Auth::user()->role == "Administrateur" or Auth::user()->role == "Gerant"){
            $operations = Operation::with('user','authorized_by','bank','partenair')
                            ->select('operations.*',DB::raw('DATE_FORMAT(date, "%d-%m-%Y %H:%i") as dateOperation'),DB::raw('DATE_FORMAT(authorization_date, "%d-%m-%Y %H:%i") as authorizationDate'))
                            ->where('state',$state)
                            ->orderBy('id', 'DESC')
                            ->get();
            foreach ($operations as $operation) {
                if($operation->operation_type == "deposit"){
                    $totalEntree += $operation->amount;
                }else{
                    $totalSortie += $operation->amount;
                }
            }
        }
        if(Auth::user()->role == "Superviseur" or Auth::user()->role == "Comptable"){
            $operations = Operation::with('user','authorized_by','bank','partenair')
                            ->join('caisse_ouvertes','caisse_ouvertes.id','=','operations.caisse_ouverte_id')
                            ->select('operations.*',DB::raw('DATE_FORMAT(date, "%d-%m-%Y %H:%i") as dateOperation'),DB::raw('DATE_FORMAT(authorization_date, "%d-%m-%Y %H:%i") as authorizationDate'))
                            ->where([['operations.user_id',Auth::user()->id],['caisse_ouvertes.date_fermeture',NULL],['state',$state]])
                            ->orderBy('id', 'DESC')
                            ->get();
            foreach ($operations as $operation) {
                if($operation->operation_type == "deposit"){
                    $totalEntree += $operation->amount;
                }else{
                    $totalSortie += $operation->amount;
                }
            }
        }
        if(Auth::user()->role == "Agent"){
            $operations = Operation::with('user','authorized_by','bank','partenair')
                            ->join('caisse_ouvertes','caisse_ouvertes.id','=','operations.caisse_ouverte_id')
                            ->select('operations.*',DB::raw('DATE_FORMAT(date, "%d-%m-%Y %H:%i") as dateOperation'),DB::raw('DATE_FORMAT(authorization_date, "%d-%m-%Y %H:%i") as authorizationDate'))
                            ->where([['operations.user_id',Auth::user()->id],['caisse_ouvertes.date_fermeture',NULL],['state',$state]])
                            ->orderBy('id', 'DESC')
                            ->get();
            foreach ($operations as $operation) {
                if($operation->operation_type == "deposit"){
                    $totalEntree += $operation->amount;
                }else{
                    $totalSortie += $operation->amount;
                }
            }
        }
        $jsonData["rows"] = $operations->toArray();
        $jsonData["total"] = $operations->count();
        $jsonData["totalEntree"] = $totalEntree;
        $jsonData["totalSortie"] = $totalSortie;
        $jsonData["totalSolde"] = ($totalEntree - $totalSortie);
        return response()->json($jsonData);
    }

    public function listOperationsByConcerne($concerne){
        $totalEntree = 0; $totalSortie = 0;
        if(Auth::user()->role == "Administrateur" or Auth::user()->role == "Gerant"){
            $operations = Operation::with('user','authorized_by','bank','partenair')
                            ->select('operations.*',DB::raw('DATE_FORMAT(date, "%d-%m-%Y %H:%i") as dateOperation'),DB::raw('DATE_FORMAT(authorization_date, "%d-%m-%Y %H:%i") as authorizationDate'))
                            ->where($concerne,'!=',NULL)
                            ->orderBy('id', 'DESC')
                            ->get();
            foreach ($operations as $operation) {
                if($operation->operation_type == "deposit"){
                    $totalEntree += $operation->amount;
                }else{
                    $totalSortie += $operation->amount;
                }
            }
        }
        if(Auth::user()->role == "Superviseur" or Auth::user()->role == "Comptable"){
            $operations = Operation::with('user','authorized_by','bank','partenair')
                            ->join('caisse_ouvertes','caisse_ouvertes.id','=','operations.caisse_ouverte_id')
                            ->select('operations.*',DB::raw('DATE_FORMAT(date, "%d-%m-%Y %H:%i") as dateOperation'),DB::raw('DATE_FORMAT(authorization_date, "%d-%m-%Y %H:%i") as authorizationDate'))
                            ->where([['operations.user_id',Auth::user()->id],['caisse_ouvertes.date_fermeture',NULL],[$concerne,'!=',NULL]])
                            ->orderBy('id', 'DESC')
                            ->get();
            foreach ($operations as $operation) {
                if($operation->operation_type == "deposit"){
                    $totalEntree += $operation->amount;
                }else{
                    $totalSortie += $operation->amount;
                }
            }
        }
        if(Auth::user()->role == "Agent"){
            $operations = Operation::with('user','authorized_by','bank','partenair')
                            ->join('caisse_ouvertes','caisse_ouvertes.id','=','operations.caisse_ouverte_id')
                            ->select('operations.*',DB::raw('DATE_FORMAT(date, "%d-%m-%Y %H:%i") as dateOperation'),DB::raw('DATE_FORMAT(authorization_date, "%d-%m-%Y %H:%i") as authorizationDate'))
                            ->where([['operations.user_id',Auth::user()->id],['caisse_ouvertes.date_fermeture',NULL],['operations.state',"authorized"],[$concerne,'!=',NULL]])
                            ->orderBy('id', 'DESC')
                            ->get();
            foreach ($operations as $operation) {
                if($operation->operation_type == "deposit"){
                    $totalEntree += $operation->amount;
                }else{
                    $totalSortie += $operation->amount;
                }
            }
        }

        $jsonData["rows"] = $operations->toArray();
        $jsonData["total"] = $operations->count();
        $jsonData["totalEntree"] = $totalEntree;
        $jsonData["totalSortie"] = $totalSortie;
        $jsonData["totalSolde"] = ($totalEntree - $totalSortie);
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
        if ($request->isMethod('post') && $request->input('dateOperation')) {

                $data = $request->all();

            try {

                //Vérification de l'ouverture de la caisse 
                $caisseOuverte = CaisseOuverte::where([['user_id',Auth::user()->id],['date_fermeture',NULL]])->first();
                if(!$caisseOuverte){
                    return response()->json(["code" => 0, "msg" => "Votre caisse est fermée", "data" => NULL]);
                }

                //Vérification du solde de la caisse s'il s'agit d'un retrait
                if($data['operation_type'] == "withdrawal"){
                    $operations = Operation::where('caisse_ouverte_id',$caisseOuverte->id)->get();
                    $totalSortie = 0; $totalEntree = 0; 
                    foreach ($operations as $operat){
                        if($operat->operation_type == "withdrawal"){
                            $totalSortie += $operat->amount;
                        }else{
                            $totalEntree += $operat->amount;
                        }
                    }

                    $soldeCaisse = ($totalEntree + $caisseOuverte->montant_ouverture) - $totalSortie;

                    if($soldeCaisse < $data['amount']){
                        return response()->json(["code" => 0, "msg" => "Le solde de votre caisse ne vous permet pas d'effectuer cette opération", "data" => NULL]);
                    }
                }

                $operation = $data['id'] ? Operation::findOrFail($data['id']) : new Operation;

                if(empty($data['id']) && !isset($data['reference'])){
                    //formation numéro de référence
                    $maxIdOperation = DB::table('operations')->max('id');
                    $operation->reference = date("dmYHi").($maxIdOperation+1);
                }

                if(isset($data['reference'])){
                    $operation->reference = $data['reference'];
                }

                $operation->amount = $data['amount'];
                $operation->operation_type = $data['operation_type'];
                if(empty($data['id'])){
                    $operation->caisse_ouverte_id = $caisseOuverte->id;
                }
                $operation->date = Carbon::createFromFormat('d-m-Y H:i', $data['dateOperation']);
                $operation->partenair_id = isset($data['partenair_id']) ? $data['partenair_id'] : NULL; 
                $operation->receptionist = isset($data['receptionist']) ? $data['receptionist'] : NULL;
                $operation->id_card_receptionist = isset($data['id_card_receptionist']) ? $data['id_card_receptionist'] : NULL;
                $operation->bank_id = isset($data['bank_id']) ? $data['bank_id'] : NULL;
                $operation->city_id = isset($data['city_id']) ? $data['city_id'] : NULL;
                if(empty($data['id'])){
                    $operation->user_id = Auth::user()->id;
                }

                if($operation->state != "authorized" && $data['state'] == "authorized"){
                    $operation->authorized_by = Auth::user()->id;
                    $operation->authorization_date = now();
                }
                $operation->state = $data['state'];
                $operation->observation = isset($data['observation']) ? $data['observation'] : NULL;
                $operation->pattern = isset($data['pattern']) ? $data['pattern'] : NULL;
               
                //Décaissement - Encaissement
                if(isset($data['other_caisse_id'])){
                    //Verification si l'autre caisse est ouverte
                    $otherCaisseOuverte = CaisseOuverte::where([['id',$data['other_caisse_id']],['date_fermeture',NULL]])->first();
                    if(!$otherCaisseOuverte){
                        return response()->json(["code" => 0, "msg" => "La caisse que vous visez est fermée !", "data" => NULL]);
                    }

                    //Vérification du solde de la caisse s'il s'agit d'un retrait
                    if($data['operation_type'] == "deposit"){
                        $operationotherCaisses = Operation::where('caisse_ouverte_id',$otherCaisseOuverte->id)->get();
                        $totalSortieOc = 0; $totalEntreeOc = 0; 
                        foreach ($operationotherCaisses as $operat){
                            if($operat->operation_type == "withdrawal"){
                                $totalSortieOc += $operat->amount;
                            }else{
                                $totalEntreeOc += $operat->amount;
                            }
                        }

                        $soldeCaisseOc = ($totalEntreeOc + $otherCaisseOuverte->montant_ouverture) - $totalSortieOc;

                        if($soldeCaisseOc < $data['amount']){
                            return response()->json(["code" => 0, "msg" => "Le solde de l'autre caisse ne vous permet pas d'effectuer cette opération", "data" => NULL]);
                        }
                    }

                    $operation->other_caisse_id = $data['other_caisse_id'];
                    $operation->save();

                    //Nouvel ajout
                    if(empty($data['id'])){
                        //formation numéro de référence
                        $maxIdOthOperation = DB::table('operations')->max('id');

                        $operationOtherCaisse = New Operation();
                        $operationOtherCaisse->operation_id = $operation->id;
                        $operationOtherCaisse->reference = date("dmYHi").($maxIdOthOperation+1);
                        $operationOtherCaisse->operation_type = ($data['operation_type'] == "deposit") ? "withdrawal" : "deposit";
                        $operationOtherCaisse->amount = $data['amount'];
                        $operationOtherCaisse->date = Carbon::createFromFormat('d-m-Y H:i', $data['dateOperation']);
                        $operationOtherCaisse->caisse_ouverte_id = $otherCaisseOuverte->id;
                        $operationOtherCaisse->other_caisse_id = $caisseOuverte->id;
                        $operationOtherCaisse->receptionist = isset($data['receptionist']) ? $data['receptionist'] : NULL;
                        $operationOtherCaisse->id_card_receptionist = isset($data['id_card_receptionist']) ? $data['id_card_receptionist'] : NULL;
                        $operationOtherCaisse->user_id = $otherCaisseOuverte->user_id;
                        if($data['state'] != "authorized"){
                            $operationOtherCaisse->authorized_by = Auth::user()->id;
                            $operationOtherCaisse->authorization_date = now();
                        }
                        $operationOtherCaisse->state = $data['state'];
                        $operationOtherCaisse->observation = isset($data['observation']) ? $data['observation'] : NULL;
                        $operationOtherCaisse->pattern = isset($data['pattern']) ? $data['pattern'] : NULL;
                        $operationOtherCaisse->save();
                    }

                    //En cas de modification
                    if(!empty($data['id'])){
                        $operationOtherCaisse = Operation::where('operation_id',$data['id'])->first();
                        $operationOtherCaisse->operation_type = ($data['operation_type'] == "deposit") ? "withdrawal" : "deposit";
                        $operationOtherCaisse->amount = $data['amount'];
                        $operationOtherCaisse->date = Carbon::createFromFormat('d-m-Y H:i', $data['dateOperation']);
                        $operationOtherCaisse->caisse_ouverte_id = $otherCaisseOuverte->id;
                        $operationOtherCaisse->other_caisse_id = $caisseOuverte->id;
                        $operationOtherCaisse->receptionist = isset($data['receptionist']) ? $data['receptionist'] : NULL;
                        $operationOtherCaisse->id_card_receptionist = isset($data['id_card_receptionist']) ? $data['id_card_receptionist'] : NULL;
                        $operationOtherCaisse->user_id = $otherCaisseOuverte->user_id;
                        if($operation->state != "authorized" && $data['state'] == "authorized"){
                            $operationOtherCaisse->authorized_by = Auth::user()->id;
                            $operationOtherCaisse->authorization_date = now();
                        }
                        $operationOtherCaisse->state = $data['state'];
                        $operationOtherCaisse->observation = isset($data['observation']) ? $data['observation'] : NULL;
                        $operationOtherCaisse->pattern = isset($data['pattern']) ? $data['pattern'] : NULL;
                        $operationOtherCaisse->save();
                    }
                }

                //file uploading
                if(isset($data['file_to_upload'])){
                    $file_name = 'file_to_upload'.date('dmYHis').'.jpg';
                    $img = Image::make($data['file_to_upload']);
                    $img->save(storage_path('app/public/images/uploaded-files/'.$file_name),60);
                    $operation->file_to_upload = 'storage/images/uploaded-files/'.$file_name;
                }

                $operation->save();
                $jsonData["data"] = json_decode($operation);
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
        $operation = Operation::find($id);

        $jsonData = ["code" => 1, "msg" => "Opération effectuée avec succès."];
            if($operation){
                try {
                    
                    $operation->delete();
                    $jsonData["data"] = json_decode($operation);
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

    //Fonction pour recuperer les infos de configuration
    public function infosConfig()
    {
        $configuration = DB::table('configurations')->where('id', 1)->first();
        return $configuration;
    }

    public function recuOperation($idOperation){
        $Operation = Operation::find($idOperation);
        $pdf = \App::make('dompdf.wrapper');
        $pdf->getDomPDF()->set_option("enable_php", true);
        $pdf->loadHTML($this->recuContent($idOperation));
        return $pdf->stream($Operation->reference.'.pdf');
    }

    public function recuContent($id){
        $outPut = $this->header();
        $operation = Operation::with('partenair','user','bank')
                        ->join('caisse_ouvertes','caisse_ouvertes.id','=','operations.caisse_ouverte_id')
                        ->join('caisses','caisses.id','=','caisse_ouvertes.caisse_id')
                        ->leftjoin('cities','cities.id','=','caisses.city_id')
                        ->leftjoin('countries','countries.id','=','caisses.country_id')
                        ->select('operations.*','caisses.libelle_caisse','cities.libelle_city','countries.libelle_country',DB::raw('DATE_FORMAT(date, "%d-%m-%Y à %H:%i") as dateOperation'))
                        ->where('operations.id',$id)
                        ->first();
        $tittre = ""; $depot_retrait = "";
        if($operation->partenair_id != null){
            $tittre = " de partenaire";
        }  
        if($operation->other_caisse_id != null){
            $tittre = "Caisse";
        }     
        $operation->operation_type == "withdrawal" ? $depot_retrait ="Retrait " : $depot_retrait = "Dépôt";
        $outPut .= '<div class="container-table">
            <p style="text-align:center; font-size:20;"><u>'.$depot_retrait.' '.$tittre.'</u></p>';
        $outPut.='<table border="0" cellspacing="0" width="100%" style="line-height: 32px;">';
        if($operation->partenair_id != null){  
            $operation->user->role == "Superviseur" ? $zone = $operation->libelle_country : $zone = $operation->libelle_city; 
            $outPut.='<p style="font-size:14;">Le partenaire <b>'.$operation->partenair->name.'</b> a effectué un <b>'.$depot_retrait.'</b> avec pour référence <b>'.$operation->reference.'</b> d\'un montant de <b>'.number_format($operation->amount, 0, ',', ' ').'</b> F CFA à la date du '.$operation->dateOperation.' dans la caisse <b>'.$operation->libelle_caisse.'</b> de la zone <b>'.$zone.'</b> qui avait pour caissier à cette date <b>'.$operation->user->name.'.</b></p>
            <p>Observation : '.$operation->observation.'</p>';
        }
        
        $outPut.='</table>
        </div>';
        $outPut .= $this->footer();
        return $outPut;
    }

    //Reçu Header
    public function header()
    {
        $header = '<html>
                    <head>
                        <style>
                            @page{
                                margin: 100px 20px;
                                margin-bottom: 45px;
                                size: 20cm 18cm;
                            }
                            header{
                                position: absolute;
                                top: -80px;
                                left: 0px;
                                right: 0px;
                                height:40px;
                                margin:0px 20px;
                            }
                            .container-table{
                                margin:100px 0px;
                                width: 100%;
                            }
                            footer{
                                font-size:13px;
                                position: absolute; 
                                bottom: -5px; 
                                left: 0px; 
                                right: 0px;
                                height: 80px; 
                                text-align:center;
                            }
                        </style>
                    </head>
        <body>
        <header>
        <p>
            <img src='.$this->infosConfig()->logo.' width="100" height="80"/><br/>
            '.$this->infosConfig()->nom_compagnie.'<br/>
            '.$this->infosConfig()->adresse_compagnie.'<br/>
            '.$this->infosConfig()->contact_responsable.' / '.$this->infosConfig()->cellulaire.' <br/>
            '.$this->infosConfig()->email_compagnie.'
        </p>
        </header>';
        return $header;
    }

    //Reçu footer
    public function footer(){
        $footer ="<footer>
                <table border='0' cellspacing='0' width='100%' style='font-size:17px;'>
                    <tr>
                        <td width='50%' align='center'>
                            <u>Signature</u>
                        </td>
                        <td width='50%' align='center'>
                            <u>La caisse</u>
                        </td>
                    </tr>
                </table>
               </footer>
            </body>
        </html>";
        return $footer;
    }
}
