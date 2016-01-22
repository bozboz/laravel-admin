@foreach($report->getActions() as $action)
	@include($action->getView(), $action->getViewData(null))
@endforeach
