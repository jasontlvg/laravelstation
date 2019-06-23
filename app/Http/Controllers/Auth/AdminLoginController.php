<?php

namespace App\Http\Controllers\Auth;


use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

// Nuevo
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

use Illuminate\Foundation\Auth\RedirectsUsers;
use Illuminate\Foundation\Auth\ThrottlesLogins;

class AdminLoginController extends Controller
{

    use RedirectsUsers, ThrottlesLogins; // Agregados para Limitar el numero de Intentos

    public function __construct()
    {
        $this->middleware('guest:admin')->except('logout');
    }
    
    public function showLoginForm(){
        return view('auth.login_admin');
    }

    public function login(Request $request)
    {
        // Validate the form data
        $this->validate($request, [
           'email' => 'required|email',
            'password' => 'required|min:6'
        ]);

        // Limitar el intento de Accesos
        if ($this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);

            return $this->sendLockoutResponse($request);
        }

        // Attempt to log the user in
        if(Auth::guard('admin')->attempt(['email'=>$request->email, 'password' => $request->password], $request->remember)){
            return redirect()->intended(route('admin.dashboard'));
        }

//        return redirect()->back()->withInput($request->only('email')); // If unsuccesfull, the redirect to their intendet location

        // Agregado solo tttl para el limite
        // If the login attempt was unsuccessful we will increment the number of attempts
        // to login and redirect the user back to the login form. Of course, when this
        // user surpasses their maximum number of attempts they will get locked out.
        $this->incrementLoginAttempts($request); // tttl

        return $this->sendFailedLoginResponse($request);
    }

    protected function sendFailedLoginResponse(Request $request)
    {
        throw ValidationException::withMessages([
            $this->username() => [trans('auth.failed')],
        ]);
    }
    public function username()
    {
        return 'email';
    }

    public function logout()
    {
        Auth::guard('admin')->logout();
        return redirect('/');
    }

}
