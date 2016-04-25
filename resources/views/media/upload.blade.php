@extends('admin::layouts.default')

@section('main')

	<div class="form-row discrete">
		<a href="{{ $listingUrl }}" class="pull-right btn btn-default">
			<i class="fa fa-list-alt"></i>
			Back to Listing
			</a>
	</div>

	<h2>Media Upload</h2>

	<p><span class="label label-info"><strong>Please Note:</strong> Maximum file upload size is {{ str_replace('M', ' megabytes', ini_get('upload_max_filesize')) }}</span></p>

	{!! Form::open(['id' => 'fileupload', 'action' => $action, 'files' => true]) !!}
		<!-- The fileupload-buttonbar contains buttons to add/delete files and start/cancel the upload -->
		<div class="row fileupload-buttonbar">
			<div class="col-lg-7">
				<!-- The fileinput-button span is used to style the file input field as button -->
				<span class="btn btn-success fileinput-button">
					<i class="fa fa-plus"></i>
					<span>Add files...</span>
					<input type="file" name="files[]" multiple>
				</span>
				<button type="submit" class="btn btn-primary start">
					<i class="fa fa-upload"></i>
					<span>Start upload</span>
				</button>
				<button type="reset" class="btn btn-warning cancel">
					<i class="fa fa-times"></i>
					<span>Cancel upload</span>
				</button>
				<button type="button" class="btn btn-danger delete">
					<i class="fa fa-trash"></i>
					<span>Delete</span>
				</button>
				<input type="checkbox" class="toggle">
				<!-- The global file processing state -->
				<span class="fileupload-process"></span>
			</div>
			<!-- The global progress state -->
			<div class="col-lg-5 fileupload-progress fade">
				<!-- The global progress bar -->
				<div class="progress progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100">
					<div class="progress-bar progress-bar-success" style="width:0%;"></div>
				</div>
				<!-- The extended global progress state -->
				<div class="progress-extended">&nbsp;</div>
			</div>
		</div>
		<!-- The table listing the files available for upload/download -->
		<table role="presentation" class="table table-striped"><tbody class="files"></tbody></table>
	{!! Form::close() !!}
@stop

@section('scripts')
@parent
<!-- The template to display files available for upload -->
<script id="template-upload" type="text/x-tmpl">
{% for (var i=0, file; file=o.files[i]; i++) { %}
	<tr class="template-upload fade">
		<td>
			<span class="preview"></span>
		</td>
		<td>
			<p class="name">{%=file.name%}</p>
			<strong class="error text-danger"></strong>
		</td>
		<td>
			<input name="caption" placeholder="Caption" class="caption form-control">
		</td>
		<td>
			<label>Private
				<input name="is_private" type="checkbox" class="is-private form-control">
			</label>
		</td>
		<td>
			<p class="size">Processing...</p>
			<div class="progress progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0"><div class="progress-bar progress-bar-success" style="width:0%;"></div></div>
		</td>
		<td>
			{% if (!i && !o.options.autoUpload) { %}
				<button class="btn btn-primary start" disabled>
					<i class="fa fa-upload"></i>
					<span>Start</span>
				</button>
			{% } %}
			{% if (!i) { %}
				<button class="btn btn-warning cancel">
					<i class="fa fa-times"></i>
					<span>Cancel</span>
				</button>
			{% } %}
		</td>
	</tr>
{% } %}
</script>
<!-- The template to display files available for download -->
<script id="template-download" type="text/x-tmpl">
{% for (var i=0, file; file=o.files[i]; i++) { %}
	<tr class="template-download fade">
		<td>
			<span class="preview">
				{% if (file.thumbnailUrl) { %}
					<a href="{%=file.url%}" title="{%=file.name%}" data-gallery><img src="{%=file.thumbnailUrl%}"></a>
				{% } %}
			</span>
		</td>
		<td colspan="2">
			<p class="name">
				{% if (file.url) { %}
					<a href="{%=file.url%}" title="{%=file.name%}" {%=file.thumbnailUrl?'data-gallery':''%}>{%=file.name%}</a>
				{% } else { %}
					<span>{%=file.name%}</span>
				{% } %}
			</p>
			{% if (file.error) { %}
				<div><span class="label label-danger">Error</span> {%=file.error%}</div>
			{% } %}
		</td>
		<td>
			{% if(file.private) { %}
				Private
			{% } else { %}
				Public
			{% } %}
		</td>
		<td>
			<span class="size">{%=o.formatFileSize(file.size)%}</span>
		</td>
		<td>
			{% if (file.deleteUrl) { %}
				<button class="btn btn-danger delete" data-type="{%=file.deleteType%}" data-url="{%=file.deleteUrl%}"{% if (file.deleteWithCredentials) { %} data-xhr-fields='{"withCredentials":true}'{% } %}>
					<i class="fa fa-trash"></i>
					<span>Delete</span>
				</button>
				<input type="checkbox" name="delete" value="1" class="toggle">
			{% } else { %}
				<button class="btn btn-warning cancel">
					<i class="fa fa-times"></i>
					<span>Cancel</span>
				</button>
			{% } %}
		</td>
	</tr>
{% } %}
</script>
<script>
	$(function () {
		'use strict';

		$('#fileupload').fileupload({
			url: this.action
		});

		$('#fileupload').bind('fileuploadsubmit', function (e, data) {
			var caption = data.context.find('.caption');

			if (caption.val() === '') {
				caption.focus();
				data.context.find('button').prop('disabled', false);
				return false;
			}

			data.formData = {
				caption: caption.val(),
				is_private: data.context.find('.is-private').is(':checked') ? 1 : 0,
				_token: $('[name="_token"]').val()
			};
		});
	});
</script>
@stop
