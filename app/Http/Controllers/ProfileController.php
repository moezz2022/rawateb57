<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Illuminate\Support\Facades\Hash;
use App\Models\Group;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{

    public function edit(Request $request): View
    {
        $groups = Group::all();

        return view('profile.edit', [
            'user' => $request->user(),
            'groups' => $groups
        ]);
    }


    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();

        $user->fill($request->validated());

        if ($user->isDirty('email')) {

        }

        $user->save();

        return Redirect::route('profile.edit')->with('status', 'تم تحديث الملف الشخصي بنجاح');
    }


    public function updatePassword(Request $request): RedirectResponse
    {
        $request->validate([
            'current_password' => 'required|string',
            'new_password' => 'required|string|min:8|confirmed',
        ]);

        $user = auth()->user();

        if (!Hash::check($request->current_password, $user->password)) {
            return redirect()->back()->withErrors(['current_password' => 'كلمة المرور الحالية غير صحيحة.']);
        }

        $user->password = Hash::make($request->new_password);
        $user->save();

        return redirect()->route('profile.edit')->with('status', 'تم تغيير كلمة المرور بنجاح!');
    }

    public function updateAvatar(Request $request): RedirectResponse
    {
        $request->validate([
            'avatar' => ['required', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
        ]);

        $user = auth()->user();

        if ($user->avatar) {
            Storage::delete('public/' . $user->avatar);
        }

        $path = $request->file('avatar')->store('avatars', 'public');
        $user->avatar = $path;
        $user->save();

        return redirect()->route('profile.edit')->with('status', 'تم تحديث الصورة الشخصية بنجاح');
    }
    public function editPassword()
    {
        $user = auth()->user();
        return view('profile.updatepassword', compact('user'));
    }
}
