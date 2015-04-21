<?php namespace Wowtables\Core\Mailers;

use Mail;

abstract class Mailer {

    public function sendTo( $email, $name, $subject, $view, $data = [] )
    {

        Mail::queue($view, $data, function($message) use($email, $name, $subject)
        {
            $message->to( $email, $name )->subject( $subject );
        });
    }
}