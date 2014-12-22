<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<title>@yield('page_title', 'CMS')</title>

	@section('styles')
	{{ HTML::style('packages/bozboz/admin/css/admin.min.css') }}
	@include('admin::partials.custom-styles')
	@show
</head>

<body class="@yield('body_class', 'body')">
	@yield('main')
</body>
</html>
