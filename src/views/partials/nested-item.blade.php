			<li data-id="{{$row->getId()}}">
				<div class="nested-group">
					<div class="actions">
						<a href="{{ URL::action($controller . '@edit', array($row->getModel()->id)) }}" class="btn btn-info btn-sm" type="submit">
							<i class="fa fa-pencil"></i>
							Edit
						</a>

						{{ Form::model($row->getModel(), array('class' => 'inline-form', 'action' => array($controller . '@destroy', $row->getModel()->id), 'method' => 'DELETE')) }}
							<button class="btn btn-danger btn-sm" data-warn="true" type="submit"><i class="fa fa-minus-square"></i> Delete</button>
						{{ Form::close() }}
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
