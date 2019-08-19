@extends('admin::layouts.default')

@section('main')
<main id="app">
    <boz-media
        :can-create="{{$canCreate ? 'true' : 'false'}}"
        :can-edit="{{$canEdit ? 'true' : 'false'}}"
        :can-delete="{{$canDelete ? 'true' : 'false'}}"
    ></boz-media>
</main>
@stop
