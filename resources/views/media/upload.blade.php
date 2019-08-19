@extends('admin::layouts.default')

@section('main')
<main id="app">
    <a class="btn-default pull-right space-left btn btn-sm" href="/admin/media">
        <i class="fa fa-list-alt"></i>
        Back to listing
    </a>
    <boz-uploader {{ request()->input('folder') ? ':default-folder="'.request()->input('folder').'"' : '' }}></boz-uploader>
    <a class="btn-default pull-right space-left btn btn-sm" href="/admin/media">
        <i class="fa fa-list-alt"></i>
        Back to listing
    </a>
</main>
@stop
