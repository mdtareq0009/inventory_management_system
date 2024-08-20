<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Models\CompanyProfile;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Providers\RouteServiceProvider;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\UserActivity;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     *
     * @param  \App\Http\Requests\Auth\LoginRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(LoginRequest $request)
    {
        $request->authenticate();

        $request->session()->regenerate();

        $activity = UserActivity::create(array(
            'user_id' => auth()->user()->id,
            'login_time' => now(),
            'ip_address' => $request->ip(),
            'branch_id' => auth()->user()->branch_id,
        ));

        session(
            array(
                'user_activity_id' => $activity->id,
                'branch_id' => auth()->user()->branch_id,
            )
        );

        return redirect()->intended(RouteServiceProvider::HOME);
    }

    /**
     * Destroy an authenticated session.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request)
    {
        UserActivity::where('id', session('user_activity_id'))->update([ 'logout_time' => now() ]);
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
