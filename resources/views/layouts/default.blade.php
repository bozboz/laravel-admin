<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="csrf-token" content="{{ csrf_token() }}">

	<title>@yield('page_title', 'CMS')</title>

	@section('header_scripts')
		<script src="//code.jquery.com/jquery-1.11.0.min.js"></script>
		<script src="//code.jquery.com/jquery-migrate-1.2.1.min.js"></script>
	@show

	@section('styles')
	<link rel="stylesheet" type="text/css" href="{!! asset_version('assets/css/min/admin/style.css') !!}">
	@include('admin::partials.custom-styles')
	@show
</head>

<body>
	@section('top')
		@include('admin::partials.nav')
	@show


	<div class="main" id="app">
		@if (session()->get('error'))
			<div class="alert alert-danger" role="alert">
				<p><strong>{{session()->get('error')}}</strong></p>
			</div>
		@endif

		@section('main')
		@show
		<edit-modal></edit-modal>
	</div>
	@section('scripts')
		<script src="{{ asset_version('assets/js/min/admin/app.js') }}"></script>
		<script src="/admin/media.js"></script>
	@show
</body>
</html>
