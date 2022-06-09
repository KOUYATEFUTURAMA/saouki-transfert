<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Carbon;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    protected function confirmCompte(Request $request){
        $this->validate($request, [
            'password' => 'required|confirmed|min:8',
        ]);

        $data = $request->all();
        $somme = intval($data["inputVal1"]) + intval($data["inputVal2"]);

        if($somme != $data["sommeval"]){
            return redirect()->back()->with('msg', 'Vérifier la somme des nombres svp !');
        }

        $user = User::where('email',$data['email'])->first();

        if($user){
            $user->confirmation_token = null;
            $user->password = bcrypt($data['password']);
            $user->save();
            return redirect('/');
        }
        return redirect()->back()->with('msg'," Votre compte n'existe pas");
    }

    protected function authenticated(Request $request, $user)
    {
       
        if($user->confirmation_token){
            $user = Auth::user();
            $user->update(['etat_user' => 0]);
            $email = $user->email;

            $this->guard()->logout();

            $request->session()->invalidate();

            $request->session()->regenerateToken();

            if ($response = $this->loggedOut($request)) {
                return $response;
            }

            return redirect('/confirm-compte')->with('email',$email);
        }
       
        //When user conneted
        $user->update([
            'etat_user' => 1
        ]);

        if($user->role == "Administrateur" or $user->role == "Gerant") {
            return redirect('/home-admin');
        }
        if($user->role == "Superviseur") {
            return redirect('/home-superviseur');
        } 
        if($user->role == "Comptable") {
            return redirect('/home-comptable');
        } 
        if($user->role == "Agent") {
            return redirect('/home-agent');
        } 
    }

    public function logout(Request $request)
    {
        $user = Auth::user();
        $user->update([
                    'last_login_at' => Carbon::now()->toDateTimeString(),
                    'last_login_ip' => $request->getClientIp(),
                    'etat_user' => 0
                ]);

        $this->guard()->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        if ($response = $this->loggedOut($request)) {
            return $response;
        }

        return $request->wantsJson() ? new Response('', 204) : redirect('/');
    }
}
