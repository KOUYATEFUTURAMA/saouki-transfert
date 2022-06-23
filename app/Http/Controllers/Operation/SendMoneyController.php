<?php

namespace App\Http\Controllers\Operation;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Models\Operation\Operation;
use App\Models\Operation\SendMoney;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Operation\CaisseOuverte;
use App\Models\Parametre\City;
use App\Models\Parametre\Customer;

class SendMoneyController extends Controller
{
    public function index(){
        $countries = DB::table('countries')
                        ->select('libelle_country','id')
                        ->orderBy('libelle_country', 'ASC')
                        ->get();

        $customers = DB::table('customers')
                        ->select('name','surname','id')
                        ->orderBy('name', 'ASC')
                        ->get();

        $menuPrincipal = "Opération";
        $titleControlleur = "envoie d'argent";
        $btnModalAjout = Auth::user()->role == "Agent" ? "TRUE" : "FALSE";

        return view('operation.send-money.index', compact('customers','countries','menuPrincipal', 'titleControlleur', 'btnModalAjout'));
    }

    public function listSendMoney(){
        if(Auth::user()->role == "Administrateur" or Auth::user()->role == "Gerant"){
            $sendMoney = SendMoney::with('sender','recipient','sending_country','destination_country','created_by')
                            ->join('users','users.id','=','send_money.created_by')
                            ->join('agencies','agencies.id','=','users.agency_id')
                            ->select('send_money.*','agencies.libelle_agency',DB::raw('DATE_FORMAT(send_date, "%d-%m-%Y %H:%i") as sendDate'))
                            ->orderBy('send_money.id', 'DESC')
                            ->where('send_money.deleted_at',NULL)
                            ->get();
        }
        if(Auth::user()->role == "Superviseur"){
            $sendMoney = SendMoney::with('sender','recipient','sending_country','destination_country','created_by')
                            ->join('users','users.id','=','send_money.created_by')
                            ->join('agencies','agencies.id','=','users.agency_id')
                            ->select('send_money.*','agencies.libelle_agency',DB::raw('DATE_FORMAT(send_date, "%d-%m-%Y %H:%i") as sendDate'))
                            ->orderBy('send_money.id', 'DESC')
                            ->where([['sending_country_id',Auth::user()->country_id],['send_money.deleted_at',NULL]])
                            ->get();
        }
        if(Auth::user()->role == "Comptable"){
            $sendMoney = SendMoney::with('sender','recipient','sending_country','destination_country','created_by')
                            ->join('users','users.id','=','send_money.created_by')
                            ->join('agencies','agencies.id','=','users.agency_id')
                            ->select('send_money.*','agencies.libelle_agency',DB::raw('DATE_FORMAT(send_date, "%d-%m-%Y %H:%i") as sendDate'))
                            ->orderBy('send_money.id', 'DESC')
                            ->where([['agencies.city_id',Auth::user()->city_id],['send_money.deleted_at',NULL]])
                            ->get();
        }
        if(Auth::user()->role == "Agent"){
            $sendMoney = SendMoney::with('sender','recipient','sending_country','destination_country','authorized_by')
                            ->join('caisse_ouvertes','caisse_ouvertes.user_id','=','send_money.created_by')
                            ->select('send_money.*',DB::raw('DATE_FORMAT(send_date, "%d-%m-%Y %H:%i") as sendDate'),DB::raw('DATE_FORMAT(authorization_date, "%d-%m-%Y %H:%i") as authorizationDate'))
                            ->orderBy('send_money.id', 'DESC')
                            ->where([['created_by',Auth::user()->id],['caisse_ouvertes.date_fermeture',NULL],['send_money.deleted_at',NULL]])
                            ->get();
        }

        $jsonData["rows"] = $sendMoney->toArray();
        $jsonData["total"] = $sendMoney->count();
        return response()->json($jsonData);
    }
    public function listSendMoneyByCode($code){
        if(Auth::user()->role == "Administrateur" or Auth::user()->role == "Gerant"){
            $sendMoney = SendMoney::with('sender','recipient','sending_country','destination_country','created_by')
                            ->join('users','users.id','=','send_money.created_by')
                            ->join('agencies','agencies.id','=','users.agency_id')
                            ->select('send_money.*','agencies.libelle_agency',DB::raw('DATE_FORMAT(send_date, "%d-%m-%Y %H:%i") as sendDate'))
                            ->where('secret_code',$code)
                            ->orderBy('send_money.id', 'DESC')
                            ->get();
        }
        if(Auth::user()->role == "Superviseur"){
            $sendMoney = SendMoney::with('sender','recipient','sending_country','destination_country','created_by')
                             ->join('users','users.id','=','send_money.created_by')
                            ->join('agencies','agencies.id','=','users.agency_id')
                            ->select('send_money.*','agencies.libelle_agency',DB::raw('DATE_FORMAT(send_date, "%d-%m-%Y %H:%i") as sendDate'))
                            ->orderBy('send_money.id', 'DESC')
                            ->where([['sending_country_id',Auth::user()->country_id],['secret_code',$code]])
                            ->get();
        }
        if(Auth::user()->role == "Comptable"){
            $sendMoney = SendMoney::with('sender','recipient','sending_country','destination_country','created_by')
                            ->join('users','users.id','=','send_money.created_by')
                            ->join('agencies','agencies.id','=','users.agency_id')
                            ->select('send_money.*','agencies.libelle_agency',DB::raw('DATE_FORMAT(send_date, "%d-%m-%Y %H:%i") as sendDate'))
                            ->orderBy('send_money.id', 'DESC')
                            ->where([['agencies.city_id',Auth::user()->city_id],['secret_code',$code]])
                            ->get();
        }
        if(Auth::user()->role == "Agent"){
            $sendMoney = SendMoney::with('sender','recipient','sending_country','destination_country')
                            ->select('send_money.*',DB::raw('DATE_FORMAT(send_date, "%d-%m-%Y %H:%i") as sendDate'))
                            ->orderBy('send_money.id', 'DESC')
                            ->where([['created_by',Auth::user()->id],['secret_code',$code]])
                            ->get();
        }

        $jsonData["rows"] = $sendMoney->toArray();
        $jsonData["total"] = $sendMoney->count();
        return response()->json($jsonData);
    }
    public function listSendMoneyBySender($sender){
        if(Auth::user()->role == "Administrateur" or Auth::user()->role == "Gerant"){
            $sendMoney = SendMoney::with('sender','recipient','sending_country','destination_country','created_by')
                            ->join('users','users.id','=','send_money.created_by')
                            ->join('agencies','agencies.id','=','users.agency_id')
                            ->select('send_money.*','agencies.libelle_agency',DB::raw('DATE_FORMAT(send_date, "%d-%m-%Y %H:%i") as sendDate'))
                            ->where('sender_id',$sender)
                            ->orderBy('send_money.id', 'DESC')
                            ->get();
        }
        if(Auth::user()->role == "Superviseur"){
            $sendMoney = SendMoney::with('sender','recipient','sending_country','destination_country','created_by')
                            ->join('users','users.id','=','send_money.created_by')
                            ->join('agencies','agencies.id','=','users.agency_id')
                            ->select('send_money.*','agencies.libelle_agency',DB::raw('DATE_FORMAT(send_date, "%d-%m-%Y %H:%i") as sendDate'))
                            ->orderBy('send_money.id', 'DESC')
                            ->where([['sending_country_id',Auth::user()->country_id],['sender_id',$sender]])
                            ->get();
        }
        if(Auth::user()->role == "Comptable"){
            $sendMoney = SendMoney::with('sender','recipient','sending_country','destination_country','created_by')
                            ->join('users','users.id','=','send_money.created_by')
                            ->join('agencies','agencies.id','=','users.agency_id')
                            ->select('send_money.*','agencies.libelle_agency',DB::raw('DATE_FORMAT(send_date, "%d-%m-%Y %H:%i") as sendDate'))
                            ->orderBy('send_money.id', 'DESC')
                            ->where([['agencies.city_id',Auth::user()->city_id],['sender_id',$sender]])
                            ->get();
        }
        if(Auth::user()->role == "Agent"){
            $sendMoney = SendMoney::with('sender','recipient','sending_country','destination_country')
                            ->select('send_money.*',DB::raw('DATE_FORMAT(send_date, "%d-%m-%Y %H:%i") as sendDate'))
                            ->orderBy('send_money.id', 'DESC')
                            ->where([['created_by',Auth::user()->id],['sender_id',$sender]])
                            ->get();
        }

        $jsonData["rows"] = $sendMoney->toArray();
        $jsonData["total"] = $sendMoney->count();
        return response()->json($jsonData);
    }
    public function listSendMoneyByDestination($destination){
        if(Auth::user()->role == "Administrateur" or Auth::user()->role == "Gerant"){
            $sendMoney = SendMoney::with('sender','recipient','sending_country','destination_country','created_by')
                            ->join('users','users.id','=','send_money.created_by')
                            ->join('agencies','agencies.id','=','users.agency_id')
                            ->select('send_money.*','agencies.libelle_agency',DB::raw('DATE_FORMAT(send_date, "%d-%m-%Y %H:%i") as sendDate'))
                            ->where('destination_country_id',$destination)
                            ->orderBy('send_money.id', 'DESC')
                            ->get();
        }
        if(Auth::user()->role == "Superviseur"){
            $sendMoney = SendMoney::with('sender','recipient','sending_country','destination_country','created_by')
                            ->join('users','users.id','=','send_money.created_by')
                            ->join('agencies','agencies.id','=','users.agency_id')
                            ->select('send_money.*','agencies.libelle_agency',DB::raw('DATE_FORMAT(send_date, "%d-%m-%Y %H:%i") as sendDate'))
                            ->orderBy('send_money.id', 'DESC')
                            ->where([['sending_country_id',Auth::user()->country_id],['destination_country_id',$destination]])
                            ->get();
        }
        if(Auth::user()->role == "Comptable"){
            $sendMoney = SendMoney::with('sender','recipient','sending_country','destination_country','created_by')
                            ->join('users','users.id','=','send_money.created_by')
                            ->join('agencies','agencies.id','=','users.agency_id')
                            ->select('send_money.*','agencies.libelle_agency',DB::raw('DATE_FORMAT(send_date, "%d-%m-%Y %H:%i") as sendDate'))
                            ->orderBy('send_money.id', 'DESC')
                            ->where([['agencies.city_id',Auth::user()->city_id],['destination_country_id',$destination]])
                            ->get();
        }
        if(Auth::user()->role == "Agent"){
            $sendMoney = SendMoney::with('sender','recipient','sending_country','destination_country')
                            ->select('send_money.*',DB::raw('DATE_FORMAT(send_date, "%d-%m-%Y %H:%i") as sendDate'))
                            ->orderBy('send_money.id', 'DESC')
                            ->where([['created_by',Auth::user()->id],['destination_country_id',$destination]])
                            ->get();
        }

        $jsonData["rows"] = $sendMoney->toArray();
        $jsonData["total"] = $sendMoney->count();
        return response()->json($jsonData);
    }
    public function listSendMoneyByState($state){
        if(Auth::user()->role == "Administrateur" or Auth::user()->role == "Gerant"){
            $sendMoney = SendMoney::with('sender','recipient','sending_country','destination_country','created_by')
                            ->join('users','users.id','=','send_money.created_by')
                            ->join('agencies','agencies.id','=','users.agency_id')
                            ->select('send_money.*','agencies.libelle_agency',DB::raw('DATE_FORMAT(send_date, "%d-%m-%Y %H:%i") as sendDate'))
                            ->where('state',$state)
                            ->orderBy('send_money.id', 'DESC')
                            ->get();
        }
        if(Auth::user()->role == "Superviseur"){
            $sendMoney = SendMoney::with('sender','recipient','sending_country','destination_country','created_by')
                            ->join('users','users.id','=','send_money.created_by')
                            ->join('agencies','agencies.id','=','users.agency_id')
                            ->select('send_money.*','agencies.libelle_agency',DB::raw('DATE_FORMAT(send_date, "%d-%m-%Y %H:%i") as sendDate'))
                            ->orderBy('send_money.id', 'DESC')
                            ->where([['sending_country_id',Auth::user()->country_id],['state',$state]])
                            ->get();
        }
        if(Auth::user()->role == "Comptable"){
            $sendMoney = SendMoney::with('sender','recipient','sending_country','destination_country','created_by')
                            ->join('users','users.id','=','send_money.created_by')
                            ->join('agencies','agencies.id','=','users.agency_id')
                            ->select('send_money.*','agencies.libelle_agency',DB::raw('DATE_FORMAT(send_date, "%d-%m-%Y %H:%i") as sendDate'))
                            ->orderBy('send_money.id', 'DESC')
                            ->where([['agencies.city_id',Auth::user()->city_id],['state',$state]])
                            ->get();
        }
        if(Auth::user()->role == "Agent"){
            $sendMoney = SendMoney::with('sender','recipient','sending_country','destination_country')
                            ->select('send_money.*',DB::raw('DATE_FORMAT(send_date, "%d-%m-%Y %H:%i") as sendDate'))
                            ->orderBy('send_money.id', 'DESC')
                            ->where([['created_by',Auth::user()->id],['state',$state]])
                            ->get();
        }

        $jsonData["rows"] = $sendMoney->toArray();
        $jsonData["total"] = $sendMoney->count();
        return response()->json($jsonData);
    }
    public function listSendMoneyByDate($start,$end){
        if(Auth::user()->role == "Administrateur" or Auth::user()->role == "Gerant"){
            $sendMoney = SendMoney::with('sender','recipient','sending_country','destination_country','created_by')
                            ->join('users','users.id','=','send_money.created_by')
                            ->join('agencies','agencies.id','=','users.agency_id')
                            ->select('send_money.*','agencies.libelle_agency',DB::raw('DATE_FORMAT(send_date, "%d-%m-%Y %H:%i") as sendDate'))
                            ->whereBetween('send_date',[$start,$end])
                            ->orderBy('send_money.id', 'DESC')
                            ->get();
        }
        if(Auth::user()->role == "Superviseur"){
            $sendMoney = SendMoney::with('sender','recipient','sending_country','destination_country','created_by')
                            ->join('users','users.id','=','send_money.created_by')
                            ->join('agencies','agencies.id','=','users.agency_id')
                            ->select('send_money.*','agencies.libelle_agency',DB::raw('DATE_FORMAT(send_date, "%d-%m-%Y %H:%i") as sendDate'))
                            ->orderBy('send_money.id', 'DESC')
                            ->whereBetween('send_date',[$start,$end])
                            ->where([['sending_country_id',Auth::user()->country_id]])
                            ->get();
        }
        if(Auth::user()->role == "Comptable"){
            $sendMoney = SendMoney::with('sender','recipient','sending_country','destination_country','created_by')
                            ->join('users','users.id','=','send_money.created_by')
                            ->join('agencies','agencies.id','=','users.agency_id')
                            ->select('send_money.*','agencies.libelle_agency',DB::raw('DATE_FORMAT(send_date, "%d-%m-%Y %H:%i") as sendDate'))
                            ->orderBy('send_money.id', 'DESC')
                            ->whereBetween('send_date',[$start,$end])
                            ->where([['agencies.city_id',Auth::user()->city_id]])
                            ->get();
        }
        if(Auth::user()->role == "Agent"){
            $sendMoney = SendMoney::with('sender','recipient','sending_country','destination_country')
                            ->select('send_money.*',DB::raw('DATE_FORMAT(send_date, "%d-%m-%Y %H:%i") as sendDate'))
                            ->orderBy('send_money.id', 'DESC')
                            ->whereBetween('send_date',[$start,$end])
                            ->where('created_by',Auth::user()->id)
                            ->get();
        }

        $jsonData["rows"] = $sendMoney->toArray();
        $jsonData["total"] = $sendMoney->count();
        return response()->json($jsonData);
    }

