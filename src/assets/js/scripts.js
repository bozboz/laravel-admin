$('textarea.html-editor').summernote({
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

$('#save-new-order').on('click', function() {
	var objects = table.sortable('serialize')[0];
	var data = [];
	for (var i in objects) {
		data.push(extractId(objects[i]));
	}

	$.post('/admin/sort', {model: table.data('model'), items: data});
	$(this).closest('.alert').hide();
});

$('#cancel-new-order').on('click', function() {
	$('.js-save-notification').hide();
});

var table = $('.sortable').sortable({
	handle: '.sorting-handle',
	onDrop: function ($item, container, _super) {
		$('.js-save-notification').show();
		_super($item, container);
	}
});

function extractId(obj)
{
	var newObj = {id: obj.id};

	if (obj.children) {
		newObj.children = [];
		for (var i in obj.children) {
			newObj.children.push(extractId(obj.children[i]));
		}
	}
	return newObj;
}
