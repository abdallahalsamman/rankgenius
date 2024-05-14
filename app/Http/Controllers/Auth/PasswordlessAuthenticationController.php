<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Mail\SendMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\RedirectResponse;

class PasswordlessAuthenticationController extends Controller
{

    public function sendLink(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => 'required|email'
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            $user = User::create([
                'email' => $request->email,
            ]);
        }

        $url = URL::temporarySignedRoute(
            'passwordless.authenticate',
            now()->addMinutes(30),
            ['user' => $user->id]
        );

        // return redirect($url);

        Mail::to($request->email)->send(new SendMail($url));

        return back()->with('success', 'A link has been sent to your email address. Please click the link in the email to login');
    }

    public function authenticateUser(Request $request): RedirectResponse
    {
        $user = User::findOrFail($request->user);

        if (!URL::hasValidSignature($request)) {
            return redirect('/login')->withErrors(['url' => 'The link is invalid.']);
        }

        Auth::login($user);

        return redirect($request->next ?? '/dashboard/generate-articles');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        return redirect()->route('login');
    }
}
