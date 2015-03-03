@extends('admin::overview')

@section('main')
	@include('admin::partials.new')
	<h1>{{ $modelName }}</h1>
	{{ $report->getHeader() }}
	<ul class="js-mason secret-list media-view">
	@foreach ($report->getRows() as $row)
		<li class="masonry-item">
			<a href="{{ URL::action($controller . '@edit', array($row->getId())) }}">
				@if ($row->getModel()->getAttribute('type') === 'image')
					{{ $row->getColumns()['image'] }}
				@else
					<img src="{{ asset('/packages/bozboz/media-library/images/document.png') }}" alt="{{ $row->getModel()->getAttribute('caption') }}">
				@endif
			</a>
			<div class="icons">
				<p>{{ $row->getColumns()['caption'] }}</p>

				{{ Form::model($row->getModel(), array('action' => array($controller . '@destroy', $row->getId()), 'method' => 'DELETE')) }}
					<button data-warn="true" class="btn btn-danger btn-xs" type="submit">
						<i class="fa fa-minus-square"></i>
						Delete
					</button>
				{{ Form::close() }}
			</div>
		</li>
	@endforeach
	</ul>
	{{ $report->getFooter() }}
	@include('admin::partials.new')
@stop
