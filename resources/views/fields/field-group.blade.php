
@if (!isset($tabbed_fields) || !$tabbed_fields)
<fieldset{{ HTML::attributes($attributes) }}>
	@if (isset($legend))
		<legend>{{ $legend }}</legend>
	@endif
	@foreach ($fields as $field)
		{!! $field->render($errors) !!}
	@endforeach
</fieldset>
@else
  <!-- Nav tabs -->
  <ul class="nav nav-tabs" role="tablist">
    @php
        $first = true;
    @endphp
    @foreach ($tabbed_fields as $key => $tab)
        @php
            $tab_slug = str_slug($key);
        @endphp
        @if ($first)
            <li role="presentation" class="active"><a href="#{{ $tab_slug }}" aria-controls="{{ $tab_slug }}" role="tab" data-toggle="tab">{{ ucwords($key) }}</a></li>
            @php
                $first = false;
            @endphp
        @else
            <li role="presentation"><a href="#{{ $tab_slug }}" aria-controls="{{ $tab_slug }}" role="tab" data-toggle="tab">{{ ucwords($key) }}</a></li>
        @endif
    @endforeach
  </ul>

  <!-- Tab panes -->
  <div class="tab-content">
    @php
        $first = true;
    @endphp
    @foreach ($tabbed_fields as $key => $tab)
        @php
            $tab_slug = str_slug($key);
        @endphp
        <div role="tabpanel" class="tab-pane @if($first) active @endif" id="{{ $tab_slug }}">
            <fieldset{{ HTML::attributes($attributes) }}>
            @foreach ($tab as $field)
                {!! $field->render($errors) !!}
            @endforeach
            </fieldset>
        </div>
        @php
            $first = false;
        @endphp
    @endforeach
  </div>


@endif
