<?php namespace WowTables\Handlers\Events\Site;

use WowTables\Events\Site\NewUserWasRegistered;
use Wowtables\Core\Mailers\UserMailer;

class NewUserWasRegisteredEventHandler {

	/**
	 * Create the event handler.
	 * @param UserMailer $mailer
	 */
	public function __construct(UserMailer $mailer)
	{
		$this->mailer = $mailer;
	}

	/**
	 * Handle the event.
	 *
	 * @param  NewUserWasRegistered $event
	 * @internal param UserMailer $mailer
	 */
	public function handle(NewUserWasRegistered $event)
	{
		$this->sendUserWelcomeMail($event);
	}

	/**
	 * @param NewUserWasRegistered $event
	 * @internal param UserMailer $mailer
	 */
	public function sendUserWelcomeMail(NewUserWasRegistered $event)
	{
		$this->mailer->welcomeUser($event->email, $event->full_name);
	}

}
