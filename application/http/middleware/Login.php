<?php

namespace app\http\middleware;

class login
{
    public function handle($request, \Closure $next)
    {
        if (!session('adminLoginVal')){
            return redirect('admin/Login/in');
        }
        return $next($request);
    }
}
