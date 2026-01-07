<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

use Closure;

use App\Helpers\ApiFormatter;

use Tymon\JWTAuth\Facades\JWTAuth;
use TymonJWTAuth\Exceptions\TokenExpiredException;
use TymonJWTAuth\Exceptions\TokenInvalidException;
use TymonJWTAuth\Exceptions\TokenBlacklistedException;
use TymonJWTAuth\Exceptions\JWTException;

class Authenticate extends Middleware
{
    public function handle($request, Closure $next, ...$guards)
    {
        $header = $request->header('Authorization');
        if (!$header) {
            return response()->json(ApiFormatter::createJson(401, 'Authorization header not provided'), 401);
        }

        try {
            // Verifikasi token yang dikirimkan
            $user = JWTAuth::parseToken()->authenticate();
            if (!$user) {
                return response()->json(ApiFormatter::createJson(401, 'Unauthorized'), 401);
            }
        } catch (TokenExpiredException $e) {
            return response()->json(ApiFormatter::createJson(401, 'Token has expired'), 401);
        } catch (TokenInvalidException $e) {
            return response()->json(ApiFormatter::createJson(401, 'Token is invalid'), 401);
        } catch (TokenBlacklistedException $e) {
            return response()->json(ApiFormatter::createJson(401, 'Token is invalid'), 401);
        } catch (JWTException $e) {
            return response()->json(ApiFormatter::createJson(401, 'Token is invalid'), 401);
        }

        return $next($request);
    }
    
    protected function redirectTo(Request $request): ?string
    {
        return null;
    }
}