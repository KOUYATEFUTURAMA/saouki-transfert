<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Mail\SimpleMessage;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use Illuminate\Foundation\Auth\ResetsPasswords;

class ResetPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    use ResetsPasswords;

    public function askPassword(Request $request){

        $jsonData = ["code" => 1, "msg" => "Votre mot de passe a été envoyer dans votre boite mail."];
        $data = $request->all();

        if(isset($data["inputVal1"]) && isset($data["inputVal2"])){
            $somme = intval($data["inputVal1"]) + intval($data["inputVal2"]);

            if($somme != $data["sommeval"]){
                return response()->json(["code" => 0, "msg" => "Vérifier la somme des nombres svp !", "data" => NULL]);
            }
        }

        $user = User::where('email', $data["email"])->first();

        if($user){
            //noouvel enregistrement
            $password = "";
            $ranges = array(range('a', 'z'), range('A', 'Z'), range(1, 9));
            for ($i = 0; $i <= 10; $i++) {
                $rkey = array_rand($ranges);
                $vkey = array_rand($ranges[$rkey]);
                $password.= $ranges[$rkey][$vkey];
            }

            $user->password = bcrypt($password);
            $user->confirmation_token = str_replace('/', '', bcrypt(Str::random(16)));
            $user->save();

            //envoie de mail pour la création du compte
            $subject = 'Réinitialisation de votre mot de passe';
            $body = "Cher(e) ".$user->name.", <br/>Vous avez demandé une réinitialisation de votre mot de passe.<br/> Votre nouveau mot de passe est le suivant: <strong>".$password."</strong><br/>Ignonrer cet e-mail si ceci est une erreur.";
            Mail::to($user)->send((new SimpleMessage($subject, $body))->onQueue('notifications'));
            
            $jsonData["data"] = json_decode($user);
            return response()->json($jsonData);
        }

        return response()->json(["code" => 0, "msg" => "Ce compte n'existe pas dans nos données, veillez contactez l'administrateur", "data" => NULL]);
    }
}
