<?php

namespace App\Http\Middleware;

use Closure;

class CheckRole
{
    /**
     * Handle an incoming request to check the authorization of the user and integrity.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @param $roles
     * @return mixed
     */
    public function handle($request, Closure $next, $roles)
    {
        if ($request->user()->hasRole($roles) == false) {
            if ($request->ajax()) {
                return \Response::json(["errors" => ["error" => __('general.not_allowed')]], 403);
            }
            \App::abort(403);
        }

        return $next($request);
    }
}
