$('textarea').summernote({
	height: 230,
	toolbar: [
		// ['style', ['style']],
		['font', ['bold', 'italic', 'underline', 'clear']],
		['para', ['ul', 'ol', 'paragraph']],
		['table', ['table']],
		['insert', ['link', 'picture', 'video']],
		['view', ['fullscreen', 'codeview']],
		['help', ['help']]
	]
});

var masonryContainer = $('.js-mason').masonry({ "columnWidth": 187, "itemSelector": ".masonry-item" });
masonryContainer.imagesLoaded( function() {
  masonryContainer.masonry();
});
