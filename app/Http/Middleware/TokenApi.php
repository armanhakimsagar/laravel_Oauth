<?php

namespace App\Http\Middleware;

use Closure;
use Session;
use App\User;

class TokenApi
{
    public function handle($request, Closure $next)
    {
        $DatabaseToken = User::where('api_token',$request->token)->first();

        if($DatabaseToken != null) {
            return $next($request);  // if exist proceed to next step
        } else {
            return redirect('/');
        }
    }
}
