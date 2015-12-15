@if (is_callable($canCreate) ? $canCreate() : $canCreate)
	<a class="btn btn-success pull-right" type="submit" href="{{ action($createAction) }}"><i class="fa fa-plus-square"></i>New {{ $modelName }}</a>
@endif
