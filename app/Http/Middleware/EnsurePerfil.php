<?php 
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsurePerfil
{
    public function handle(Request $request, Closure $next, string $perfil): Response
    {
        abort_unless($request->user()?->perfil === $perfil, 403);

        return $next($request);
    }
}