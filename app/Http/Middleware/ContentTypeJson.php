<?php namespace WowTables\Http\Middleware;

use Closure;

class ContentTypeJson {

	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Closure  $next
	 * @return mixed
	 */
	public function handle($request, Closure $next)
	{
        if(!in_array(strtolower($request->method()), ['get','head'])){
            if(strpos($request->header('Content-Type'), 'application/json') !== false){
                return $next($request);
            }else{
                return response()->json([
                    'action' => 'Checking the content-type header of the request',
                    'message' => 'Invalid Content-Type. Please set it to application/json'
                ], 406);
            }
        }else{
            return $next($request);
        }
	}

}
