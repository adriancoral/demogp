<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Str;
use Symfony\Component\HttpKernel\Exception\NotAcceptableHttpException;

class AcceptJsonOnly
{
    /**
     * @param $request
     * @param Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (!collect(['POST', 'PATCH', 'PUT'])->contains($request->method())) {
            return $next($request);
        }

        if (!Str::of($request->header('content-type'))->contains('application/json')) {
            throw new NotAcceptableHttpException();
        }

        return $next($request);
    }
}
