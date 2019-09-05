<div {{ $enableVue ? '' : 'v-pre' }} class="form-group{{ ($errors) ? ' bs-callout bs-callout-danger' : '' }}">
    {!! $label !!}
    {!! $input !!}
    @if ($helpText)
        <span class="help-block"><small>
            <strong>{{ $helpText->title }}</strong>
            {!! $helpText->content !!}
        </small></span>
    @endif
    {!! $errors !!}
</div>
