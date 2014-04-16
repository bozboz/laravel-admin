@extends('admin::layouts.default')

@section('main')
@parent
	@include('admin::partials.new')
	<h1>{{ $modelName }}</h1>
	<div class="table-responsive">
		<ol class="secret-list sortable sortable-nested">
	@foreach ($instances as $i => $model)
			<li>
				<div class="nested-group">
					<div class="actions">
						<a href="{{ URL::action($controller . '@edit', array($model->id)) }}" class="btn btn-info btn-sm" type="submit">
							<i class="fa fa-pencil"></i>
							Edit
						</a>

						{{ Form::model($model, array('class' => 'inline-form', 'action' => array($controller . '@destroy', $model->id), 'method' => 'DELETE')) }}
							<button class="btn btn-danger btn-sm" type="submit"><i class="fa fa-minus-square"></i> Delete</button>
						{{ Form::close() }}
					</div>
					<div class="nested-value">
						<i class="fa fa-sort sorting-handle"></i>
					</div>
				@foreach ($columns[$i] as $name => $value)
					<div class="nested-value">{{ $value }}</div>
				@endforeach
				</div>
				<ol></ol>
			</li>
	@endforeach
	</ol>
</div>
	@include('admin::partials.new')
@stop

@section('scripts')
    @parent
<script src="/packages/bozboz/admin/js/jquery-sortable.js"></script>
<script>
$('.sortable').sortable();
</script>
@stop
