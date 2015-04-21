<?php namespace Wowtables\Core\Mailers;


class UserMailer extends Mailer {

    public function welcomeUser( $email, $name )
    {
        $view = 'emails.users.welcome';

        $data = array(
            'name' => $name,
            'email' => $email,
        );

        $subject = 'Welcome To WowTables!!!';

        return $this->sendTo( $email, $name, $subject, $view, $data);
    }
}