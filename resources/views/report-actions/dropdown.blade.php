<div{!! HTML::attributes($dropdownAttributes) !!}>
    @if (array_key_exists('split_button', $attributes))
        {!! $actions->shift()->render() !!}
        <button{!! HTML::attributes($attributes) !!} data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <span class="caret"></span>
            <span class="sr-only">Toggle Dropdown</span>
        </button>
    @else
        <button{!! HTML::attributes($attributes) !!}>
            <i class="fa {{ $icon }}"></i>
            {{ $label }}
            <span class="caret"></span>
            <span class="sr-only">Toggle Dropdown</span>
        </button>
    @endif
    <ul class="dropdown-menu" role="menu">
        @foreach($actions as $action)
            <li>
                {!! $action->render() !!}
            </li>
        @endforeach
    </ul>
</div>
