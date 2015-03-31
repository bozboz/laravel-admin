jQuery(function($){

	$('.select2').select2();

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
		],
		onImageUpload: function(files, editor, welEditable) {
			sendFile(files[0], editor, welEditable);
		}
	});

	function sendFile(file, editor, welEditable) {
		data = new FormData();
		data.append("filename", file);
		$.ajax({
			data: data,
			type: "POST",
			url: "/admin/media",
			cache: false,
			contentType: false,
			processData: false,
			success: function(url) {
				editor.insertImage(welEditable, url);
			}
		});
	}

	var masonryContainer = $('.js-mason').masonry({ "columnWidth": 187, "itemSelector": ".masonry-item" });
	masonryContainer.imagesLoaded( function() {
	  masonryContainer.masonry();
	});

	$('#save-new-order').on('click', function() {
		var data = table.nestedSortable('toHierarchy');

		$.post('/admin/sort', {model: table.data('model'), items: data});
		$(this).closest('.alert').hide();
	});

	$('#cancel-new-order').on('click', function() {
		$('.js-save-notification').hide();
	});

	$('.btn[data-warn]').on('click', function() {
		return confirm('Are you sure you want to delete');
	});
	
	var table = $('.sortable').each(function(){
		var options = {
			handle: '.sorting-handle',
			items: 'li',
			toleranceElement: '> div',
			// maxLevels: 0 = unlimited
			maxLevels: $(this).hasClass('nested') ? 0 : 1,
			stop: function (e, ui) {
				$('.js-save-notification').show();
			}
		};
		if ($(this).hasClass('nested'))
			return $(this).nestedSortable(options);
		else
			return $(this).sortable(options);
	});

	function extractId(obj)
	{
		var newObj = {id: obj.id};

		if (obj.children) {
			newObj.children = [];
			for (var i in obj.children[0]) {
				newObj.children.push(extractId(obj.children[0][i]));
			}
		}
		return newObj;
	}

});
