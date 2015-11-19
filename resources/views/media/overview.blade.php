@extends('admin::overview')

@section('report')
	<ul class="js-mason secret-list media-view">
	@foreach ($report->getRows() as $row)
		<li class="masonry-item">
			@if ($canEdit($row->getId()))
				<a href="{{ URL::action($editAction, array($row->getId())) }}">
					{!! $row->getColumn('image') !!}
				</a>
			@else
				{!! $row->getColumn('image') !!}
			@endif
			<div class="icons">
				<p>{{ $row->getColumn('caption') }}</p>

				@if ($canDelete($row->getId()))
					{!! Form::open(['action' => array($destroyAction, $row->getId()), 'method' => 'DELETE']) !!}
						<button data-warn="true" class="btn btn-danger btn-xs" type="submit">
							<i class="fa fa-minus-square"></i>
							Delete
						</button>
					{!! Form::close() !!}
				@endif
			</div>
		</li>
	@endforeach
	</ul>
@stop
