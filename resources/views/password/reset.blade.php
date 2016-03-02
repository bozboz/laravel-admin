@extends('admin::layouts.bare')
@section('body_class', 'login')
@section('page_title', 'Admin Password Reset')

@section('main')
    @include('admin::partials.login-logo')
    {!! Form::open(['class' => 'form-signin']) !!}
        <div class="login-container">
        <div class="page-header">
            <h1>Password reset</h1>
        </div>
            <input type="hidden" name="return" value="/admin">
            <input type="hidden" name="token" value="{{ $token }}">
            @if(Session::has('error'))
                <div class="alert alert-danger">
                    {!! Session::get('error') !!}
                </div>
            @elseif(Session::has('status'))
                <div class="alert alert-info">{!! Session::get('status') !!}</div>
            @endif

            <div class="input-group">
                <label for="email" class="input-group-addon"><i class="fa fa-user"></i></label>
                {!! Form::email('email', '', ['id' => 'email', 'class' => 'form-control', 'placeholder'=>'Email Address']) !!}
            </div>
            <div class="input-group">
                <label for="password" class="input-group-addon"><i class="fa fa-lock"></i></label>
                {!! Form::password('password',  ['id' => 'password', 'class' => 'form-control', 'placeholder'=>'Password']) !!}
            </div>
            <div class="input-group">
                <label for="password" class="input-group-addon"><i class="fa fa-lock"></i></label>
                {!! Form::password('password_confirmation',['id' => 'password', 'class' => 'form-control', 'placeholder'=>'Password Confirmation']) !!}
            </div>
            {!! Form::submit('Reset', ['class' => 'btn btn-lg btn-primary pull-right']) !!}
        </div>
    {!! Form::close() !!}
@stop
