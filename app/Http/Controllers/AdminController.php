<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Jobs\SendEmail;
use Illuminate\Support\Str;
use App\Mail\ResetPassword;
use App\Mail\ActivateAccount;
use Carbon\Carbon;

class AdminController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'email' => 'required | email | unique:users',
            'password' => 'required| min:8 | confirmed',
            'password_confirmation' => 'required | min:8',
            'phone' => 'required | unique:users',
            'first_name' => 'required',
            'last_name' => 'required',
            'company_name' => 'required',
            'siret'  => 'required | min:14 | max:14',
            'address' => 'required',
            'city' => 'required',
            'zip_code' => 'required',
            'country' => 'required',
        ]);
        $user = User::create([
            'email' => $request->email,
            'password' =>  Hash::make($request->password),
            'phone' => $request->phone,
            'first_name' => ucfirst($request->first_name),
            'last_name' => strtoupper($request->last_name),
            'company_name' => $request->company_name,
            'siret'  => $request->siret,
            'address' => $request->address,
            'city' => $request->city,
            'zip_code' => $request->zip_code,
            'country' => strtoupper($request->country),
            'code' => Str::random(7),
        ]);
        $user->assignRole('client');
        SendEmail::dispatch(new ActivateAccount($user), $user->email);   
        return redirect()->route('activate', $user->id)->with('message', 'Votre code d\'activation vous a été bien envoyé par mail à '. $user->email);
    }

    public function activate($id)
    {
        $userId = $id;
        return view('activate', compact('userId'));
    }

    public function resendCode($id)
    {
        $user = User::find($id);
        $user->code = Str::random(7);
        $user->save();
        SendEmail::dispatch(new ActivateAccount($user), $user->email);   
        return redirect()->route('activate', $user->id)->with('message', 'Votre code d\'activation vous a été bien envoyé par mail à '. $user->email);
    }

    public function activateAccount(Request $request, $id)
    {
        $request->validate([
            'code' => ['required'],
        ]);
        $user = User::findOrFail($id);
        if ($user && $request->code == $user->code ) {
            $user->verified_at = now();
            $user->save();
            return redirect()->route('login')->with('message', 'Votre compte a été bien activé.');
        }
        return redirect()->back()->with('fail', 'Code erroné.');
    }

    public function login(Request $request) 
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        $user = User::where('email', $request->email)->first();

        if ($user) {
            $isAdmin = in_array("admin", $user->getRoleNames()->toArray());
            if (!$isAdmin && $user->verified_at == null) {
                return redirect()->route('activate', $user->id)->with('fail', 'Veuillez procéder à l\'activation de votre compte.');
            }
            if (Auth::attempt($credentials)) {
                $request->session()->regenerate();
                return $isAdmin ? redirect()->route('admin.home') : redirect()->route('welcome');
            }
        }
        
        return back()->with([
            'error' => 'The provided credentials do not match any user.',
        ]);
    }
    public function logout(Request $request)
    {
        Auth::logout();
    
        $request->session()->invalidate();
    
        $request->session()->regenerateToken();
    
        return redirect()->route('welcome');
    }

    public function forgot()
    {
        return view('password');
    }

    public function verify(Request $request)
    {
        $request->validate([
            'email' => 'required'
        ]);
        $user = User::where('email', $request->email)->first();
        if ( $user && ! in_array('admin', $user->getRoleNames()->toArray() ) ) {
            $currentTimestamp = now()->timestamp;
            $token = encrypt($user->id.' '.$currentTimestamp);
            $url = config('app.url') . '/reset-password?token=' . $token;
            SendEmail::dispatch(new ResetPassword($url, $user), $user->email);   
        }
        return redirect()->back()->with('ok', 'Un email de récuperation du mot de passe vous sera envoyé si vous êtes inscrit sur notre site.');
    }

    public function reset(Request $request)
    {
        $request->validate([
            'token' => 'required'
        ]);
        $code = decrypt($request->token);
        $user = User::find(explode(' ', $code)[0]);
        $timestamp = Carbon::createFromTimestamp(explode(' ', $code)[1]);
        $diff = now()->diffInMinutes($timestamp);
        if ( $user && $diff <= 60 ) {
            return view('reset', compact('user'));
        }
        return redirect()->route('login')->with('message', 'Lien expiré, reéssayer de nouveau.');
    }

    public function updatePwd(Request $request)
    {
        $request->validate([
            'user' => 'required',
            'password' => 'required| min:8 | confirmed',
            'password_confirmation' => 'required | min:8',
        ]);
        $user = User::find($request->user);
        $user->update([
            'password' =>  Hash::make($request->password),
        ]);
        return redirect()->route('login')->with('message', 'Mot de passe mis à jour avec succès !');
    }
}
