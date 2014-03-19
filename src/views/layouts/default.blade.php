<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<title>@yield('page_title', 'CMS')</title>
	<!-- Bootstrap core CSS -->
	{{ HTML::style('packages/bozboz/admin/css/style.css') }}
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
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
	<script src="//netdna.bootstrapcdn.com/bootstrap/3.1.0/js/bootstrap.min.js"></script>
</body>
</html>
