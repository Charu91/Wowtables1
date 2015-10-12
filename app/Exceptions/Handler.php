<?php namespace WowTables\Exceptions;

use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use \Illuminate\Http\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Mail;

class Handler extends ExceptionHandler {

	/**
	 * A list of the exception types that should not be reported.
	 *
	 * @var array
	 */
	protected $dontReport = [
		'Symfony\Component\HttpKernel\Exception\HttpException'
	];

	/**
	 * Report or log an exception.
	 *
	 * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
	 *
	 * @param  \Exception  $e
	 * @return void
	 */
	public function report(Exception $e)
	{
		if ($e instanceof Exception)
        {
            echo '';
        }

        //echo $e->getCode();die();
        //var_dump($e->ge());die();
        /*$error_url = $_SERVER['SERVER_NAME']."".$_SERVER['REQUEST_URI'];
        //$user_ip = $e->get;

        $browser_details =  $_SERVER['HTTP_USER_AGENT'];
        //echo $browser_details;
        $file_name = $e->getFile();
        $message = $e->getMessage();
        $line_no = $e->getLine();

        $error_array = array('error_url'=>$error_url,'browser_details'=>$browser_details,'file_name'=>$file_name,'message'=>$message,'line_no'=>$line_no);

        //print_r($error_array);die();
        Mail::send('site.pages.page_error_404',[
            'error_array'=> $error_array,
        ], function($message) use ($error_array)
        {
            $message->from('manan@gourmetitup.com', 'WowTables by GourmetItUp');
            $message->to(['x+46554753315426@mail.asana.com'])->subject('Exception- '.$error_array['message']);

        });*/
		//return parent::report($e);
	}

	/**
	 * Render an exception into an HTTP response.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Exception  $e
	 * @return \Illuminate\Http\Response
	 */
	public function render($request, Exception $e)
	{
		 if ($this->isHttpException($e))
        {   //echo "asd";
            return $this->renderHttpException($e);
        }
        else

        { //echo "sadsad";
            /*$error_url = $_SERVER['SERVER_NAME']."".$_SERVER['REQUEST_URI'];


        {
            $error_url = $_SERVER['SERVER_NAME']."".$_SERVER['REQUEST_URI'];

             //echo "sadsad";
            /*$error_url = $_SERVER['SERVER_NAME']."".$_SERVER['REQUEST_URI'];


            $user_ip = $this->getUserIP();
            $browser_details =  $_SERVER['HTTP_USER_AGENT'];
            $errorarray = array('error_url'=>$error_url,'ip_address'=>$user_ip,'browser_details'=>$browser_details);
            Mail::send('site.pages.page_error_404',[
                'error_array'=> $errorarray,
            ], function($message) use ($errorarray)
            {
                $message->from('concierge@wowtables.com', 'WowTables by GourmetItUp');

                $message->to('concierge@wowtables.com')->subject('404 error on website');
                $message->cc(['kunal@wowtables.com','tech@wowtables.com']);

            });*/
            //return parent::render($request, $e);
            return response()->view('errors.404');
        }
	}

	    /**
     * Render the given HttpException.
     *
     * @param  \Symfony\Component\HttpKernel\Exception\HttpException  $e
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function renderHttpException(HttpException $e)
    {
        if (view()->exists('errors.'.$e->getStatusCode()))
        {
            return response()->view('errors.'.$e->getStatusCode(), [], $e->getStatusCode());
        }
        else
        {
            return (new SymfonyDisplayer(config('app.debug')))->createResponse($e);
        }
    }

}
