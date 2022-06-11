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
        $operations = Operation::with('partenair','user','authorized_by')
                            ->select('operations.*',DB::raw('DATE_FORMAT(date, "%d-%m-%Y %H:%i") as dateOperation'),DB::raw('DATE_FORMAT(authorization_date, "%d-%m-%Y %H:%i") as authorizationDate'))
                            ->orderBy('id', 'DESC')
                            ->where('partenair_id','!=',NULL)
                            ->get();

        $jsonData["rows"] = $operations->toArray();
        $jsonData["total"] = $operations->count();
        return response()->json($jsonData);
    }
    public function listOperationsByPartenairs($partenair){
        $operations = Operation::with('partenair','user','authorized_by')
                            ->select('operations.*',DB::raw('DATE_FORMAT(date, "%d-%m-%Y %H:%i") as dateOperation'),DB::raw('DATE_FORMAT(authorization_date, "%d-%m-%Y %H:%i") as authorizationDate'))
                            ->orderBy('id', 'DESC')
                            ->where('partenair_id',$partenair)
                            ->get();

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
        $operations = Operation::with('bank','user','authorized_by')
                            ->select('operations.*',DB::raw('DATE_FORMAT(date, "%d-%m-%Y %H:%i") as dateOperation'),DB::raw('DATE_FORMAT(authorization_date, "%d-%m-%Y %H:%i") as authorizationDate'))
                            ->orderBy('id', 'DESC')
                            ->where('bank_id','!=',NULL)
                            ->get();

        $jsonData["rows"] = $operations->toArray();
        $jsonData["total"] = $operations->count();
        return response()->json($jsonData);
    }
    public function listOperationsByBanks($bank){
        $operations = Operation::with('bank','user','authorized_by')
                            ->select('operations.*',DB::raw('DATE_FORMAT(date, "%d-%m-%Y %H:%i") as dateOperation'),DB::raw('DATE_FORMAT(authorization_date, "%d-%m-%Y %H:%i") as authorizationDate'))
                            ->orderBy('id', 'DESC')
                            ->where('bank_id',$bank)
                            ->get();

        $jsonData["rows"] = $operations->toArray();
        $jsonData["total"] = $operations->count();
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

                //Vérification de caisse 
                $caisseOuverte = CaisseOuverte::where([['user_id',Auth::user()->id],['date_fermeture',null]])->first();
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
                    $operation->reference = date("dmYHis").$maxIdOperation;
                }

                if(isset($data['reference'])){
                    $operation->reference = $data['reference'];
                }

                $operation->operation_type = $data['operation_type'];
                $operation->amount = $data['amount'];
                $operation->caisse_ouverte_id = $caisseOuverte->id;
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
        $operation = Operation::with('partenair','user','bank','caisse_ouverte','other_caisse')
                        ->join('caisse_ouvertes','caisse_ouvertes.id','=','operations.caisse_ouverte_id')
                        ->join('caisses','caisses.id','=','caisse_ouvertes.caisse_id')
                        ->leftjoin('cities','cities.id','=','caisses.city_id')
                        ->leftjoin('countries','countries.id','=','caisses.country_id')
                        ->select('operations.*','caisses.libelle_caisse','cities.libelle_city','countries.libelle_country',DB::raw('DATE_FORMAT(date, "%d-%m-%Y à %H:%i") as dateOperation'))
                        ->where('operations.id',$id)
                        ->first();
        $tittre = ""; $depot_retrait = "";
        if($operation->partenair_id != null){
            $tittre = "Partenaire";
        }  
        if($operation->bank_id != null){
            $tittre = "Banque";
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