    public function findSendMoney($id){
        $sendMoney = SendMoney::with('sender','recipient','sending_country','destination_country')
                    ->select('send_money.*')
                    ->where('send_money.id',$id)
                    ->get();

        $jsonData["rows"] = $sendMoney->toArray();
        $jsonData["total"] = $sendMoney->count();
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
        if ($request->isMethod('post') && $request->input('destination_country_id')) {

                $data = $request->all();

            try {

                //Vérification de l'ouverture de la caisse 
                $caisseOuverte = CaisseOuverte::where([['user_id',Auth::user()->id],['date_fermeture',NULL]])->first();
                if(!$caisseOuverte){
                    return response()->json(["code" => 0, "msg" => "Votre caisse est fermée", "data" => NULL]);
                }

                $sendMoney = $data['id'] ? SendMoney::findOrFail($data['id']) : new SendMoney;

                if(empty($data['id'])){
                    //formation numéro du code sécret
                    $maxId = DB::table('send_money')->max('id');
                    $sendMoney->secret_code = date("dmy-His-").sprintf("%03d", ($maxId+1));

                    //enregistrement de l'expediteur s'il n'est pas dans la base
                    if(empty($data['sender_id'])){
                        $sender = new Customer;
                        $sender->name = $data['sender_name'];
                        $sender->surname = $data['sender_surname'];
                        $sender->contact = $data['sender_contact'];
                        $sender->country_id = Auth::user()->country_id;
                        $sender->save();
                    }
                    
                    //enregistrement du destinataire s'il n'est pas dans la base
                    if(empty($data['recipient_id'])){
                        $recipient = new Customer;
                        $recipient->name = $data['recipient_name'];
                        $recipient->surname = $data['recipient_surname'];
                        $recipient->contact = $data['recipient_contact'];
                        $recipient->country_id = $data['destination_country_id'];
                        $recipient->save();
                    }
                }

                if(!empty($data['id'])){
                    $sender = Customer::find($data['sender_id']);
                    $sender->name = $data['sender_name'];
                    $sender->surname = $data['sender_surname'];
                    $sender->contact = $data['sender_contact'];
                    $sender->country_id = Auth::user()->country_id;
                    $sender->save();

                    $recipient = Customer::find($data['recipient_id']);
                    $recipient->name = $data['recipient_name'];
                    $recipient->surname = $data['recipient_surname'];
                    $recipient->contact = $data['recipient_contact'];
                    $recipient->country_id = $data['destination_country_id'];
                    $recipient->save();
                }

                $sendMoney->send_date = Carbon::createFromFormat('d-m-Y H:i', $data['sendDate']);
                $sendMoney->state = 'sent';
                $sendMoney->sender_id = empty($data['sender_id']) ? $sender->id : $data['sender_id'];
                $sendMoney->recipient_id = empty($data['recipient_id']) ? $recipient->id : $data['recipient_id']; 
                $sendMoney->sending_country_id = Auth::user()->country_id;
                $sendMoney->destination_country_id = $data['destination_country_id'];
                $sendMoney->amount = $data['amount'];
                $sendMoney->shipping_cost = $data['shipping_cost'];
                $sendMoney->discount_on_shipping_costs = isset($data['discount_on_shipping_costs']) ? $data['discount_on_shipping_costs'] : 0;
                $sendMoney->shipping_costs_included = isset($data['shipping_costs_included']) ? TRUE : FALSE;
                if(empty($data['id'])){
                    $sendMoney->created_by = Auth::user()->id;
                }
                if(!empty($data['id'])){
                    $sendMoney->updated_by = Auth::user()->id;
                }
                $sendMoney->save();

                if($sendMoney){
                    if(empty($data['id'])){
                        $operation = New Operation();
                        $operation->send_money_id = $sendMoney->id;
                        $operation->reference = $sendMoney->secret_code;
                        $operation->operation_type = "deposit";
                        $operation->amount = $data['amountTotal'];
                        $operation->date = Carbon::createFromFormat('d-m-Y H:i', $data['sendDate']);
                        $operation->caisse_ouverte_id = $caisseOuverte->id;
                        $operation->user_id = $sendMoney->created_by;
                        $operation->state = 'sent';
                        $operation->save();
                    }

                    if(!empty($data['id'])){
                        $operation = Operation::where('send_money_id',$data['id'])->first();
                        $operation->amount = $data['amountTotal'];
                        $operation->date = Carbon::createFromFormat('d-m-Y H:i', $data['sendDate']);
                        $operation->save();
                    }
                }

                $jsonData["data"] = json_decode($sendMoney);
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
        $sendMoney = SendMoney::find($id);

        $jsonData = ["code" => 1, "msg" => "Opération effectuée avec succès."];
            if($sendMoney){
                try {   
                        if(Auth::user()->role != "Agent"){
                            $sendMoney->to_delete = TRUE;
                            $sendMoney->authorized_by = Auth::user()->id;
                            $sendMoney->authorization_date = Now();
                            $sendMoney->save();
                            $jsonData["data"] = json_decode($sendMoney);
                            return response()->json($jsonData);
                        }

                        $operation = Operation::where('send_money_id',$id)->first();
                        if(!$operation){
                            return response()->json(["code" => 0, "msg" => "Echec de suppression", "data" => NULL]);
                        }
                        $operation->delete();
                    
                    $sendMoney->update(['deleted_by' => Auth::user()->id]);
                    $sendMoney->delete();
                    $jsonData["data"] = json_decode($sendMoney);
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

    public function recuMoneySend($idSendMoney){
        $SendMoney = SendMoney::find($idSendMoney);
        $pdf = \App::make('dompdf.wrapper');
        $pdf->getDomPDF()->set_option("enable_php", true);
        $pdf->loadHTML($this->recuContent($idSendMoney));
        return $pdf->stream($SendMoney->secret_code.'.pdf');
    }

    public function recuContent($id){
        $outPut = $this->header();
        $sendMoney = SendMoney::with('sender','recipient','sending_country','destination_country')
                            ->select('send_money.*',DB::raw('DATE_FORMAT(send_date, "%d-%m-%Y à %H:%i") as sendDate'))
                            ->where('send_money.id',$id)
                            ->first();

        $outPut .= '<div class="container-table">
                <p style="text-align:center; font-size:20;"><u>Reçu d\'envoie</u></p>
                <table border="2" cellspacing="0" width="100%" style="line-height: 32px;">
                    <tr>
                            <td>&nbsp;Date d\'envoie : <b>'.$sendMoney->sendDate.'</b></td>
                            <td>&nbsp;Code secret : <b>'.$sendMoney->secret_code.'</b></td>
                            <td width="35%">&nbsp;Montant : <b>'.number_format($sendMoney->amount, 0, ',', ' ').' F CFA</b></td>
                    </tr>
                </table>
                <br/>
                <table border="2" cellspacing="0" width="100%" style="line-height:32px;">
                    <tr>
                        <td colspan="2" align="center">Détails</td>
                    <tr>
                    <tr>
                        <td align="center">Informations expéditeur</td>
                        <td align="center">Informations destinatire</td>
                    </tr>
                    <tr>
                        <td align="center">&nbsp; Nom : <b>'.$sendMoney->sender->name.'</b></td>
                        <td align="center">&nbsp; Nom : <b>'.$sendMoney->recipient->name.'</b></td>
                    </tr>
                    <tr>
                        <td align="center">&nbsp; Prénom(s) : <b>'.$sendMoney->sender->surname.'</b></td>
                        <td align="center">&nbsp; Prénom(s) : <b>'.$sendMoney->recipient->surname.'</b></td>
                    </tr>
                    <tr>
                        <td align="center">&nbsp; Contact : <b>'.$sendMoney->sender->contact.'</b></td>
                        <td align="center">&nbsp; Contact : <b>'.$sendMoney->recipient->contact.'</b></td>
                    </tr>
                    <tr>
                        <td align="center">&nbsp; Pays : <b>'.$sendMoney->sending_country->libelle_country.'</b></td>
                        <td align="center">&nbsp; Pays : <b>'.$sendMoney->destination_country->libelle_country.'</b></td>
                    </tr>
                </table>
                <br/><br/>
                <table border="0" cellspacing="0" width="100%" style="font-size:17px;">
                    <tr>
                        <td width="50%" align="center">
                            <u>Signature du client</u>
                        </td>
                        <td width="50%" align="center">
                            <u>La caisse</u>
                        </td>
                    </tr>
                </table>
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
                                size: 20cm 20cm;
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
                                font-size : 15px;
                            }
                            footer{
                                font-size:13px;
                                position: absolute; 
                                bottom: -5px; 
                                left: 0px; 
                                right: 0px;
                                height: 50px; 
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
        $city = City::find(Auth::user()->city_id);
        $footer ="<footer>
                    <p>
                        Editer à ".$city->libelle_city." le ".date('d-m-Y à H:i:s')." par ".Auth::user()->name."
                    </p>
                </footer>
            </body>
        </html>";
        return $footer;
    }
}
