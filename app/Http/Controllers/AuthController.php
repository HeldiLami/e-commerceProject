<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function showRegisterUser(){
      return view('auth.register-user');
    }

    public function showLogin(){
      return view('auth.login');
    }

    public function register(Request $request){
      $validatedUser = $request->validate([
        'name' => 'required|string|max:255',
        'email'=>'required|email|unique:users',
        'password'=>[
            'required', 
            'string',
            'confirmed',
            Password::min(8)
            ->letters()
            ->numbers()
            ->symbols(),
        ], 
        'is_admin'=>'boolean'
    ]);
      $validatedUser['password'] = Hash::make($validatedUser['password']);
      $user = User::create($validatedUser);
      Auth::login($user);
      $request->session()->regenerate();
      $user->sendEmailVerificationNotification();

      return redirect()->route('verification.notice');
    }

    public function login(Request $request){
      $request->validate([
        'email' => 'required|email',
        'password' => 'required|string'
      ]);

      $user = User::where('email', $request->email)->first();

      if(!$user){
        throw ValidationException::withMessages([
          'email' => 'The email isn\'t registered'
        ]);
      }

      if(!Hash::check($request->password, $user->password)){
        throw ValidationException::withMessages([
         'password' => 'Password is incorrect'
        ]);
      }
      Auth::login($user);
      $request->session()->regenerate();  

      if ($user->is_admin) {
        return redirect('/admin');
      }

      return redirect()->route('home');
    }

    public function logout(Request $request){
      //removes all of the user data from the current session
      Auth::logout();
      //removes any other data related with the session
      $request->session()->invalidate();
      //regenerates CSRF token so requests from the prev sessions dont get accepted
      $request->session()->regenerateToken();

      return redirect()->route('home');
    }
}
