@foreach($actions as $action)
	@include($action->getView(), $action->getViewData())
@endforeach
