@extends('admin::layouts.default')

@section('main')
@parent
	@include('admin::partials.new')
	<h1>{{ Str::plural($modelName) }}</h1>

	<div class="js-save-notification alert alert-info" style="display: none">
		You changed the sort order
		<button class="btn pull-right btn-sm btn-danger" id="cancel-new-order">Cancel</button>
		<button class="btn pull-right btn-sm" id="save-new-order">Save Order</button>
	</div>

	<div class="table-responsive">
		<ol class="secret-list sortable sortable-nested">
		@foreach ($report->getRows() as $row)
			@include('admin::partials.nested-item')
		@endforeach
	</ol>
</div>
	@include('admin::partials.new')
@stop

@section('scripts')
    @parent
	<script src="/packages/bozboz/admin/js/jquery-sortable.js"></script>
	<script src="/packages/bozboz/admin/js/sort.js"></script>
</script>
@stop
