<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    public function show()
    {
        $user     = auth()->user();
        $bookings = $user->bookings()->latest()->get();

        return view('profile.show', compact('user', 'bookings'));
    }

    public function update(Request $request)
    {
        $user    = auth()->user();
        $section = $request->input('section', 'info');

        if ($section === 'password') {

            $request->validate([
                'password' => [
                    'required',
                    'confirmed',
                    'min:8',
                    'regex:/[A-Z]/',
                    'regex:/[a-z]/',
                    'regex:/[0-9]/',
                    'regex:/[!@#$%^&*()\-_=+\[\]{};:\'",.<>\/?\\|`~]/',
                ],
            ],[
                'password.required'  => 'Password baru wajib diisi.',
                'password.confirmed' => 'Password tidak sepadan.',
                'password.min'       => 'Password mestilah sekurang-kurangnya 8 aksara.',
                'password.regex'     => 'Password mesti ada huruf besar, huruf kecil, nombor dan simbol.',
            ]);

            $user->password = Hash::make($request->password);
            $user->save();

            return back()->with('success', 'Password berjaya dikemaskini!');
        }

        // INFO section
        $request->validate([
            'name'  => ['required','string','max:255'],
            'email' => [
                'required','string','email','max:255',
                Rule::unique('users')->ignore($user->id),
            ],
            'phone' => [
                'required',
                'string',
                'regex:/^[0-9]+$/',
                'min:9',
                'max:11',
            ],
        ],[
            'phone.regex' => 'Nombor telefon mestilah nombor sahaja.',
            'phone.min'   => 'Nombor telefon mestilah sekurang-kurangnya 9 digit.',
            'phone.max'   => 'Nombor telefon tidak boleh melebihi 11 digit.',
            'email.unique'=> 'Email ini telah digunakan.',
        ]);

        $user->name  = $request->name;
        $user->email = $request->email;
        $user->phone = $request->phone;
        $user->save();

        return back()->with('success', 'Profil berjaya dikemaskini!');
    }
}