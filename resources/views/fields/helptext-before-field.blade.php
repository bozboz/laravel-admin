<div class="form-group{{ ($errors) ? ' bs-callout bs-callout-danger' : '' }}">
    {!! $label !!}
    @if ($helpText)
        <span class="help-block"><small>
            <strong>{{ $helpText->title }}</strong>
            {!! $helpText->content !!}
        </small></span>
    @endif
    {!! $input !!}
    {!! $errors !!}
</div>
