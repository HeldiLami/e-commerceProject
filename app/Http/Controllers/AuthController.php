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
    public function showRegister(){
      return view('auth.register', ['isAdmin' => false]);
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

    return redirect()->route('users.index');
    }

    public function login(Request $request){
      $validatedUser = $request->validate([
        'email'=>'required|email',
        'password'=>'required|string', 
        'is_admin'=>'boolean'
      ]);
      if(Auth::attempt($validatedUser)){
        $request->session()->regenerate();

        return redirect()->route('users.index');
      } 

      throw ValidationException::withMessages([
        'credentials' => 'Sorry incorect credentials'
      ]);
    }

    public function logout(Request $request){
      //removes all of the user data from the current session
      Auth::logout();
      //removes any other data related with the session
      $request->session()->invalidate();
      //regenerates CSRF token so requests from the prev sessions dont get accepted
      $request->session()->regenerateToken();
    }
}
