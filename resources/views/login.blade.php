@extends('admin::layouts.bare')
@section('body_class', 'login')
@section('page_title', 'Admin Login')

@section('main')
	@include('admin::partials.login-logo')
	{!! Form::open(array('url' => 'admin/login', 'class' => 'form-signin')) !!}
		<div class="login-container">
		<div class="page-header">
			<h1 class="login-heading">Login</h1>
		</div>
			@if(Session::has('error'))
				<div class="alert alert-danger">
					<p><b>Your email &amp; password combination was not recognised, or you are not authorised to login.</b></p>
					<p>Please try again or <a href="/admin/password/remind">reset your password</a></p>
				</div>
			@elseif(Session::has('message'))
				<div class="alert alert-danger">{!! Session::get('message') !!}</div>
			@endif

			<div class="input-group">
				<label for="email" class="input-group-addon"><i class="fa fa-user"></i></label>
				{!! Form::email('email', '', array('id' => 'email', 'class' => 'form-control', 'placeholder'=>'Email Address')) !!}
			</div>
			<div class="input-group">
				<label for="password" class="input-group-addon"><i class="fa fa-lock"></i></label>
				{!! Form::password('password',  array('id' => 'password', 'class' => 'form-control', 'placeholder'=>'Password')) !!}
			</div>
			{!! Form::submit('Login', array('class' => 'btn btn-lg btn-primary pull-right')) !!}
		</div>
		<div class="login-footer">
			{!! link_to('admin/password/remind', 'Reset your password') !!}
		</div>
	{!! Form::close() !!}
@stop
