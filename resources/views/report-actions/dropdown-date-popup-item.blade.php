<li>
    {!! Form::open(['url' => $url, 'method' => $method, 'class' => 'form js-datepicker-popup']) !!}
        <input type="hidden" class="js-date" name="date">
        <button{!! HTML::attributes([
            'class' => $class,
            'type' => 'submit',
            'data-warn' => $warn,
        ]) !!}>
            <i class="{{ $icon }}"></i>
            {{ $label }}
        </button>
    {!! Form::close() !!}
</li>