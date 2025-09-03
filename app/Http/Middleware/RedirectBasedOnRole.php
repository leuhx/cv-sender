<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RedirectBasedOnRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->user()) {
            $user = $request->user();

            // Se estiver no dashboard geral, redirecionar baseado no role
            if ($request->is('dashboard')) {
                if ($user->role->value === 'admin') {
                    return redirect()->route('admin.forms.index');
                } elseif ($user->role->value === 'applicant') {
                    return redirect()->route('forms.index');
                }
            }
        }

        return $next($request);
    }
}
