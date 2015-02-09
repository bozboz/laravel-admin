(function() {
	var elem = document.querySelector('.js-media-browser-{{ $id }}');
	console.log($(elem).data('values'));
	var viewModel = new MediaViewModel($(elem).data('values'), '{{ url('admin/media?page=1') }}');

	ko.applyBindings(viewModel, elem);

	$('.media-browser').sortable({
		placeholder: '<li class="placeholder masonry-item"></li>'
	});

	$('.js-media-browser-{{ $id }} .pagination').on('click', 'a', function(e) {
		e.preventDefault();
		viewModel.mediaLibrary.query(this.href);
	});
})();
