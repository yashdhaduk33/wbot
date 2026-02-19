<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    protected $redirectTo = '/home';

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');
    }

    /**
     * Redirect user after login based on role.
     */
    protected function authenticated(Request $request, $user)
    {
        // Check if account is deactivated
        if (!$user->is_active) {
            auth()->logout();
            
            // Use with() for flash message instead of withErrors()
            return redirect()->route('login')
                ->with('error', 'Your account has been deactivated.')
                ->withInput($request->only('email'));
        }

        if ($user->isAdmin()) {
            return redirect()->intended(route('admin.dashboard'));
        }

        return redirect()->intended($this->redirectTo);
    }

    /**
     * Get the failed login response instance.
     */
    protected function sendFailedLoginResponse(Request $request)
    {
        return redirect()->back()
            ->withInput($request->only('email', 'remember'))
            ->withErrors([
                'email' => trans('auth.failed'),
            ]);
    }
}