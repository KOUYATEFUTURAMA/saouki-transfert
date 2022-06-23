<?php

namespace App\Http\Controllers\Etat;

use Illuminate\Http\Request;
use App\Models\Parametre\Country;
use App\Models\Parametre\Customer;
use Illuminate\Support\Facades\DB;
use App\Models\Operation\SendMoney;
use App\Http\Controllers\Controller;
use App\Models\Operation\WithdrawalMoney;

class EtatController extends Controller
{
    public function customer(){
        
        $countries = DB::table('countries')
                        ->select('libelle_country','id')
                        ->orderBy('libelle_country', 'ASC')
                        ->get();

        $menuPrincipal = "Etat";
        $titleControlleur = "liste des clients";
        $btnModalAjout = "FALSE";

        return view('etat.customer', compact('countries','menuPrincipal', 'titleControlleur', 'btnModalAjout'));
    }
    public function bank(){

        $countries = DB::table('countries')
                        ->select('libelle_country','id')
                        ->orderBy('libelle_country', 'ASC')
                        ->get();
                        
        $menuPrincipal = "Etat";
        $titleControlleur = "liste des banques";
        $btnModalAjout = "FALSE";

        return view('etat.bank', compact('countries', 'menuPrincipal', 'titleControlleur', 'btnModalAjout'));
    }
    public function partenair(){

        $countries = DB::table('countries')
                        ->select('libelle_country','id')
                        ->orderBy('libelle_country', 'ASC')
                        ->get();

        $menuPrincipal = "Etat";
        $titleControlleur = "liste des partenairs";
        $btnModalAjout = "FALSE";

        return view('etat.partenair', compact('countries', 'menuPrincipal', 'titleControlleur', 'btnModalAjout'));
    }
    public function superviseur(){
        $countries = DB::table('countries')
                        ->select('libelle_country','id')
                        ->orderBy('libelle_country', 'ASC')
                        ->get();

        $menuPrincipal = "Etat";
        $titleControlleur = "liste des superviseurs";
        $btnModalAjout = "FALSE";

        return view('etat.superviseur', compact('countries','menuPrincipal', 'titleControlleur', 'btnModalAjout'));
    }
    public function comptable(){

        $countries = DB::table('countries')
                        ->select('libelle_country','id')
                        ->orderBy('libelle_country', 'ASC')
                        ->get();

        $menuPrincipal = "Etat";
        $titleControlleur = "liste des comptables";
        $btnModalAjout = "FALSE";

        return view('etat.comptable', compact('countries', 'menuPrincipal', 'titleControlleur', 'btnModalAjout'));
    }
    public function caissier(){

        $countries = DB::table('countries')
                        ->select('libelle_country','id')
                        ->orderBy('libelle_country', 'ASC')
                        ->get();

        $menuPrincipal = "Etat";
        $titleControlleur = "liste des caissiers";
        $btnModalAjout = "FALSE";

        return view('etat.caissier', compact('countries','menuPrincipal', 'titleControlleur', 'btnModalAjout'));
    }
    public function agency(){

        $countries = DB::table('countries')
                        ->select('libelle_country','id')
                        ->orderBy('libelle_country', 'ASC')
                        ->get();

        $menuPrincipal = "Etat";
        $titleControlleur = "liste des agences";
        $btnModalAjout = "FALSE";

        return view('etat.agencies', compact('countries','menuPrincipal', 'titleControlleur', 'btnModalAjout'));
    }
    public function operationPartenair(){

        $partenairs = DB::table('partenairs')
                        ->select('name','id')
                        ->orderBy('name', 'ASC')
                        ->get();

        $menuPrincipal = "Etat";
        $titleControlleur = "opération des partenaires";
        $btnModalAjout = "FALSE";

        return view('etat.operation-partenair', compact('partenairs','menuPrincipal', 'titleControlleur', 'btnModalAjout'));
    }
    public function operationBank(){

        $banks = DB::table('banks')
                        ->select('libelle_bank','id')
                        ->orderBy('libelle_bank', 'ASC')
                        ->get();

        $menuPrincipal = "Etat";
        $titleControlleur = "opération bancaires";
        $btnModalAjout = "FALSE";

        return view('etat.operation-bank', compact('banks','menuPrincipal', 'titleControlleur', 'btnModalAjout'));
    }
    public function sendMoney(){

        $menuPrincipal = "Etat";
        $titleControlleur = "envoi d'argent";
        $btnModalAjout = "FALSE";

        return view('etat.send-money', compact('menuPrincipal', 'titleControlleur', 'btnModalAjout'));
    }
    public function withdrawalMoney(){

        $menuPrincipal = "Etat";
        $titleControlleur = "retrait d'argent";
        $btnModalAjout = "FALSE";

        return view('etat.withdrawal-money', compact('menuPrincipal', 'titleControlleur', 'btnModalAjout'));
    }
    public function operation(){

        $menuPrincipal = "Etat";
        $titleControlleur = "opération";
        $btnModalAjout = "FALSE";

        return view('etat.operation', compact('menuPrincipal', 'titleControlleur', 'btnModalAjout'));
    }
    public function encaissDecaiss(){

        $menuPrincipal = "Etat";
        $titleControlleur = "encaissement et décaissement";
        $btnModalAjout = "FALSE";

        return view('etat.encaiss-decaiss', compact('menuPrincipal', 'titleControlleur', 'btnModalAjout'));
    }
    public function caisseClosed(){

        $menuPrincipal = "Etat";
        $titleControlleur = "caisses cloturées";
        $btnModalAjout = "FALSE";

        return view('etat.caisse-closed', compact('menuPrincipal', 'titleControlleur', 'btnModalAjout'));
    }

