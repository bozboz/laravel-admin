@extends('admin::overview')

@section('report')
	@if ($report->hasRows())
		<ul class="js-mason secret-list media-view">
		@foreach ($report->getRows() as $row)
			<li class="masonry-item">
				@foreach($row->getColumns() as $column)
					{!! $column !!}
				@endforeach
				@foreach ($row->filterRowActions($report->getRowActions()) as $action)
					@include($action->getView(), $action->getViewData())
				@endforeach
			</li>
		@endforeach
		</ul>
	@else
		<p>Nothing here yet. Why not add something?</p>
	@endif
@stop
