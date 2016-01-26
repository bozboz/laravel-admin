@extends('admin::overview')

@section('report')
	<ul class="js-mason secret-list media-view">
	@foreach ($report->getRows() as $row)
		<li class="masonry-item">
			@if ($edit->check($row))
				<a href="{{ $edit->getUrl($row->getId()) }}">
					{!! $row->getColumn('image') !!}
				</a>
			@else
				{!! $row->getColumn('image') !!}
			@endif
			<div class="icons">
				<p>{{ $row->getColumn('caption') }}</p>

				@if ($destroy->check($row))
					@include($destroy->getView(), $destroy->getViewData($row))
				@endif
			</div>
		</li>
	@endforeach
	</ul>
@stop