    //Fonction pour recuperer les infos de configuration
    public function infosConfig()
    {
        $configuration = DB::table('configurations')->where('id', 1)->first();
        return $configuration;
    }

    //Customer
    public function listOperationCustomerPdf(){
        $pdf = \App::make('dompdf.wrapper');
        $pdf->getDomPDF()->set_option("enable_php", true);
        $pdf->loadHTML($this->listOperationCustomer());
        return $pdf->stream('operation-clients.pdf');
    }
    public function listOperationCustomer(){
        $outPut = $this->header();
        $customers = Customer::with('country')
                        ->leftjoin('send_money','send_money.sender_id','=','customers.id')
                        ->leftjoin('withdrawal_money','withdrawal_money.id_recipient','=','customers.id')
                        ->select('customers.*',DB::raw('sum(send_money.amount) as allDeposit'),DB::raw('sum(withdrawal_money.amount) as allWithdrawal'))
                        ->groupBy('customers.contact')
                        ->orderBy('allDeposit', 'DESC')
                        ->get();

        $outPut .= '<div class="container-table">
            <h3 align="center"><u>Liste des envois et retraits des clients</h3>
            <table border="2" cellspacing="0" width="100%">
                <tr style="background-color:#dcdcdc;">
                    <th cellspacing="0" border="2" width="20%" align="center">NOM</th>
                    <th cellspacing="0" border="2" width="25%" align="center">PRENOM</th>
                    <th cellspacing="0" border="2" width="15%" align="center">CONTACT</th>
                    <th cellspacing="0" border="2" width="15%" align="center">PAYS</th>
                    <th cellspacing="0" border="2" width="25%" align="center">ENVOI TOTAL</th>
                    <th cellspacing="0" border="2" width="25%" align="center">RETRAIT TOTAL</th>
            </tr>';

        $totalEnvoi = 0;
        $totalRetrait = 0;

        foreach ($customers as $customer) {
            $totalEnvoi += $customer->allDeposit;
            $totalRetrait += $customer->allWithdrawal;

            $outPut .= '<tr>
                            <td cellspacing="0" border="2" align="left">&nbsp;&nbsp;'.$customer->name.'</td>
                            <td cellspacing="0" border="2" align="left">&nbsp;&nbsp;'.$customer->surname.'</td>
                            <td  cellspacing="0" border="2" align="left">&nbsp;&nbsp;'.$customer->contact.'</td>
                            <td  cellspacing="0" border="2" align="left">&nbsp;&nbsp;'.$customer->country->libelle_country.'</td>
                            <td  cellspacing="0" border="2" align="right">'.number_format($customer->allDeposit, 0, ',', ' ').'&nbsp;&nbsp;</td>
                            <td  cellspacing="0" border="2" align="right">'.number_format($customer->allWithdrawal, 0, ',', ' ').'&nbsp;&nbsp;</td>
                        </tr>';
        }

        $outPut.='</table>';
        $outPut.='<p>Envoi total : <b> '.number_format($totalEnvoi, 0, ',', ' ').' F CFA</b></p><p>Retrait total : <b> '.number_format($totalRetrait, 0, ',', ' ').' F CFA</b></p>
        </div>';
        return $outPut;
    }
    public function listOperationCustomerByCountryPdf($country){
        $Country = Country::find($country);
        $pdf = \App::make('dompdf.wrapper');
        $pdf->getDomPDF()->set_option("enable_php", true);
        $pdf->loadHTML($this->listOperationCustomerByCountry($country));
        return $pdf->stream('operation-clients-de-'.$Country->libelle_country.'.pdf');
    }
    public function listOperationCustomerByCountry($country){
        $Country = Country::find($country);
        $outPut = $this->header();
        $customers = Customer::leftjoin('send_money','send_money.sender_id','=','customers.id')
                        ->leftjoin('withdrawal_money','withdrawal_money.id_recipient','=','customers.id')
                        ->select('customers.*',DB::raw('sum(send_money.amount) as allDeposit'),DB::raw('sum(withdrawal_money.amount) as allWithdrawal'))
                        ->where('customers.country_id',$country)
                        ->groupBy('customers.contact')
                        ->orderBy('allDeposit', 'DESC')
                        ->get();

        $outPut .= '<div class="container-table">
            <h3 align="center"><u>Liste des envois et retraits des clients de '.$Country->libelle_country.'</h3>
            <table border="2" cellspacing="0" width="100%">
                <tr style="background-color:#dcdcdc;">
                    <th cellspacing="0" border="2" width="20%" align="center">NOM</th>
                    <th cellspacing="0" border="2" width="25%" align="center">PRENOM</th>
                    <th cellspacing="0" border="2" width="15%" align="center">CONTACT</th>
                    <th cellspacing="0" border="2" width="25%" align="center">ENVOI TOTAL</th>
                    <th cellspacing="0" border="2" width="25%" align="center">RETRAIT TOTAL</th>
            </tr>';

        $totalEnvoi = 0;
        $totalRetrait = 0;

        foreach ($customers as $customer) {
            $totalEnvoi += $customer->allDeposit;
            $totalRetrait += $customer->allWithdrawal;

            $outPut .= '<tr>
                            <td cellspacing="0" border="2" align="left">&nbsp;&nbsp;'.$customer->name.'</td>
                            <td cellspacing="0" border="2" align="left">&nbsp;&nbsp;'.$customer->surname.'</td>
                            <td  cellspacing="0" border="2" align="left">&nbsp;&nbsp;'.$customer->contact.'</td>
                            <td  cellspacing="0" border="2" align="right">'.number_format($customer->allDeposit, 0, ',', ' ').'&nbsp;&nbsp;</td>
                            <td  cellspacing="0" border="2" align="right">'.number_format($customer->allWithdrawal, 0, ',', ' ').'&nbsp;&nbsp;</td>
                        </tr>';
        }

        $outPut.='</table>';
        $outPut.='<p>Envoi total : <b> '.number_format($totalEnvoi, 0, ',', ' ').' F CFA</b></p><p>Retrait total : <b> '.number_format($totalRetrait, 0, ',', ' ').' F CFA</b></p>
        </div>';
        return $outPut;
    }
    public function listSendMoneyByCustomerPdf($customer){
        $Customer = Customer::find($customer);
        $pdf = \App::make('dompdf.wrapper');
        $pdf->getDomPDF()->set_option("enable_php", true);
        $pdf->loadHTML($this->listSendMoneyByCustomer($customer));
        return $pdf->stream('envois-clients-'.$Customer->name.'.pdf');
    }
    public function listSendMoneyByCustomer($customer){
        $outPut = $this->header();
        $Customer = Customer::find($customer);
        $sendMoney = SendMoney::with('sender','recipient','sending_country','destination_country','created_by')
                    ->join('users','users.id','=','send_money.created_by')
                    ->join('agencies','agencies.id','=','users.agency_id')
                    ->select('send_money.*','agencies.libelle_agency',DB::raw('DATE_FORMAT(send_date, "%d-%m-%Y à %H:%i") as sendDate'))
                    ->where('sender_id',$customer)
                    ->orderBy('send_money.id', 'DESC')
                    ->get();

        $outPut .= '<div class="container-table">
            <h3 align="center"><u>Liste des envois du client '.$Customer->name.' '.$Customer->surname.'</h3>
            <table border="2" cellspacing="0" width="100%">
                <tr style="background-color:#dcdcdc;">
                    <th cellspacing="0" border="2" width="20%" align="center">DATE</th>
                    <th cellspacing="0" border="2" width="25%" align="center">MONTANT</th>
                    <th cellspacing="0" border="2" width="35%" align="center">DESTINATAIRE</th>
                    <th cellspacing="0" border="2" width="20%" align="center">PAYS</th>
            </tr>';

        $totalEnvoi = 0;
        foreach ($sendMoney as $send) {
            $totalEnvoi += $send->amount;

            $outPut .= '<tr>
                            <td cellspacing="0" border="2" align="left">&nbsp;&nbsp;'.$send->sendDate.'</td>
                            <td  cellspacing="0" border="2" align="right">'.number_format($send->amount, 0, ',', ' ').'&nbsp;&nbsp;</td>
                            <td cellspacing="0" border="2" align="left">&nbsp;&nbsp;'.$send->recipient->name.' '.$send->recipient->surname.'</td>
                            <td  cellspacing="0" border="2" align="left">&nbsp;&nbsp;'.$send->destination_country->libelle_country.'</td>
                        </tr>';
        }

        $outPut.='</table>';
        $outPut.='<p>Envoi total : <b> '.number_format($totalEnvoi, 0, ',', ' ').' F CFA</b></p></div>';
        return $outPut;
    }
    public function listWithdrawalMoneyByCustomerPdf($customer){
        $Customer = Customer::find($customer);
        $pdf = \App::make('dompdf.wrapper');
        $pdf->getDomPDF()->set_option("enable_php", true);
        $pdf->loadHTML($this->listWithdrawalMoneyByCustomer($customer));
        return $pdf->stream('retraits-clients-'.$Customer->name.'.pdf');
    }
    public function listWithdrawalMoneyByCustomer($customer){
        $outPut = $this->header();
        $Customer = Customer::find($customer);
        $withdrawalMoney = WithdrawalMoney::with('created_by')
                            ->join('send_money','send_money.id','=','withdrawal_money.send_money_id')
                            ->join('customers as sender','sender.id','=','send_money.sender_id')
                            ->join('customers as recipient','recipient.id','=','send_money.recipient_id')
                            ->join('countries','countries.id','=','send_money.sending_country_id')
                            ->join('users','users.id','=','withdrawal_money.created_by')
                            ->join('agencies','agencies.id','=','users.agency_id')
                            ->select('withdrawal_money.*','users.name as userName','sender.name as senderName','sender.surname as senderSurname','recipient.name as recipientName','recipient.surname as recipientSurname','countries.libelle_country','send_money.state','agencies.libelle_agency',DB::raw('DATE_FORMAT(withdrawal_date, "%d-%m-%Y à %H:%i") as withdrawalDate'))
                            ->where('withdrawal_money.id_recipient',$customer)
                            ->orderBy('withdrawal_money.id', 'DESC')
                            ->get();

       $outPut .= '<div class="container-table">
            <h3 align="center"><u>Liste des retraits du client '.$Customer->name.' '.$Customer->surname.'</h3>
            <table border="2" cellspacing="0" width="100%">
                <tr style="background-color:#dcdcdc;">
                    <th cellspacing="0" border="2" width="25%" align="center">DATE</th>
                    <th cellspacing="0" border="2" width="20%" align="center">MONTANT</th>
                    <th cellspacing="0" border="2" width="25%" align="center">EXPEDITEUR</th>
                    <th cellspacing="0" border="2" width="20%" align="center">PAYS D\'EXPEDIT.</th>
                    <th cellspacing="0" border="2" width="20%" align="center">AGENCE</th>
                    <th cellspacing="0" border="2" width="20%" align="center">CAISSIER</th>
                    <th cellspacing="0" border="2" width="10%" align="center">PAYE</th>
            </tr>';

        $totalRetrait = 0;
        foreach ($withdrawalMoney as $withdrawal) {
            $totalRetrait += $withdrawal->amount;

            $outPut .= '<tr>
                            <td cellspacing="0" border="2" align="left">&nbsp;&nbsp;'.$withdrawal->withdrawalDate.'</td>
                            <td  cellspacing="0" border="2" align="right">'.number_format($withdrawal->amount, 0, ',', ' ').'&nbsp;&nbsp;</td>
                            <td cellspacing="0" border="2" align="left">&nbsp;&nbsp;'.$withdrawal->senderName.' '.$withdrawal->senderSurname.'</td>
                            <td  cellspacing="0" border="2" align="left">&nbsp;&nbsp;'.$withdrawal->libelle_country.'</td>
                            <td  cellspacing="0" border="2" align="left">&nbsp;&nbsp;'.$withdrawal->libelle_agency.'</td>
                            <td  cellspacing="0" border="2" align="left">&nbsp;&nbsp;'.$withdrawal->userName.'</td>
                            <td  cellspacing="0" border="2" align="left">&nbsp;&nbsp;'.($withdrawal->state == "withdrawn" ? "OUI" : "NON").'</td>
                        </tr>';
        }

        $outPut.='</table>';
        $outPut.='<p>Retrait total : <b> '.number_format($totalRetrait, 0, ',', ' ').' F CFA</b></p></div>';
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
                                font-size : 15px;
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

}
