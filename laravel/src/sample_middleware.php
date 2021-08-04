<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureAccessToPageIsValid
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $initParam = null;
        foreach($request->route()->parameters() as $param) {
            // skip for initial route parameter
            if ($initParam === null) {
                $initParam = $param;
                continue;
            }

            // check for user routes
            if ($request->routeIs('*.user.*')) {
                if($initParam->users->where('id', $param->id)->isEmpty()) {
                    abort(403);
                }
            }

            // check if there's relationship between models
            $relation = $this->getRelationName($initParam);
            if ($initParam->id !== $param->$relation->id) {
                abort(403);
            }
            $initParam = $param;
        }
        return $next($request);
    }

    private function getRelationName(object $param): string
    {
        $chunk = explode('\\', get_class($param));
        return lcfirst(end($chunk));
    }
}
