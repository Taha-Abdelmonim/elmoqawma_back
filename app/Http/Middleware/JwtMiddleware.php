<?php
  
  namespace App\Http\Middleware;
  
  use Closure;
  use Illuminate\Http\Request;
  use Tymon\JWTAuth\Facades\JWTAuth;
  
  class JwtMiddleware
  {
    public function handle(Request $request, Closure $next)
    {
      try {
        $user = JWTAuth::parseToken()->authenticate();
      } catch (\Exception $e) {
        if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenInvalidException) {
          return returnError($e->getCode(), 'يجب تسجيل الدخول اولا');
        } elseif ($e instanceof \Tymon\JWTAuth\Exceptions\TokenExpiredException) {
          return response()->json(['message' => 'يجب تسجيل الدخول مره اخري']);
        } else {
          return response()->json(['message' => 'انت لست مسجل', 'errCode' => 304]);
        }
      }
      return $next($request);
    }
  }
