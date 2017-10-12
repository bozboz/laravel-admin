@extends('admin::layouts.default')

@section('main')
@parent
{{ Form::open(array('method' => $method, 'action' => $action, 'role' => 'form')) }}

    <h2>Bulk Edit {{ $modelName }}</h2>

    <h3><small>Editing:</small></h3>
    <ul>
      <li>
        {!! $labels->implode('</li><li>') !!}
      </li>
    </ul>

    @include('admin::fields.field-group', ['attributes' => []])

    <div class="form-row">
        <button class="btn btn-success pull-right space-left" type="submit" name="after_save" value="exit">
            <i class="fa fa-save"></i>
            Save
        </button>
        @include('admin::partials.listing')
    </div>

{{ Form::close() }}
@stop
