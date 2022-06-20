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
use App\Models\Parametre\Customer;

class SendMoneyController extends Controller
{
    public function index(){
        $countries = DB::table('countries')
                        ->select('libelle_country','id')
                        ->orderBy('libelle_country', 'ASC')
                        ->get();

        $menuPrincipal = "Opération";
        $titleControlleur = "envoie d'argent";
        $btnModalAjout = Auth::user()->role == "Agent" ? "TRUE" : "FALSE";

        return view('operation.send-money.index', compact('countries','menuPrincipal', 'titleControlleur', 'btnModalAjout'));
    }

    public function listSendMoney(){
        if(Auth::user()->role == "Administrateur" or Auth::user()->role == "Gerant"){
            $sendMoney = SendMoney::with('sender','recipient','sending_country','destination_country','created_by')
                            ->select('send_money.*',DB::raw('DATE_FORMAT(send_date, "%d-%m-%Y %H:%i") as sendDate'))
                            ->orderBy('send_money.id', 'DESC')
                            ->get();
        }
        if(Auth::user()->role == "Superviseur" or Auth::user()->role == "Comptable"){
            $sendMoney = SendMoney::with('sender','recipient','sending_country','destination_country','created_by')
                            ->select('send_money.*',DB::raw('DATE_FORMAT(send_date, "%d-%m-%Y %H:%i") as sendDate'))
                            ->orderBy('send_money.id', 'DESC')
                            ->where('sending_country_id',Auth::user()->country_id)
                            ->get();
        }
        if(Auth::user()->role == "Agent"){
            $sendMoney = SendMoney::with('sender','recipient','sending_country','destination_country')
                            ->join('caisse_ouvertes','caisse_ouvertes.user_id','=','send_money.created_by')
                            ->select('send_money.*',DB::raw('DATE_FORMAT(send_date, "%d-%m-%Y %H:%i") as sendDate'))
                            ->orderBy('send_money.id', 'DESC')
                            ->where([['created_by',Auth::user()->id],['caisse_ouvertes.date_fermeture',NULL]])
                            ->get();
        }

        $jsonData["rows"] = $sendMoney->toArray();
        $jsonData["total"] = $sendMoney->count();
        return response()->json($jsonData);
    }
    public function listSendMoneyByCode($code){
        if(Auth::user()->role == "Administrateur" or Auth::user()->role == "Gerant"){
            $sendMoney = SendMoney::with('sender','recipient','sending_country','destination_country','created_by')
                            ->select('send_money.*',DB::raw('DATE_FORMAT(send_date, "%d-%m-%Y %H:%i") as sendDate'))
                            ->where('secret_code',$code)
                            ->orderBy('send_money.id', 'DESC')
                            ->get();
        }
        if(Auth::user()->role == "Superviseur" or Auth::user()->role == "Comptable"){
            $sendMoney = SendMoney::with('sender','recipient','sending_country','destination_country','created_by')
                            ->select('send_money.*',DB::raw('DATE_FORMAT(send_date, "%d-%m-%Y %H:%i") as sendDate'))
                            ->orderBy('send_money.id', 'DESC')
                            ->where([['sending_country_id',Auth::user()->country_id],['secret_code',$code]])
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
                            ->select('send_money.*',DB::raw('DATE_FORMAT(send_date, "%d-%m-%Y %H:%i") as sendDate'))
                            ->where('sender_id',$sender)
                            ->orderBy('send_money.id', 'DESC')
                            ->get();
        }
        if(Auth::user()->role == "Superviseur" or Auth::user()->role == "Comptable"){
            $sendMoney = SendMoney::with('sender','recipient','sending_country','destination_country','created_by')
                            ->select('send_money.*',DB::raw('DATE_FORMAT(send_date, "%d-%m-%Y %H:%i") as sendDate'))
                            ->orderBy('send_money.id', 'DESC')
                            ->where([['sending_country_id',Auth::user()->country_id],['sender_id',$sender]])
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
    public function listSendMoneyByRecipient($recipient){
        if(Auth::user()->role == "Administrateur" or Auth::user()->role == "Gerant"){
            $sendMoney = SendMoney::with('sender','recipient','sending_country','destination_country','created_by')
                            ->select('send_money.*',DB::raw('DATE_FORMAT(send_date, "%d-%m-%Y %H:%i") as sendDate'))
                            ->where('recipient_id',$recipient)
                            ->orderBy('send_money.id', 'DESC')
                            ->get();
        }
        if(Auth::user()->role == "Superviseur" or Auth::user()->role == "Comptable"){
            $sendMoney = SendMoney::with('sender','recipient','sending_country','destination_country','created_by')
                            ->select('send_money.*',DB::raw('DATE_FORMAT(send_date, "%d-%m-%Y %H:%i") as sendDate'))
                            ->orderBy('send_money.id', 'DESC')
                            ->where([['sending_country_id',Auth::user()->country_id],['recipient_id',$recipient]])
                            ->get();
        }
        if(Auth::user()->role == "Agent"){
            $sendMoney = SendMoney::with('sender','recipient','sending_country','destination_country')
                            ->select('send_money.*',DB::raw('DATE_FORMAT(send_date, "%d-%m-%Y %H:%i") as sendDate'))
                            ->orderBy('send_money.id', 'DESC')
                            ->where([['created_by',Auth::user()->id],['recipient_id',$recipient]])
                            ->get();
        }

        $jsonData["rows"] = $sendMoney->toArray();
        $jsonData["total"] = $sendMoney->count();
        return response()->json($jsonData);
    }
    public function listSendMoneyByDestination($destination){
        if(Auth::user()->role == "Administrateur" or Auth::user()->role == "Gerant"){
            $sendMoney = SendMoney::with('sender','recipient','sending_country','destination_country','created_by')
                            ->select('send_money.*',DB::raw('DATE_FORMAT(send_date, "%d-%m-%Y %H:%i") as sendDate'))
                            ->where('destination_country_id',$destination)
                            ->orderBy('send_money.id', 'DESC')
                            ->get();
        }
        if(Auth::user()->role == "Superviseur" or Auth::user()->role == "Comptable"){
            $sendMoney = SendMoney::with('sender','recipient','sending_country','destination_country','created_by')
                            ->select('send_money.*',DB::raw('DATE_FORMAT(send_date, "%d-%m-%Y %H:%i") as sendDate'))
                            ->orderBy('send_money.id', 'DESC')
                            ->where([['sending_country_id',Auth::user()->country_id],['destination_country_id',$destination]])
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
                            ->select('send_money.*',DB::raw('DATE_FORMAT(send_date, "%d-%m-%Y %H:%i") as sendDate'))
                            ->where('state',$state)
                            ->orderBy('send_money.id', 'DESC')
                            ->get();
        }
        if(Auth::user()->role == "Superviseur" or Auth::user()->role == "Comptable"){
            $sendMoney = SendMoney::with('sender','recipient','sending_country','destination_country','created_by')
                            ->select('send_money.*',DB::raw('DATE_FORMAT(send_date, "%d-%m-%Y %H:%i") as sendDate'))
                            ->orderBy('send_money.id', 'DESC')
                            ->where([['sending_country_id',Auth::user()->country_id],['state',$state]])
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
    public function listSendMoneyByDate($sendDate){
        if(Auth::user()->role == "Administrateur" or Auth::user()->role == "Gerant"){
            $sendMoney = SendMoney::with('sender','recipient','sending_country','destination_country','created_by')
                            ->select('send_money.*',DB::raw('DATE_FORMAT(send_date, "%d-%m-%Y %H:%i") as sendDate'))
                            ->where('send_date',$sendDate)
                            ->orderBy('send_money.id', 'DESC')
                            ->get();
        }
        if(Auth::user()->role == "Superviseur" or Auth::user()->role == "Comptable"){
            $sendMoney = SendMoney::with('sender','recipient','sending_country','destination_country','created_by')
                            ->select('send_money.*',DB::raw('DATE_FORMAT(send_date, "%d-%m-%Y %H:%i") as sendDate'))
                            ->orderBy('send_money.id', 'DESC')
                            ->where([['sending_country_id',Auth::user()->country_id],['send_date',$sendDate]])
                            ->get();
        }
        if(Auth::user()->role == "Agent"){
            $sendMoney = SendMoney::with('sender','recipient','sending_country','destination_country')
                            ->select('send_money.*',DB::raw('DATE_FORMAT(send_date, "%d-%m-%Y %H:%i") as sendDate'))
                            ->orderBy('send_money.id', 'DESC')
                            ->where([['created_by',Auth::user()->id],['send_date',$sendDate]])
                            ->get();
        }

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

                    //enregistrement de l'expediteur et destinataire
                    $sender = new Customer;
                    $sender->name = $data['sender_name'];
                    $sender->surname = $data['sender_surname'];
                    $sender->contact = $data['sender_contact'];
                    $sender->country_id = Auth::user()->country_id;
                    $sender->save();

                    $recipient = new Customer;
                    $recipient->name = $data['recipient_name'];
                    $recipient->surname = $data['recipient_surname'];
                    $recipient->contact = $data['recipient_contact'];
                    $recipient->country_id = $data['destination_country_id'];
                    $recipient->save();
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
                $sendMoney->sender_id = $sender->id;
                $sendMoney->recipient_id = $recipient->id; 
                $sendMoney->sending_country_id = Auth::user()->country_id;
                $sendMoney->destination_country_id = $data['destination_country_id'];
                $sendMoney->amount = $data['amount'];
                $sendMoney->shipping_cost = $data['shipping_cost'];
                $sendMoney->discount_on_shipping_costs = isset($data['discount_on_shipping_costs']) ? $data['discount_on_shipping_costs'] : 0;
                $sendMoney->shipping_costs_included = isset($data['shipping_costs_included']) ? TRUE : FALSE;
                $sendMoney->created_by = Auth::user()->id;
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
                        $operation->amount = $data['amount'];
                        $operation->date = Carbon::createFromFormat('d-m-Y H:i', $data['sendDate']);
                        $operation->caisse_ouverte_id = $caisseOuverte->id;
                        $operation->user_id = $sendMoney->created_by;
                        $operation->state = 'sent';
                        $operation->save();
                    }

                    if(!empty($data['id'])){
                        $operation = Operation::where('send_money_id',$data['id'])->first();
                        $operation->amount = intval($data['amountTotal']);
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

}
