@foreach($report->getActions() as $action)
{{ Debugbar::info($action->getViewData(null)) }}
	@include($action->getView(), $action->getViewData(null))
@endforeach
