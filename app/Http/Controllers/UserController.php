<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::all();
        //returns the user.index view with the users data idk if it needed
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //returns the user.create view
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedUser = $request->validate([
            'name' => 'required|string|max:255',
            'email'=>'required|email|unique:users',
            'password'=>[
                'required', 
                'string',
                Password::min(8)
                ->letters()
                ->numbers()
                ->symbols()
            ],
        ]);

        $validatedUser->password = Hash::make($validatedUser['password']);
        $user = User::create($validatedUser);
        $user->save();

        //redirect to the user.index view with a success message
    }

    /**
     * Display the specified resource.
     */
    public function show(string $email)
    {
     User::findOrFail($email);
     //return the view user.show   
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        //return the view user.edit
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $attributes = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', 'unique:users,email,' . $user->id],
            'password' => [
            'nullable',
            'confirmed',
            Password::min(8)->letters()->numbers()->symbols()
        ],
        ]);

        if (!empty($attributes['password'])) {
            $attributes['password'] = Hash::make($attributes['password']);
        } else {
            unset($attributes['password']);
        }
        $user->update($attributes);

        //return redirect('/users/' . $user->id)->with('success', 'User updated!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        $user->delete();
        // return redirect('/users')->with('success', 'User deleted successfully.');
    }
}