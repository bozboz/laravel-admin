<?php  namespace Bozboz\Admin\Database\Seeds;

use Bozboz\Admin\Models\User;

class UserTableSeeder extends \Seeder
{
	public function run()
	{
		User::create(array(
			'id' => 2,
			'username' => 'Bower',
			'email' => 'danielb@bozboz.co.uk',
			'password' => 'really secure password',
			'name' => 'Dan Bower',
			'created_at' => date('Y-m-d H:i:s'),
			'updated_at' => date('Y-m-d H:i:s')
		));
	}
}
