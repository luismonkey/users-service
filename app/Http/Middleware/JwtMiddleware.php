<?php

namespace App\Http\Middleware;

use Closure;
use JWTAuth;
use Exception;
use App\Traits\ApiResponse;
use Tymon\JWTAuth\Http\Middleware\BaseMiddleware;
use Illuminate\Support\Facades\Auth;

class JwtMiddleware extends BaseMiddleware
{
    use ApiResponse;

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        Auth::shouldUse($guard);
        try {
            $token = JWTAuth::getToken();

            if(!$token)
                return $this->error('The token could not be parsed from the request', 404);

            // Setear el token para el guard específico
            JWTAuth::setToken($token)->authenticate();

            // Autenticar el token usando el guard específico
            $user = Auth::user();

            if(!$user)
                return $this->error('User not authenticated', 401);

        } catch (\Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {
            return $this->error('Token is Invalid', 401);
        } catch (\Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {
            return $this->error('Token is Expired', 401);
        } catch (Exception $e) {
            return $this->error($e->getMessage(), 500);
        }

        return $next($request);
    }
}
