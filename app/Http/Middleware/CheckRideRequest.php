<?php

namespace App\Http\Middleware;

use Closure;
use Dingo\Api\Routing\Helpers;

class CheckRideRequest
{

    use Helpers;

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // sleep(3);
        

        return $next($request);
    }
}
