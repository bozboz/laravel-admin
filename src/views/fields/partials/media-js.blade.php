(function() {
	var elem = document.querySelector('.js-media-browser-{{ $id }}');
	var viewModel = new MediaViewModel($(elem).data('values'), '{{ url('admin/media?page=1') }}');

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
		formData: { },
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
					filename: upload.filename
				});
			}
			viewModel.mediaLibrary.loaded(false);
			delete viewModel.mediaLibrary.pages['admin/media?page=1'];
		}
	}).prop('disabled', !$.support.fileInput)
		.parent().addClass($.support.fileInput ? undefined : 'disabled');
})();
