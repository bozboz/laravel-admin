{{ Form::open(array('action' => 'Bozboz\Admin\Controllers\UserAdminController@store')) }}

	<div class="form-row">
		{{ Form::label('name', 'Name') }}
		{{ Form::text('name') }}
	</div>

	<div class="form-row">
		{{ Form::label('username', 'Username') }}
		{{ Form::text('username') }}
	</div>

	<div class="form-row">
		{{ Form::label('email', 'Email') }}
		{{ Form::text('email') }}
	</div>

	<div class="form-row">
		{{ Form::label('password', 'Password') }}
		{{ Form::password('password') }}
	</div>

	<div class="form-row">
		{{ Form::label('password-repeated', 'Repeat password') }}
		{{ Form::password('password-repeated') }}
	</div>

	<div class="form-row">
		{{ Form::submit() }}
	</div>

{{ Form::close() }}
