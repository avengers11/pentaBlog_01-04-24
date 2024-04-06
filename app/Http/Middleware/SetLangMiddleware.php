<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Language;
use App;

class SetLangMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
     public function handle($request, Closure $next)
     {

         if (session()->has('lang')) {
           app()->setLocale(session()->get('lang'));
         } else {
           if (!empty($defaultLang)) {
             app()->setLocale($defaultLang->code);
           }
         }

         return $next($request);
     }
}
