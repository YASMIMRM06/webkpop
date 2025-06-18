<?php 
namespace App\Http\Controllers; 
use Illuminate\Http\Request; 
use Illuminate\Support\Facades\Auth; 
use Illuminate\Support\Facades\Hash; 
use App\Models\User; 
use Illuminate\Auth\Events\Registered; 
use Illuminate\Support\Facades\Password; 
use Illuminate\Auth\Events\PasswordReset; 
use Illuminate\Support\Str; 
class AuthController extends Controller 
{ 
    public function showLoginForm() 
    { 
        return view('auth.login'); 
    } 
    public function login(Request $request) 
    { 
        $credentials = $request->validate([ 
            'email' => 'required|email', 
            'password' => 'required', 
        ]); 
        if (Auth::attempt($credentials, $request->remember)) { 
            $request->session()->regenerate(); 
            return redirect()->intended('/'); 
        } 
        return back()->withErrors([ 
            'email' => 'As credenciais fornecidas não correspondem aos nossos registros.', 
        ])->onlyInput('email'); 
    } 
    public function showRegistrationForm() 
    { 
        return view('auth.register'); 
    } 
    public function register(Request $request) 
    { 
        $validated = $request->validate([ 
            'name' => 'required|string|max:255', 
            'email' => 'required|string|email|max:255|unique:users', 
            'password' => 'required|string|min:8|confirmed', 
            'birth_date' => 'nullable|date', 
        ]); 
 
        $validated['password'] = Hash::make($validated['password']); 
        $validated['type'] = 'fan'; 
        $user = User::create($validated); 
        event(new Registered($user)); 
        Auth::login($user); 
        return redirect()->route('verification.notice'); 
    } 
    public function logout(Request $request) 
    { 
        Auth::logout(); 
        $request->session()->invalidate(); 
        $request->session()->regenerateToken(); 
        return redirect('/'); 
    } 
    public function showForgotPasswordForm() 
    { 
        return view('auth.forgot-password'); 
    } 
    public function sendResetLinkEmail(Request $request) 
    { 
        $request->validate(['email' => 'required|email']); 
        $status = Password::sendResetLink( 
            $request->only('email') 
        ); 
        return $status === Password::RESET_LINK_SENT 
            ? back()->with(['status' => __($status)]) 
            : back()->withErrors(['email' => __($status)]); 
    } 
    public function showResetPasswordForm($token) 
    { 
        return view('auth.reset-password', ['token' => $token]); 
    } 
    public function resetPassword(Request $request) 
    { 
        $request->validate([ 
            'token' => 'required', 
            'email' => 'required|email', 
            'password' => 'required|min:8|confirmed', 
        ]); 
        $status = Password::reset( 
            $request->only('email', 'password', 'password_confirmation', 'token'), 
            function ($user, $password) { 
                $user->forceFill([ 
                    'password' => Hash::make($password) 
                ])->setRememberToken(Str::random(60)); 
                $user->save(); 
                event(new PasswordReset($user)); 
            } 
        ); 
        return $status == Password::PASSWORD_RESET 
            ? redirect()->route('login')->with('status', __($status)) 
            : back()->withErrors(['email' => [__($status)]]); 
    } 
}