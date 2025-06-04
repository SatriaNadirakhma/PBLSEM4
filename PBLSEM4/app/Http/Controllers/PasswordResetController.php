<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Models\UserModel;

class PasswordResetController extends Controller
{
    public function showLinkRequestForm()
    {
        return view('auth.passwordmail');
    }

    public function sendResetLinkEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ], [
            'email.required' => 'Email harus diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.exists' => 'Email tidak ditemukan dalam sistem.',
        ]);

        try {
            $token = Str::random(64);

            DB::table('password_resets')->where('email', $request->email)->delete();

            DB::table('password_resets')->insert([
                'email' => $request->email,
                'token' => Hash::make($token),
                'created_at' => Carbon::now()
            ]);

            $user = UserModel::where('email', $request->email)->first();

            Mail::send('emails.password-reset', [
                'token' => $token,
                'email' => $request->email,
                'name' => $user->username ?? 'UserModel'
            ], function($message) use ($request) {
                $message->to($request->email);
                $message->subject('Reset Password - siPinta');
            });

            return response()->json([
                'status' => true,
                'message' => 'Link reset password telah dikirim ke email Anda.'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Gagal mengirim email. Silakan coba lagi.'
            ], 500);
        }
    }

    public function showResetForm(Request $request, $token = null)
    {
        $email = $request->email;

        if (!$email || !$token) {
            return redirect('/login')->with('error', 'Link reset password tidak valid.');
        }

        $resetRecord = DB::table('password_resets')
            ->where('email', $email)
            ->where('created_at', '>=', Carbon::now()->subHours(1))
            ->first();

        if (!$resetRecord || !Hash::check($token, $resetRecord->token)) {
            return redirect('/login')->with('error', 'Link reset password sudah kadaluarsa atau tidak valid.');
        }

        return view('auth.passwords.reset', [
            'token' => $token,
            'email' => $email
        ]);
    }

    public function reset(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed',
        ], [
            'token.required' => 'Token tidak valid.',
            'email.required' => 'Email harus diisi.',
            'email.email' => 'Format email tidak valid.',
            'password.required' => 'Password harus diisi.',
            'password.min' => 'Password minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
        ]);

        try {
            $resetRecord = DB::table('password_resets')
                ->where('email', $request->email)
                ->where('created_at', '>=', Carbon::now()->subHours(1))
                ->first();

            if (!$resetRecord || !Hash::check($request->token, $resetRecord->token)) {
                return response()->json([
                    'status' => false,
                    'message' => 'Link reset password sudah kadaluarsa atau tidak valid.'
                ], 400);
            }

            $user = UserModel::where('email', $request->email)->first();

            if (!$user) {
                return response()->json([
                    'status' => false,
                    'message' => 'User tidak ditemukan.'
                ], 404);
            }

            $user->password = Hash::make($request->password);
            $user->save();

            DB::table('password_resets')->where('email', $request->email)->delete();

            return response()->json([
                'status' => true,
                'message' => 'Password berhasil diubah. Silakan login dengan password baru.'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Terjadi kesalahan saat mengubah password.'
            ], 500);
        }
    }
}
