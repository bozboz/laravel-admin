<div class="form-group{{ ($errors) ? ' bs-callout bs-callout-danger' : '' }}">
    @if ($helpText)
        <span class="badge field-helptext"
              data-toggle="popover"
              title="{{ $helpText->title }}"
              data-content="{{ $helpText->content }}">
            ?<span class="sr-only">{{ $helpText->title }}: {{ $helpText->content }}</span>
        </span>
    @endif
    {!! $label !!}
	{!! $input !!}
    {!! $errors !!}
</div>
