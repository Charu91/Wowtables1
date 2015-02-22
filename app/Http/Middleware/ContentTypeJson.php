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
            if($request->isJson()){
                $body = $request->getContent();
                if(is_string($body) && is_object(json_decode($body)) && (json_last_error() == JSON_ERROR_NONE)){
                    return $next($request);
                }else{
                    return response()->json([
                        'action' => 'Checking if the body is a validly formed JSON',
                        'message' => 'Please check your request body. The JSON is incorrectly formed'
                    ], 409);
                }
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
