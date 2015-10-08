var ACCESS_PUBLIC = {{ $access_public }};
var ACCESS_PRIVATE = {{ $access_private }};

(function() {
	var elem = document.querySelector('.js-media-browser-{{ $id }}');
	var settings = $(elem).data('values');
	var viewModel = new MediaViewModel(settings, '{{ url('admin/media?page=1') }}');

	ko.applyBindings(viewModel, elem);

	$('.media-browser').sortable({
		placeholder: 'placeholder masonry-item'
	});

	$('.js-media-browser-{{ $id }} .pagination').on('click', 'a', function(e) {
		e.preventDefault();
		viewModel.mediaLibrary.query(this.href);
	});

	$('.js-file-upload-{{ $id }}').fileupload({
		url: '/admin/media',
		dataType: 'json',
		formData: {
			'is_private[]': ( settings.mediaAccess === ACCESS_PRIVATE ? 1 : 0 )
		},
		progressall: function (e, data) {
			var progress = parseInt(data.loaded / data.total * 100, 10);
			$(this).parent().nextAll('.js-progress').find('.progress-bar').css(
				'width',
				progress + '%'
			);
		},
		done: function(e, data) {
			for (var i in data.result.files) {
				var upload = data.result.files[i];
				viewModel.selectedMedia.media.push({
					id: upload.id,
					type: upload.type,
					caption: upload.name,
					filename: upload.filename,
					private: ( settings.mediaAccess === ACCESS_PRIVATE ? 1 : 0 ),
				});
			}
			viewModel.mediaLibrary.loaded(false);
			delete viewModel.mediaLibrary.pages['admin/media?page=1&public=1'];
			delete viewModel.mediaLibrary.pages['admin/media?page=1&private=1'];
		}
	}).prop('disabled', !$.support.fileInput)
		.parent().addClass($.support.fileInput ? undefined : 'disabled');
})();
