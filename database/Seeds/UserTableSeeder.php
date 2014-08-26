<?php  namespace Bozboz\Admin\Database\Seeds;

use Bozboz\Admin\Models\User;

class UserTableSeeder extends \Seeder
{
	public function run()
	{
		User::truncate();
		$usersData = [
			[
				'username' => 'admin',
				'email' => 'admin@bozboz.co.uk',
				'name' => 'Bozboz Admin'
			],
			[
				'username' => 'Bower',
				'email' => 'danielb@bozboz.co.uk',
				'name' => 'Dan Bower',
			]
		];
		foreach ($usersData as $userData) {
			$user = new User($userData);
			$user->password = 'gukbeb6s';
			$user->save();
		}
	}
}
