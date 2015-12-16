			<li id="nested-item_{{ $row->getId() }}" data-id="{{ $row->getId() }}">
				<div class="nested-group">
					<div class="actions">
						@if ($row->check($canEdit))
							<a href="{{ URL::action($editAction, [$row->getId()]) }}" class="btn btn-info btn-sm" type="submit">
								<i class="fa fa-pencil"></i>
								Edit
							</a>
						@endif
						@if ($row->check($canDelete))
							{{ Form::open(['class' => 'inline-form', 'action' => array($destroyAction, $row->getId()), 'method' => 'DELETE']) }}
								<button class="btn btn-danger btn-sm" data-warn="true" type="submit"><i class="fa fa-minus-square"></i> Delete</button>
							{{ Form::close() }}
						@endif
					</div>
					<div class="nested-value">
						<i class="fa fa-sort sorting-handle"></i>
					</div>
				@foreach ($row->getColumns() as $name => $value)
					<div class="nested-value">{{ $value }}</div>
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
