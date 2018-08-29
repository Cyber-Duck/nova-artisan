<?php

namespace CyberDuck\NovaArtisan\Http\Middleware;

use CyberDuck\NovaArtisan\NovaArtisan;

class Authorize
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return \Illuminate\Http\Response
     */
    public function handle($request, $next)
    {
        return resolve(NovaArtisan::class)->authorize($request) ? $next($request) : abort(403);
    }
}
