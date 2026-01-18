<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $users = User::orderByDesc('created_at')->get();
        return view('admin.users', compact('users'));
    }

    public function show(Request $request)
    {
        $user = $request->user();
        return view('users.show', ['user' => $user]);
    }

    public function edit(User $user)
    {
        return view('users.edit', ['user' => $user]);
    }

    public function update(Request $request, User $user)
    {
        $attributes = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'photo' => ['nullable', 'string', 'max:2048'], // ✅ mos e detyro url nese do path lokal
            'password' => [
                'nullable',
                'confirmed',
                Password::min(8)->letters()->numbers()->symbols()
            ],
        ]);

        // photo: bosh => null
        $attributes['photo'] = isset($attributes['photo']) && trim($attributes['photo']) !== ''
            ? trim($attributes['photo'])
            : null;

        if (!empty($attributes['password'])) {
            $attributes['password'] = Hash::make($attributes['password']);
        } else {
            unset($attributes['password']);
        }

        $user->update($attributes);

        return redirect('/users/' . $user->id)->with('success', 'User updated!');
    }

    public function adminEdit(User $user)
    {
        return view('admin.users-edit', ['user' => $user]);
    }

    public function adminUpdate(Request $request, User $user)
    {
        $attributes = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'photo' => ['nullable', 'string', 'max:2048'], // ✅ mos e detyro url nese do path lokal
            'is_admin' => ['required', 'boolean'],
            'password' => [
                'nullable',
                'confirmed',
                Password::min(8)->letters()->numbers()->symbols()
            ],
        ]);

        $attributes['photo'] = isset($attributes['photo']) && trim($attributes['photo']) !== ''
            ? trim($attributes['photo'])
            : null;

        if (!empty($attributes['password'])) {
            $attributes['password'] = Hash::make($attributes['password']);
        } else {
            unset($attributes['password']);
        }

        if ((int)$user->id === (int)auth()->id() && (int)$attributes['is_admin'] === 0) {
            return back()->with('error', 'Nuk mund ta heqësh veten nga admin.');
        }

        $user->update($attributes);

        return redirect()->route('admin.users.edit', $user)->with('success', 'User updated!');
    }

    public function destroy(User $user)
    {
        if ($user->is_admin) {
            return back()->with('error', 'Nuk mund të fshish një admin.');
        }

        $user->delete();
        return back()->with('success', 'User u fshi me sukses.');
    }
}
