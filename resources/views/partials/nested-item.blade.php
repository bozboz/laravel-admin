			<li class="nested__item js-nested-item" id="nested-item_{{ $row->getId() }}" data-id="{{ $row->getId() }}">
				<div class="nested-group">
					<div class="actions">
						@foreach ($row->injectInstance($report->getRowActions()) as $action)
							{!! $action->render() !!}
						@endforeach
					</div>
					<div class="nested-value">
						<i class="fa fa-sort sorting-handle js-sorting-handle"></i>
					</div>
				@foreach ($row->getColumns() as $name => $value)
					<div class="nested-value">{!! $value !!}</div>
				@endforeach
				</div>
				@if($report->isRowNested($row))
					<ol>
						@foreach($report->getChildrenFor($row) as $row)
							@include('admin::partials.nested-item')
						@endforeach
					</ol>
				@else
					<ol></ol>
				@endif
			</li>
