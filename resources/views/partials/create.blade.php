@foreach($report->getReportActions() as $action)
	@include($action->getView(), $action->getViewData())
@endforeach
