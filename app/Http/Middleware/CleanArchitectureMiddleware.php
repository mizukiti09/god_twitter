<?php

namespace App\Http\Middleware;

use Closure;

class CleanArchitectureMiddleware
{
    public static $view; // view 関数の結果を格納するフィールド
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        return $next($request);
    }
}
