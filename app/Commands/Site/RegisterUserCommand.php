<?php namespace WowTables\Commands\Site;

use WowTables\Commands\Command;
use Illuminate\Contracts\Bus\SelfHandling;
use WowTables\Http\Models\User;
use WowTables\Events\Site\NewUserWasRegistered;

/**
 * Class RegisterUserCommand
 * @package WowTables\Commands\Site
 */
class RegisterUserCommand extends Command implements SelfHandling {

	protected $full_name,$email,$password;

	/**
	 * Create a new command instance.
	 * @param $full_name
	 * @param $email
	 * @param $password
	 */
	public function __construct($full_name,$email,$password)
	{
		$this->full_name = $full_name;
		$this->email = $email;
		$this->password = $password;
	}

	/**
	 * Execute the command.
	 *
	 * @param User $user
	 * @return array
	 */
	public function handle(User $user)
	{
		$response = $user->register($this->full_name,$this->email,$this->password);

		event(new NewUserWasRegistered($this->email,$this->full_name));

		return $response;
	}

}
