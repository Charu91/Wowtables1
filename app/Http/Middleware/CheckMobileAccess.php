<?php namespace WowTables\Http\Middleware;

use Closure;
use Validator;
use DB;

class CheckMobileAccess {

	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Closure  $next
	 * @return mixed
	 */
	public function handle($request, Closure $next)
	{
        $input = $request->all();
        $v = Validator::make($input, [
            'access_token' => 'required',
            'device_id' => 'required'
        ]);

        if ($v->fails()) {
            return response()->json([
                'action' => 'Check if a valid access_token and device_id has been entred',
                'message' => 'There was a problem with the params sent. Please check and try again',
                'errors' => $v->errors()
            ], 422);
        }

        $userId = DB::table('user_devices')->where([
            'access_token' => $input['access_token'],
            'device_id' => $input['device_id']
        ])->pluck('user_id');

        if(!$userId){
            return response()->json([
                'action' => 'Checking the device id and access token combo in the database',
                'message' => 'Log the user out as the device is not registered in the system'
            ], 480);
        }else{
            $request->request->add(['user_id' => $userId]);
            return $next($request);
        }


	}

}
