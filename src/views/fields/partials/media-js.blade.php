(function() {
	var viewModel = new MediaViewModel({{ $data }}, '{{ url('admin/media?page=1') }}');

	ko.applyBindings(viewModel, document.querySelector('.js-media-browser-{{ $id }}'));

	$('.media-browser').sortable({
		placeholder: '<li class="placeholder masonry-item"></li>'
	});

	$('.js-media-browser-{{ $id }} .pagination').on('click', 'a', function(e) {
		e.preventDefault();
		viewModel.mediaLibrary.query(this.href);
	});
})();