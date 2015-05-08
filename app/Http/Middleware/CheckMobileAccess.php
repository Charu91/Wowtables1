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

        $user = DB::table('user_devices')
                    ->join('users', 'users.id','=','user_devices.user_id')
                    ->leftJoin('locations', 'users.location_id','=','locations.id')
                    ->where([
                        'access_token'  => $input['access_token'],
                        'device_id'     => $input['device_id']
                    ])->first(['user_id','location_id', 'locations.name as location','phone_number', 'full_name']);

        if(!$user){
            return response()->json([
                'action' => 'Checking the device id and access token combo in the database',
                'message' => 'Log the user out as the device or user is not registered in the system'
            ], 480);
        }else{
            /*if(!$user->location_id || !$user->phone_number){
                return response()->json([
                    'action' => 'Check if location and phone number has been set',
                    'message' => 'The location and phone number needs to be set before proceeding further'
                ], 225);
            }else{*/
                $request->request->add(['user' => $user]);
                return $next($request);
            //}



        }


	}

}
