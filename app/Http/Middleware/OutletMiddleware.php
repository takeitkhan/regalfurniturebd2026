<?php

namespace App\Http\Middleware;

use Closure;


class OutletMiddleware
{

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {


        if (auth()->check()) {
            if((auth()->user()->isAdmin() && auth()->user()->isAdmin()->id) || (auth()->user()->isOrderViewer() && auth()->user()->isOrderViewer()->id)){
                return $next($request);
            }else{
                return redirect('/');
            }

        } else {
            return redirect('/');
        }
    }

}
