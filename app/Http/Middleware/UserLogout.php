<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\UserActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserLogout
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();
        if($user){
            if ($user->logout || $user->status == 'd') {
                
                if($user->logout){
                    $user->logout = false;
                    $user->save();
                    
                    $message = 'Force logout. Try again!';
                }else{
                    $message = 'Your account is deactive';
                }
                
                UserActivity::where('id', session('user_activity_id'))->update([ 'logout_time' => now() ]);
                Auth::logout();
                return redirect()->route('login')->withErrors([$message]);
            }
        }

        return $next($request);
    }
}
