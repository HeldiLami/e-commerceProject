<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;

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
            'is_admin'=>'boolean'
        ],
    ]);

    $validatedUser['password'] = Hash::make($validatedUser['password']);
    $user = User::create($validatedUser);
    Auth::login($user);

    return redirect()->route('users.index');
    }

    public function login(){
    }
}
