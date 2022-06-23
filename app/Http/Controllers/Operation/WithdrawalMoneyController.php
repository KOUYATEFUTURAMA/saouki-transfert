<?php

namespace App\Http\Controllers\Operation;

use Exception;
use Illuminate\Http\Request;
use App\Models\Parametre\City;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Models\Operation\Operation;
use App\Models\Operation\SendMoney;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Operation\CaisseOuverte;
use App\Models\Operation\WithdrawalMoney;

class WithdrawalMoneyController extends Controller
{
    public function index(){
        $moneySents = SendMoney::with('sender','recipient','sending_country','destination_country')
                    ->select('send_money.*',DB::raw('DATE_FORMAT(send_date, "%d-%m-%Y %H:%i") as sendDate'))
                    ->where([['destination_country_id',Auth::user()->country_id],['send_money.deleted_at',NULL],['send_money.state','=','sent'],['send_money.to_delete',0]])
                    ->get();

        $menuPrincipal = "Opération";
        $titleControlleur = "retrait d'argent";
        $btnModalAjout = Auth::user()->role == "Agent" ? "TRUE" : "FALSE";

        return view('operation.Withdrawal-money.index', compact('moneySents','menuPrincipal', 'titleControlleur', 'btnModalAjout'));
    }

    public function listWithdrawalMoney(){
        if(Auth::user()->role == "Administrateur" or Auth::user()->role == "Gerant"){
            $withdrawalMoney = WithdrawalMoney::with('created_by')
                            ->join('send_money','send_money.id','=','withdrawal_money.send_money_id')
                            ->join('customers as sender','sender.id','=','send_money.sender_id')
                            ->join('customers as recipient','recipient.id','=','send_money.recipient_id')
                            ->join('countries','countries.id','=','send_money.sending_country_id')
                            ->join('users','users.id','=','withdrawal_money.created_by')
                            ->join('agencies','agencies.id','=','users.agency_id')
                            ->select('withdrawal_money.*','sender.name as senderName','sender.surname as senderSurname','recipient.name as recipientName','recipient.surname as recipientSurname','countries.libelle_country','send_money.state','agencies.libelle_agency',DB::raw('DATE_FORMAT(withdrawal_date, "%d-%m-%Y %H:%i") as withdrawalDate'))
                            ->orderBy('withdrawal_money.id', 'DESC')
                            ->get();
        }
        if(Auth::user()->role == "Superviseur"){
            $withdrawalMoney = WithdrawalMoney::with('created_by')
                            ->join('send_money','send_money.id','=','withdrawal_money.send_money_id')
                            ->join('customers as sender','sender.id','=','send_money.sender_id')
                            ->join('customers as recipient','recipient.id','=','send_money.recipient_id')
                            ->join('countries','countries.id','=','send_money.sending_country_id')
                            ->join('users','users.id','=','withdrawal_money.created_by')
                            ->join('agencies','agencies.id','=','users.agency_id')
                            ->select('withdrawal_money.*','sender.name as senderName','sender.surname as senderSurname','recipient.name as recipientName','recipient.surname as recipientSurname','countries.libelle_country','send_money.state','agencies.libelle_agency',DB::raw('DATE_FORMAT(withdrawal_date, "%d-%m-%Y %H:%i") as withdrawalDate'))
                            ->where('send_money.destination_country_id',Auth::user()->country_id)
                            ->orderBy('withdrawal_money.id', 'DESC')
                            ->get();
        }
        if(Auth::user()->role == "Comptable"){
            $withdrawalMoney = WithdrawalMoney::with('created_by')
                            ->join('send_money','send_money.id','=','withdrawal_money.send_money_id')
                            ->join('customers as sender','sender.id','=','send_money.sender_id')
                            ->join('customers as recipient','recipient.id','=','send_money.recipient_id')
                            ->join('countries','countries.id','=','send_money.sending_country_id')
                            ->join('users','users.id','=','withdrawal_money.created_by')
                            ->join('agencies','agencies.id','=','users.agency_id')
                            ->select('withdrawal_money.*','sender.name as senderName','sender.surname as senderSurname','recipient.name as recipientName','recipient.surname as recipientSurname','countries.libelle_country','send_money.state','agencies.libelle_agency',DB::raw('DATE_FORMAT(withdrawal_date, "%d-%m-%Y %H:%i") as withdrawalDate'))
                            ->where('agencies.city_id',Auth::user()->city_id)
                            ->orderBy('withdrawal_money.id', 'DESC')
                            ->get();
        }
        if(Auth::user()->role == "Agent"){
            $withdrawalMoney = WithdrawalMoney::with('created_by')
                            ->join('send_money','send_money.id','=','withdrawal_money.send_money_id')
                            ->join('customers as sender','sender.id','=','send_money.sender_id')
                            ->join('customers as recipient','recipient.id','=','send_money.recipient_id')
                            ->join('countries','countries.id','=','send_money.sending_country_id')
                            ->join('caisse_ouvertes','caisse_ouvertes.user_id','=','withdrawal_money.created_by')
                            ->select('withdrawal_money.*','sender.name as senderName','sender.surname as senderSurname','recipient.name as recipientName','recipient.surname as recipientSurname','countries.libelle_country','send_money.state',DB::raw('DATE_FORMAT(withdrawal_date, "%d-%m-%Y %H:%i") as withdrawalDate'))
                            ->where([['withdrawal_money.created_by',Auth::user()->id],['caisse_ouvertes.date_fermeture',NULL]])
                            ->orderBy('withdrawal_money.id', 'DESC')
                            ->get();
        }

        $jsonData["rows"] = $withdrawalMoney->toArray();
        $jsonData["total"] = $withdrawalMoney->count();
        return response()->json($jsonData);
    }
    public function listWithdrawalMoneyByCountry($country){
        if(Auth::user()->role == "Administrateur" or Auth::user()->role == "Gerant"){
            $withdrawalMoney = WithdrawalMoney::with('created_by')
                            ->join('send_money','send_money.id','=','withdrawal_money.send_money_id')
                            ->join('customers as sender','sender.id','=','send_money.sender_id')
                            ->join('customers as recipient','recipient.id','=','send_money.recipient_id')
                            ->join('countries','countries.id','=','send_money.sending_country_id')
                            ->join('users','users.id','=','withdrawal_money.created_by')
                            ->join('agencies','agencies.id','=','users.agency_id')
                            ->select('withdrawal_money.*','sender.name as senderName','sender.surname as senderSurname','recipient.name as recipientName','recipient.surname as recipientSurname','countries.libelle_country','send_money.state','agencies.libelle_agency',DB::raw('DATE_FORMAT(withdrawal_date, "%d-%m-%Y %H:%i") as withdrawalDate'))
                            ->where('send_money.sending_country_id',$country)
                            ->orderBy('withdrawal_money.id', 'DESC')
                            ->get();
        }
        if(Auth::user()->role == "Superviseur"){
            $withdrawalMoney = WithdrawalMoney::with('created_by')
                            ->join('send_money','send_money.id','=','withdrawal_money.send_money_id')
                            ->join('customers as sender','sender.id','=','send_money.sender_id')
                            ->join('customers as recipient','recipient.id','=','send_money.recipient_id')
                            ->join('countries','countries.id','=','send_money.sending_country_id')
                            ->join('users','users.id','=','withdrawal_money.created_by')
                            ->join('agencies','agencies.id','=','users.agency_id')
                            ->select('withdrawal_money.*','sender.name as senderName','sender.surname as senderSurname','recipient.name as recipientName','recipient.surname as recipientSurname','countries.libelle_country','send_money.state','agencies.libelle_agency',DB::raw('DATE_FORMAT(withdrawal_date, "%d-%m-%Y %H:%i") as withdrawalDate'))
                            ->where([['send_money.destination_country_id',Auth::user()->country_id],['send_money.destination_country_id',$country]])
                            ->orderBy('withdrawal_money.id', 'DESC')
                            ->get();
        }
        if(Auth::user()->role == "Comptable"){
            $withdrawalMoney = WithdrawalMoney::with('created_by')
                            ->join('send_money','send_money.id','=','withdrawal_money.send_money_id')
                            ->join('customers as sender','sender.id','=','send_money.sender_id')
                            ->join('customers as recipient','recipient.id','=','send_money.recipient_id')
                            ->join('countries','countries.id','=','send_money.sending_country_id')
                            ->join('users','users.id','=','withdrawal_money.created_by')
                            ->join('agencies','agencies.id','=','users.agency_id')
                            ->select('withdrawal_money.*','sender.name as senderName','sender.surname as senderSurname','recipient.name as recipientName','recipient.surname as recipientSurname','countries.libelle_country','send_money.state','agencies.libelle_agency',DB::raw('DATE_FORMAT(withdrawal_date, "%d-%m-%Y %H:%i") as withdrawalDate'))
                            ->where([['agencies.city_id',Auth::user()->city_id],['send_money.destination_country_id',$country]])
                            ->orderBy('withdrawal_money.id', 'DESC')
                            ->get();
        }
        if(Auth::user()->role == "Agent"){
            $withdrawalMoney = WithdrawalMoney::with('created_by')
                            ->join('send_money','send_money.id','=','withdrawal_money.send_money_id')
                            ->join('customers as sender','sender.id','=','send_money.sender_id')
                            ->join('customers as recipient','recipient.id','=','send_money.recipient_id')
                            ->join('countries','countries.id','=','send_money.sending_country_id')
                            ->join('caisse_ouvertes','caisse_ouvertes.user_id','=','withdrawal_money.created_by')
                            ->select('withdrawal_money.*','sender.name as senderName','sender.surname as senderSurname','recipient.name as recipientName','recipient.surname as recipientSurname','countries.libelle_country','send_money.state',DB::raw('DATE_FORMAT(withdrawal_date, "%d-%m-%Y %H:%i") as withdrawalDate'))
                            ->where([['withdrawal_money.created_by',Auth::user()->id],['caisse_ouvertes.date_fermeture',NULL],['send_money.destination_country_id',$country]])
                            ->orderBy('withdrawal_money.id', 'DESC')
                            ->get();
        }

        $jsonData["rows"] = $withdrawalMoney->toArray();
        $jsonData["total"] = $withdrawalMoney->count();
        return response()->json($jsonData);
    }
    public function listWithdrawalMoneyByPeriode($start,$end){
        if(Auth::user()->role == "Administrateur" or Auth::user()->role == "Gerant"){
            $withdrawalMoney = WithdrawalMoney::with('created_by')
                            ->join('send_money','send_money.id','=','withdrawal_money.send_money_id')
                            ->join('customers as sender','sender.id','=','send_money.sender_id')
                            ->join('customers as recipient','recipient.id','=','send_money.recipient_id')
                            ->join('countries','countries.id','=','send_money.sending_country_id')
                            ->join('users','users.id','=','withdrawal_money.created_by')
                            ->join('agencies','agencies.id','=','users.agency_id')
                            ->select('withdrawal_money.*','sender.name as senderName','sender.surname as senderSurname','recipient.name as recipientName','recipient.surname as recipientSurname','countries.libelle_country','send_money.state','agencies.libelle_agency',DB::raw('DATE_FORMAT(withdrawal_date, "%d-%m-%Y %H:%i") as withdrawalDate'))
                            ->whereBetween('withdrawal_date',[$start,$end])
                            ->orderBy('withdrawal_money.id', 'DESC')
                            ->get();
        }
        if(Auth::user()->role == "Superviseur"){
            $withdrawalMoney = WithdrawalMoney::with('created_by')
                            ->join('send_money','send_money.id','=','withdrawal_money.send_money_id')
                            ->join('customers as sender','sender.id','=','send_money.sender_id')
                            ->join('customers as recipient','recipient.id','=','send_money.recipient_id')
                            ->join('countries','countries.id','=','send_money.sending_country_id')
                            ->join('users','users.id','=','withdrawal_money.created_by')
                            ->join('agencies','agencies.id','=','users.agency_id')
                            ->select('withdrawal_money.*','sender.name as senderName','sender.surname as senderSurname','recipient.name as recipientName','recipient.surname as recipientSurname','countries.libelle_country','send_money.state','agencies.libelle_agency',DB::raw('DATE_FORMAT(withdrawal_date, "%d-%m-%Y %H:%i") as withdrawalDate'))
                            ->whereBetween('withdrawal_date',[$start,$end])
                            ->where('send_money.destination_country_id',Auth::user()->country_id)
                            ->orderBy('withdrawal_money.id', 'DESC')
                            ->get();
        }
        if(Auth::user()->role == "Comptable"){
            $withdrawalMoney = WithdrawalMoney::with('created_by')
                            ->join('send_money','send_money.id','=','withdrawal_money.send_money_id')
                            ->join('customers as sender','sender.id','=','send_money.sender_id')
                            ->join('customers as recipient','recipient.id','=','send_money.recipient_id')
                            ->join('countries','countries.id','=','send_money.sending_country_id')
                            ->join('users','users.id','=','withdrawal_money.created_by')
                            ->join('agencies','agencies.id','=','users.agency_id')
                            ->select('withdrawal_money.*','sender.name as senderName','sender.surname as senderSurname','recipient.name as recipientName','recipient.surname as recipientSurname','countries.libelle_country','send_money.state','agencies.libelle_agency',DB::raw('DATE_FORMAT(withdrawal_date, "%d-%m-%Y %H:%i") as withdrawalDate'))
                            ->whereBetween('withdrawal_date',[$start,$end])
                            ->where('agencies.city_id',Auth::user()->city_id)
                            ->orderBy('withdrawal_money.id', 'DESC')
                            ->get();
        }
        if(Auth::user()->role == "Agent"){
            $withdrawalMoney = WithdrawalMoney::with('created_by')
                            ->join('send_money','send_money.id','=','withdrawal_money.send_money_id')
                            ->join('customers as sender','sender.id','=','send_money.sender_id')
                            ->join('customers as recipient','recipient.id','=','send_money.recipient_id')
                            ->join('countries','countries.id','=','send_money.sending_country_id')
                            ->join('caisse_ouvertes','caisse_ouvertes.user_id','=','withdrawal_money.created_by')
                            ->select('withdrawal_money.*','sender.name as senderName','sender.surname as senderSurname','recipient.name as recipientName','recipient.surname as recipientSurname','countries.libelle_country','send_money.state',DB::raw('DATE_FORMAT(withdrawal_date, "%d-%m-%Y %H:%i") as withdrawalDate'))
                            ->whereBetween('withdrawal_date',[$start,$end])
                            ->where([['withdrawal_money.created_by',Auth::user()->id],['caisse_ouvertes.date_fermeture',NULL]])
                            ->orderBy('withdrawal_money.id', 'DESC')
                            ->get();
        }

        $jsonData["rows"] = $withdrawalMoney->toArray();
        $jsonData["total"] = $withdrawalMoney->count();
        return response()->json($jsonData);
    }
    public function listWithdrawalMoneyByRecipient($recipient){
        if(Auth::user()->role == "Administrateur" or Auth::user()->role == "Gerant"){
            $withdrawalMoney = WithdrawalMoney::with('created_by')
                            ->join('send_money','send_money.id','=','withdrawal_money.send_money_id')
                            ->join('customers as sender','sender.id','=','send_money.sender_id')
                            ->join('customers as recipient','recipient.id','=','send_money.recipient_id')
                            ->join('countries','countries.id','=','send_money.sending_country_id')
                            ->join('users','users.id','=','withdrawal_money.created_by')
                            ->join('agencies','agencies.id','=','users.agency_id')
                            ->select('withdrawal_money.*','sender.name as senderName','sender.surname as senderSurname','recipient.name as recipientName','recipient.surname as recipientSurname','countries.libelle_country','send_money.state','agencies.libelle_agency',DB::raw('DATE_FORMAT(withdrawal_date, "%d-%m-%Y %H:%i") as withdrawalDate'))
                            ->where('withdrawal_money.id_recipient',$recipient)
                            ->orderBy('withdrawal_money.id', 'DESC')
                            ->get();
        }
        if(Auth::user()->role == "Superviseur"){
            $withdrawalMoney = WithdrawalMoney::with('created_by')
                            ->join('send_money','send_money.id','=','withdrawal_money.send_money_id')
                            ->join('customers as sender','sender.id','=','send_money.sender_id')
                            ->join('customers as recipient','recipient.id','=','send_money.recipient_id')
                            ->join('countries','countries.id','=','send_money.sending_country_id')
                            ->join('users','users.id','=','withdrawal_money.created_by')
                            ->join('agencies','agencies.id','=','users.agency_id')
                            ->select('withdrawal_money.*','sender.name as senderName','sender.surname as senderSurname','recipient.name as recipientName','recipient.surname as recipientSurname','countries.libelle_country','send_money.state','agencies.libelle_agency',DB::raw('DATE_FORMAT(withdrawal_date, "%d-%m-%Y %H:%i") as withdrawalDate'))
                            ->where([['send_money.destination_country_id',Auth::user()->country_id],['withdrawal_money.id_recipient',$recipient]])
                            ->orderBy('withdrawal_money.id', 'DESC')
                            ->get();
        }
        if(Auth::user()->role == "Comptable"){
            $withdrawalMoney = WithdrawalMoney::with('created_by')
                            ->join('send_money','send_money.id','=','withdrawal_money.send_money_id')
                            ->join('customers as sender','sender.id','=','send_money.sender_id')
                            ->join('customers as recipient','recipient.id','=','send_money.recipient_id')
                            ->join('countries','countries.id','=','send_money.sending_country_id')
                            ->join('users','users.id','=','withdrawal_money.created_by')
                            ->join('agencies','agencies.id','=','users.agency_id')
                            ->select('withdrawal_money.*','sender.name as senderName','sender.surname as senderSurname','recipient.name as recipientName','recipient.surname as recipientSurname','countries.libelle_country','send_money.state','agencies.libelle_agency',DB::raw('DATE_FORMAT(withdrawal_date, "%d-%m-%Y %H:%i") as withdrawalDate'))
                            ->where([['agencies.city_id',Auth::user()->city_id],['withdrawal_money.id_recipient',$recipient]])
                            ->orderBy('withdrawal_money.id', 'DESC')
                            ->get();
        }

        $jsonData["rows"] = $withdrawalMoney->toArray();
        $jsonData["total"] = $withdrawalMoney->count();
        return response()->json($jsonData);
    }
    public function listWithdrawalMoneyByUser($user){
        if(Auth::user()->role == "Administrateur" or Auth::user()->role == "Gerant"){
            $withdrawalMoney = WithdrawalMoney::with('created_by')
                            ->join('send_money','send_money.id','=','withdrawal_money.send_money_id')
                            ->join('customers as sender','sender.id','=','send_money.sender_id')
                            ->join('customers as recipient','recipient.id','=','send_money.recipient_id')
                            ->join('countries','countries.id','=','send_money.sending_country_id')
                            ->join('users','users.id','=','withdrawal_money.created_by')
                            ->join('agencies','agencies.id','=','users.agency_id')
                            ->select('withdrawal_money.*','sender.name as senderName','sender.surname as senderSurname','recipient.name as recipientName','recipient.surname as recipientSurname','countries.libelle_country','send_money.state','agencies.libelle_agency',DB::raw('DATE_FORMAT(withdrawal_date, "%d-%m-%Y %H:%i") as withdrawalDate'))
                            ->where('withdrawal_money.created_by',$user)
                            ->orderBy('withdrawal_money.id', 'DESC')
                            ->get();
        }
        if(Auth::user()->role == "Superviseur"){
            $withdrawalMoney = WithdrawalMoney::with('created_by')
                            ->join('send_money','send_money.id','=','withdrawal_money.send_money_id')
                            ->join('customers as sender','sender.id','=','send_money.sender_id')
                            ->join('customers as recipient','recipient.id','=','send_money.recipient_id')
                            ->join('countries','countries.id','=','send_money.sending_country_id')
                            ->join('users','users.id','=','withdrawal_money.created_by')
                            ->join('agencies','agencies.id','=','users.agency_id')
                            ->select('withdrawal_money.*','sender.name as senderName','sender.surname as senderSurname','recipient.name as recipientName','recipient.surname as recipientSurname','countries.libelle_country','send_money.state','agencies.libelle_agency',DB::raw('DATE_FORMAT(withdrawal_date, "%d-%m-%Y %H:%i") as withdrawalDate'))
                            ->where([['send_money.destination_country_id',Auth::user()->country_id],['withdrawal_money.created_by',$user]])
                            ->orderBy('withdrawal_money.id', 'DESC')
                            ->get();
        }
        if(Auth::user()->role == "Comptable"){
            $withdrawalMoney = WithdrawalMoney::with('created_by')
                            ->join('send_money','send_money.id','=','withdrawal_money.send_money_id')
                            ->join('customers as sender','sender.id','=','send_money.sender_id')
                            ->join('customers as recipient','recipient.id','=','send_money.recipient_id')
                            ->join('countries','countries.id','=','send_money.sending_country_id')
                            ->join('users','users.id','=','withdrawal_money.created_by')
                            ->join('agencies','agencies.id','=','users.agency_id')
                            ->select('withdrawal_money.*','sender.name as senderName','sender.surname as senderSurname','recipient.name as recipientName','recipient.surname as recipientSurname','countries.libelle_country','send_money.state','agencies.libelle_agency',DB::raw('DATE_FORMAT(withdrawal_date, "%d-%m-%Y %H:%i") as withdrawalDate'))
                            ->where([['agencies.city_id',Auth::user()->city_id],['withdrawal_money.created_by',$user]])
                            ->orderBy('withdrawal_money.id', 'DESC')
                            ->get();
        }

        $jsonData["rows"] = $withdrawalMoney->toArray();
        $jsonData["total"] = $withdrawalMoney->count();
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
        if ($request->isMethod('post') && $request->input('send_money_id')) {

                $data = $request->all();
               
            try {

                //Vérification de l'ouverture de la caisse 
                $caisseOuverte = CaisseOuverte::where([['user_id',Auth::user()->id],['date_fermeture',NULL]])->first();
                if(!$caisseOuverte){
                    return response()->json(["code" => 0, "msg" => "Votre caisse est fermée", "data" => NULL]);
                }

                //Vérification de fonds necessaire disponible dans la caisse 
                if(empty($data['id'])){
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

                    $sendMoney = SendMoney::find($data['send_money_id']);
                    if(!$sendMoney){
                        return response()->json(["code" => 0, "msg" => "Code secret introuvable !", "data" => NULL]);
                    }
                }

                $withdrawalMoney = $data['id'] ? WithdrawalMoney::findOrFail($data['id']) : new WithdrawalMoney;

                $withdrawalMoney->withdrawal_date = Carbon::createFromFormat('d-m-Y H:i', $data['withdrawalDate']);
                $withdrawalMoney->send_money_id = $data['send_money_id'];
                $withdrawalMoney->amount = $data['amount'];
                $withdrawalMoney->id_recipient = $data['id_recipient'];
                $withdrawalMoney->id_card_recipient = isset($data['id_card_recipient']) ? $data['id_card_recipient'] : NULL;
                if(empty($data['id'])){
                    $withdrawalMoney->created_by = Auth::user()->id;
                }
                $withdrawalMoney->save();

                if($withdrawalMoney){
                    $sendMoney = SendMoney::find($data['send_money_id']);
                    $sendMoney->state = 'withdrawn';
                    $sendMoney->save();

                    if(empty($data['id'])){
                        //formation numéro de référence
                        $maxIdOperation = DB::table('operations')->max('id');
                        $reference = date("dmYHi").($maxIdOperation+1);

                        $operation = New Operation();
                        $operation->withdrawal_money_id = $withdrawalMoney->id;
                        $operation->reference = $reference;
                        $operation->operation_type = "withdrawal";
                        $operation->amount = $withdrawalMoney->amount;
                        $operation->date = Carbon::createFromFormat('d-m-Y H:i', $data['withdrawalDate']);
                        $operation->caisse_ouverte_id = $caisseOuverte->id;
                        $operation->user_id = $withdrawalMoney->created_by;
                        $operation->state = 'withdrawn';
                        $operation->save();
                    }
                    if(!empty($data['id'])){
                        $operation = Operation::where('withdrawal_money_id',$data['id'])->first();
                        $operation->date = Carbon::createFromFormat('d-m-Y H:i', $data['withdrawalDate']);
                        $operation->save();
                    }
                }

                $jsonData["data"] = json_decode($withdrawalMoney);
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

    //Fonction pour recuperer les infos de configuration
    public function infosConfig()
    {
        $configuration = DB::table('configurations')->where('id', 1)->first();
        return $configuration;
    }

    public function recuWithdrawalMoney($idWithdrawalMoney){
        $WithdrawalMoney = SendMoney::find($idWithdrawalMoney);
        $pdf = \App::make('dompdf.wrapper');
        $pdf->getDomPDF()->set_option("enable_php", true);
        $pdf->loadHTML($this->recuContent($idWithdrawalMoney));
        return $pdf->stream('recu-payement.pdf');
    }

    public function recuContent($id){
        $outPut = $this->header();
        $withdrawalMoney = WithdrawalMoney::with('created_by')
                            ->join('send_money','send_money.id','=','withdrawal_money.send_money_id')
                            ->join('customers as sender','sender.id','=','send_money.sender_id')
                            ->join('customers as recipient','recipient.id','=','send_money.recipient_id')
                            ->join('countries','countries.id','=','send_money.sending_country_id')
                            ->join('caisse_ouvertes','caisse_ouvertes.user_id','=','withdrawal_money.created_by')
                            ->select('withdrawal_money.*','sender.name as senderName','sender.surname as senderSurname','sender.contact as senderContact','recipient.name as recipientName','recipient.surname as recipientSurname','recipient.contact as recipientContact','countries.libelle_country','send_money.state',DB::raw('DATE_FORMAT(withdrawal_date, "%d-%m-%Y à %H:%i") as withdrawalDate'))
                            ->where('withdrawal_money.id',$id)
                            ->first();

        $outPut .= '<div class="container-table">
                <p style="text-align:center; font-size:20;"><u>Reçu de retrait</u></p>
                <table border="2" cellspacing="0" width="100%" style="line-height: 32px;">
                    <tr>
                        <td>&nbsp;Date de retrait : <b>'.$withdrawalMoney->withdrawalDate.'</b></td>
                        <td>&nbsp;Montant : <b>'.number_format($withdrawalMoney->amount, 0, ',', ' ').' F CFA</b></td>
                    </tr>
                    <tr>
                        <td align="center">Informations expéditeur</td>
                        <td align="center">Informations destinatire</td>
                    </tr>
                    <tr>
                        <td align="center">&nbsp; Nom : <b>'.$withdrawalMoney->senderName.'</b></td>
                        <td align="center">&nbsp; Nom : <b>'.$withdrawalMoney->recipientName.'</b></td>
                    </tr>
                    <tr>
                        <td align="center">&nbsp; Prénom(s) : <b>'.$withdrawalMoney->senderSurname.'</b></td>
                        <td align="center">&nbsp; Prénom(s) : <b>'.$withdrawalMoney->recipientSurname.'</b></td>
                    </tr>
                    <tr>
                        <td align="center">&nbsp; Contact : <b>'.$withdrawalMoney->senderContact.'</b></td>
                        <td align="center">&nbsp; Contact : <b>'.$withdrawalMoney->recipientContact.'</b></td>
                    </tr>
                    <tr>
                        <td align="center">&nbsp; Pays : <b>'.$withdrawalMoney->libelle_country.'</b></td>
                        <td align="center">&nbsp; N° pièce : <b>'.$withdrawalMoney->id_card_recipient.'</b></td>
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
