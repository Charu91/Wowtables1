<?php namespace WowTables\Http\Models\Eloquent;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

class User extends Model implements AuthenticatableContract, CanResetPasswordContract {

	use Authenticatable, CanResetPassword;

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'users';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = ['name', 'email', 'password'];

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = ['password', 'remember_token'];

	public function role()
	{
		return $this->hasOne('WowTables\Http\Models\Eloquent\Role', 'id', 'role_id');
	}

	public function hasRole($name)
	{
		foreach($this->role as $role)
		{
			if($role->name == $name) return true;
		}
		return false;
	}

	public function attributesBoolean()
	{
		return $this->hasMany('WowTables\Http\Models\Eloquent\UserAttributesBoolean', 'user_id', 'id');
	}

	public function attributesDate()
	{
		return $this->hasMany('WowTables\Http\Models\Eloquent\UserAttributesDate', 'user_id', 'id');
	}

	public function attributesInteger()
	{
		return $this->hasMany('WowTables\Http\Models\Eloquent\UserAttributesInteger', 'user_id', 'id');
	}

	public function attributesFloat()
	{
		return $this->hasMany('WowTables\Http\Models\Eloquent\UserAttributesFloat', 'user_id', 'id');
	}

	public function attributesText()
	{
		return $this->hasMany('WowTables\Http\Models\Eloquent\UserAttributesText', 'user_id', 'id');
	}

	public function attributesVarChar()
	{
		return $this->hasMany('WowTables\Http\Models\Eloquent\UserAttributesVarChar', 'user_id', 'id');
	}

	public function attributesSingleSelect()
	{
		return $this->hasMany('WowTables\Http\Models\Eloquent\UserAttributesSingleSelect', 'user_id', 'id');
	}

	public function attributesMultiSelect()
	{
		return $this->hasMany('WowTables\Http\Models\Eloquent\UserAttributesMultiSelect', 'user_id', 'id');
	}

}
