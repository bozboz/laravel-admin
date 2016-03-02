@extends('admin::layouts.bare')
@section('body_class', 'login')
@section('page_title', 'Admin Password Reset')

@section('main')
    @include('admin::partials.login-logo')
    {!! Form::open(['class' => 'form-signin']) !!}
        <div class="login-container">
        <div class="page-header">
            <h1>Forgotten you password?</h1>
            <p>Enter your email address in the form below and we'll email you a link to reset it.</p>
        </div>
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
            {!! Form::submit('Submit', ['class' => 'btn btn-lg btn-primary pull-right']) !!}
        </div>
    {!! Form::close() !!}
@stop
