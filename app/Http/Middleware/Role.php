<?php

namespace App\Http\Middleware;

use App\Models\Roles;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\URL;

class Role
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, $roles)
    {
        $user_role = Roles::where('role_name',$request->role)->first();
        $roles = explode("|", $roles); // convert $roles to array
       
        foreach($roles as $role) {
            if ($role == $user_role->role_name) {
                return $next($request);
            }
      }
    
        return response()->json(
            [
                'status' => false,
                'message' => "Sorry, you are not authorized or do not have the permission",
                'data' => null
            ],
            403
        );
    
     
    }

}
