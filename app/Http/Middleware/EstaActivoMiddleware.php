<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EstaActivoMiddleware
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

        if(auth()->user()->status == 'inactivo'){

            if(auth()->user()){

                auth()->logout();

            }

            return redirect()->route('login')->with('mensaje', 'Tu cuenta esta bloqueada, contacta al administrador.');

        }

        return $next($request);
    }
}
