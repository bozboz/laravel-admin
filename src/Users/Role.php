<?php

namespace Bozboz\Admin\Users;

use Bozboz\Admin\Base\Model;
use Bozboz\Admin\Permissions\Permission;

class Role extends Model
{
	protected $fillable = ['name'];

	public function users()
	{
		return $this->hasMany(User::class);
	}

	public function permissions()
	{
		return $this->hasMany(Permission::class);
	}

	public function getPermissionOptionsAttribute()
	{
		return $this->permissions->groupBy('action')->map(function($permissions) {
			return [
				'params' => $permissions->implode('param', ','),
				'exists' => true,
			];
		});
	}

	public function grantPermission($action, $param = null)
	{
		$attributes = compact('action', 'param');

		if ($this->permissions()->where($attributes)->count() === 0) {
			$this->permissions()->create($attributes);
		}
	}

	public function grantWildcard()
	{
		$this->grantPermission(Permission::WILDCARD);
	}

	public function revokePermission($action, $param = null)
	{
		$this->permissions()->whereAction($action)->whereParam($param)->delete();
	}

	public function scopeHasPermission($builder, $action)
	{
		$builder->whereHas('permissions', function($q) use ($action) {
			$q->where(function($q) use ($action) {
				$q->where('action', $action)
				  ->orWhere('action', Permission::WILDCARD);
			});
		});
	}

	public function scopeDoesntHavePermission($builder, $action)
	{
		$builder->whereDoesntHave('permissions', function($q) use ($action) {
			$q->where(function($q) use ($action) {
				$q->where('action', $action)
				  ->orWhere('action', Permission::WILDCARD);
			});
		});
	}
}