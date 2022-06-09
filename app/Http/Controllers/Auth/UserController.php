<?php

namespace App\Http\Controllers\Auth;

use Exception;
use App\Models\User;
use App\Mail\SimpleMessage;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class UserController extends Controller
{
    public function index(){

        $countries = DB::table('countries')
                        ->select('libelle_country','id')
                        ->orderby('libelle_country','ASC')
                        ->get();

        $menuPrincipal = "Auth";
        $titleControlleur = "Utilisateurs";
        $btnModalAjout = "TRUE";

        return view('auth.user.index', compact('countries','btnModalAjout', 'menuPrincipal', 'titleControlleur'));
    }

    public function profil(){
        $user = User::with('agency','city')
                    ->where('users.id', Auth::user()->id)
                    ->select('users.*',DB::raw('DATE_FORMAT(last_login_at, "%d-%m-%y à %H:%i") as last_login'),DB::raw('DATE_FORMAT(users.created_at, "%d-%m-%Y") as created'))
                    ->first();

        $menuPrincipal = "Auth";
        $titleControlleur = "Profil utilisateur";
        $btnModalAjout = "FALSE";
        return view('auth.user.profil', compact('user','btnModalAjout', 'menuPrincipal', 'titleControlleur'));
    }

    public function listUser(){
        $users = User::with('agency','city')
                ->select('users.*',DB::raw('DATE_FORMAT(last_login_at, "%d-%m-%Y à %H:%i:%s") as last_login'),DB::raw('DATE_FORMAT(created_at, "%d-%m-%Y à %H:%i") as created'))
                ->where('users.id','!=',1)
                ->orderBy('name','asc')
                ->get();

        $jsonData["rows"] = $users->toArray();
        $jsonData["total"] = $users->count();
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
                    //verification du dubblon sur l'email
                    $User = User::where([['email',$data['email']],['id','!=',$data['id']]])->first();
                    if($User){
                        return response()->json(["code" => 0, "msg" => " L'email que vous avez choisi existe !", "data" => NULL]);
                    }

                    $user = isset($data['id']) ? User::find($data['id']) : new User();
                    $user->name = $data['name'];
                    $user->role = $data['role'];
                    $user->contact = $data['contact'];
                    $user->statut_compte = isset($data['statut_compte']) ? TRUE : FALSE;
                    $user->country_id = isset($data['country_id']) ? $data['country_id'] : NULL;
                    $user->city_id = isset($data['city_id']) ? $data['city_id'] : NULL;
                    $user->agency_id = isset($data['agency_id']) ? $data['agency_id'] : NULL;

                    if(empty($data['id']) or ($user->email != $data['email'])){
                        //For new registrer or email updated we send acount informations by email
                        $password = "";
                        $ranges = array(range('a', 'z'), range('A', 'Z'), range(1, 9));
                        for ($i = 0; $i <= 8; $i++) {
                            $rkey = array_rand($ranges);
                            $vkey = array_rand($ranges[$rkey]);
                            $password.= $ranges[$rkey][$vkey];
                        }
                        $user->password = bcrypt($password);
                        $user->confirmation_token = str_replace('/', '', bcrypt(Str::random(16)));
                    }

                    $oldemail = $user->email;
                    $user->email = $data['email'];
                    $user->save();

                    if(($user && empty($data['id'])) or ($oldemail != $data['email'])){
                        //Sending acount informations by email
                        $url = config('app.url');
                        $subject = 'Création de votre compte de connexion';
                        $body = "Cher(e) ".$data['name'].", <br/>Votre compte de connexion SAOUKI TRANSFERT vient d'être crée.<br/> Vos informations de connexion sont les suivantes, <br/> Login : <strong> ".$data['email']."</strong><br/> Mot de passe : <strong>".$password."</strong><br/>Lien de de l'application : <strong><a style='text-decoration:none;' target='_blank' href='$url'>Saouki Transfert</a></strong><br/>Vous devez<strong> changer de mot de passe lors de votre prémière connexion .</strong><br/>Ignonrer cet e-mail si ceci est une erreur.";
                        Mail::to($user)->send((new SimpleMessage($subject, $body))->onQueue('notifications'));
                    }
                    
                    $jsonData["data"] = json_decode($user);
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

    public function updateProfil(Request $request, int $user){
        $user = User::find($user);
        $data = $request->all();

        $jsonData = ["code" => 1, "msg" => "Modification effectuée avec succès."];

        if($user){

            try {
                    //verification du dubblon sur l'email
                    $User = User::where([['email',$data['email']],['id','!=',$user->id]])->first();
                    if($User){
                        return response()->json(["code" => 0, "msg" => " L'email que vous avez choisi existe !", "data" => NULL]);
                    }

                    $user->name = $data['name'];
                    $user->email = $data['email'];
                    $user->contact = $data['contact'];
                    $user->save();

                $jsonData["data"] = json_decode($user);
            return response()->json($jsonData);

            } catch (Exception $exc) {
               $jsonData["code"] = -1;
               $jsonData["data"] = NULL;
               $jsonData["msg"] = $exc->getMessage();
               return response()->json($jsonData);
            }
        }
        return response()->json(["code" => 0, "msg" => "Utilisateur introuvable !", "data" => NULL]);
    }

    public function updatePassword(Request $request, int $user){
        $user = User::find($user);
        $data = $request->all();

        $jsonData = ["code" => 1, "msg" => "Modification effectuée avec succès."];

        if($user){

            try {
                  //Control du mot de passe actuelle
                    $credentials = request(['email', 'password']);
                    if (!Auth::attempt($credentials)) {
                        return response()->json(["code" => 0, "msg" => "Votre ancien mot de passe est incorrect.", "data" => NULL]);
                    }

                    //Control de l'égalité des deux de mot de passe
                    if($data['new_password'] != $data['repeat_password']){
                        return response()->json(["code" => 0, "msg" => "Il y a inconformité entre le nouveau mot de passe et la confirmation du mot de passe.", "data" => NULL]);
                    }

                    $user->password = bcrypt($data['new_password']);
                    $user->save();

                $jsonData["data"] = json_decode($user);
            return response()->json($jsonData);

            } catch (Exception $exc) {
               $jsonData["code"] = -1;
               $jsonData["data"] = NULL;
               $jsonData["msg"] = $exc->getMessage();
               return response()->json($jsonData);
            }
        }
        return response()->json(["code" => 0, "msg" => "Utilisateur introuvable !", "data" => NULL]);
    }

    public function destroy($id)
    {
        $jsonData = ["code" => 1, "msg" => "Opération effectuée avec succès."];
        $user = User::find($id);
        if($user){
            try {

                $user->delete();
                $jsonData["data"] = json_decode($user);
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
