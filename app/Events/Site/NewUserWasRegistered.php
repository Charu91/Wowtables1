<?php namespace WowTables\Events\Site;

use WowTables\Events\Event;

use Illuminate\Queue\SerializesModels;

class NewUserWasRegistered extends Event {

	use SerializesModels;

	/**
	 * @var
	 */
	public $full_name;
	/**
	 * @var
	 */
	public $email;

	/**
	 * Create a new event instance.
	 * @param $email
	 * @param $full_name
	 */
	public function __construct($email,$full_name)
	{
		$this->full_name = $full_name;
		$this->email = $email;
	}

}
