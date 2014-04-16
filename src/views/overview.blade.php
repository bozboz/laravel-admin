@extends('admin::layouts.default')

@section('main')
@parent
	@include('admin::partials.new')
	<h1>{{ $modelName }}</h1>
	<div class="table-responsive">
		<ol class="secret-list sortable faux-table">

	@foreach ($instances as $i => $model)
			<li class="faux-table-row">
				<div class="faux-cell cell-small">
					<i class="fa fa-sort sorting-handle"></i>
				</div>
			@foreach ($columns[$i] as $name => $value)
				<div class="faux-cell">{{ $value }}</div>
			@endforeach
				<div class="no-wrap faux-cell">
					<a href="{{ URL::action($controller . '@edit', array($model->id)) }}" class="btn btn-info btn-sm" type="submit">
						<i class="fa fa-pencil"></i>
						Edit
					</a>

					{{ Form::model($model, array('class' => 'inline-form', 'action' => array($controller . '@destroy', $model->id), 'method' => 'DELETE')) }}
						<button class="btn btn-danger btn-sm" type="submit"><i class="fa fa-minus-square"></i> Delete</button>
					{{ Form::close() }}
				</div>
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
