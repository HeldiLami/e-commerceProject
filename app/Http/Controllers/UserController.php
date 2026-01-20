<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function index()
    {
        $users = User::orderByDesc('created_at')->get();
        return view('admin.users', ['users' => $users]);
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
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'password' => [
                'nullable',
                'confirmed',
                Password::min(8)->letters()->numbers()->symbols()
            ],
        ]);

        if ($request->hasFile('photo')) {
            if ($user->photo) {
                Storage::disk('public')->delete($user->photo);
            }
            $path = $request->file('photo')->store('photos', 'public');

            $validated['photo'] = $path;
        } else {
            unset($validated['photo']);
        }

        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $user->update($validated);

        return redirect('/users' . $user->id)->with('success', 'User updated!');
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
            'photo' => ['nullable', 'string', 'max:2048'],
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
            unset($attributes['password']);//unset e heq nga array kete fushe qe mos ti bej update null ne db
        }

        if ($user->id === $request->user()->id && (int)$attributes['is_admin'] === 0) {
            return back()->with('error', 'Nuk mund ta heqësh veten nga admin.');
        }

        $user->update($attributes);

        return redirect()->route('admin.users.edit', $user)->with('success', 'User updated!');
    }
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
