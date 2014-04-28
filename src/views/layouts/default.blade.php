<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<title>@yield('page_title', 'CMS')</title>

	@section('styles')
	{{ HTML::style('packages/bozboz/admin/css/style.css') }}
	@show
</head>

<body>
	@section('top')
		@include('admin::partials.nav')
	@show
	<div class="container-fluid">
		<div class="row">

	<div class="col-sm-12 col-md-12 main">
		@section('main')
		@show
	</div>
			</div>
	</div>
	@section('scripts')
		<script src="/packages/bozboz/admin/js/admin.min.js"></script>
	@show
</body>
</html>
