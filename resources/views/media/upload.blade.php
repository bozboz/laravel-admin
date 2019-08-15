@extends('admin::layouts.default')

@section('main')
<main id="app">
    <a class="btn-default pull-right space-left btn btn-sm" href="/admin/files">
        <i class="fa fa-list-alt"></i>
        Back to listing
    </a>
    <boz-uploader></boz-uploader>
    <a class="btn-default pull-right space-left btn btn-sm" href="/admin/files">
        <i class="fa fa-list-alt"></i>
        Back to listing
    </a>
</main>
@stop
@section('scripts')
    @parent
    <!--script src="{{ asset('admin/files.js') }}"></script-->
@stop
@section('styles')
@parent
    <meta name="csrf-token" content="{{ csrf_token() }}">
@stop
