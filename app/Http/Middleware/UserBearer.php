<?php

namespace App\Http\Middleware;

use App\Traits\CustomResponseTrait;
use Closure;
use Illuminate\Http\Response;

class UserBearer
{
    use CustomResponseTrait;

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        
        if (auth()->user()->verified == 0) {
            $message = __('message.unauthorize_access');
            return $this->sendResponse(Response::HTTP_UNAUTHORIZED, [], $message, [$message]);
        }

        return $next($request);
    }
}
