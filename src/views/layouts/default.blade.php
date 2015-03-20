<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<title>@yield('page_title', 'CMS')</title>

	@section('header_scripts')
		<script src="//code.jquery.com/jquery-1.11.0.min.js"></script>
		<script src="//code.jquery.com/jquery-migrate-1.2.1.min.js"></script>
	@show

	@section('styles')
	{{ HTML::style('/packages/bozboz/admin/css/admin.min.css') }}
	{{ HTML::style('/packages/bozboz/admin/css/media-library.css') }}
	@include('admin::partials.custom-styles')
	@show
</head>

<body>
	@section('top')
		@include('admin::partials.nav')
	@show


	<div class="main">
		@section('main')
		@show
	</div>
	@section('scripts')
		<script src="/packages/bozboz/admin/js/admin.min.js"></script>
	@show
</body>
</html>
