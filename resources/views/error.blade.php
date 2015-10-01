@extends('admin::layouts.default')

@section('main')
	<div class="panel panel-danger">
		<div class="panel-heading">{{ $title or 'Error' }}</div>
		<div class="panel-body">
			<p>
				{{ $message }}
			</p>
		</div>
	</div>
@stop