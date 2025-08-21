<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use PragmaRX\Google2FA\Google2FA;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;

class TwoFactorController extends Controller
{
    protected $google2fa;

    public function __construct()
    {
        $this->google2fa = new Google2FA();
    }

    public function show()
    {
        $user = Auth::user();

        if ($user->google2fa_enabled) {
            return redirect()->route('dashboard')->with('info', '2FA is already enabled');
        }

        // Generate secret key if not exists
        if (!$user->google2fa_secret) {
            $user->google2fa_secret = Crypt::encrypt($this->google2fa->generateSecretKey());
            $user->save();
        }

        $secret = Crypt::decrypt($user->google2fa_secret);

        // Generate QR code URL using different method
        $qrCodeUrl = 'https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=' . urlencode(
            'otpauth://totp/KlikBid:' . $user->email . '?secret=' . $secret . '&issuer=KlikBid'
        );

        return view('auth.2fa-setup', compact('qrCodeUrl', 'secret'));
    }

    public function enable(Request $request)
    {
        $request->validate([
            'one_time_password' => 'required|numeric'
        ]);

        $user = Auth::user();
        $secret = Crypt::decrypt($user->google2fa_secret);

        $valid = $this->google2fa->verifyKey($secret, $request->one_time_password);

        if ($valid) {
            $user->google2fa_enabled = true;
            $user->google2fa_enabled_at = now();
            $user->save();

            return redirect()->route('dashboard')->with('success', '2FA enabled successfully!');
        }

        return back()->withErrors(['one_time_password' => 'Invalid verification code']);
    }

    public function disable(Request $request)
    {
        $request->validate([
            'one_time_password' => 'required|numeric'
        ]);

        $user = Auth::user();
        $secret = Crypt::decrypt($user->google2fa_secret);

        $valid = $this->google2fa->verifyKey($secret, $request->one_time_password);

        if ($valid) {
            $user->google2fa_enabled = false;
            $user->google2fa_secret = null;
            $user->google2fa_enabled_at = null;
            $user->save();

            return redirect()->route('dashboard')->with('success', '2FA disabled successfully!');
        }

        return back()->withErrors(['one_time_password' => 'Invalid verification code']);
    }
}
