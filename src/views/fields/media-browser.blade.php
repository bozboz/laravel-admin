<input type="hidden" name="{{ $name }}">

<div class="js-media-browser-{{ $id }}">

  <div data-bind="with: selectedMedia">
    <ul class="secret-list media-browser" data-bind="foreach: media">
      <li class="masonry-item masonry-item-inline-media">
        <input type="checkbox" class="media-is-used" name="{{ $name }}" checked data-bind="
          value: id, attr: { id: '{{ $id }}-' + id }
        ">
        <label data-bind="attr: { for: '{{ $id }}-' + id }">
          <img data-bind="attr: { src: type === 'pdf' ? '/packages/bozboz/media-library/images/document.png' : $parent.getFilename(filename) }" width="150">
          <p class="icons" data-bind="text: caption"></p>
        </label>
      </li>
    </ul>
  </div>

  <span class="btn btn-success fileinput-button">
        <i class="glyphicon glyphicon-plus"></i>
        <span>Select files...</span>
        <!-- The file input field used as target for the file upload widget -->
        <input id="fileupload" type="file" name="files[]" multiple>
    </span>
    <br>
    <br>
    <!-- The global progress bar -->
    <div id="progress" class="progress">
        <div class="progress-bar progress-bar-success"></div>
    </div>
    <!-- The container for the uploaded files -->
  <div id="files" class="files"></div>

  <script>
    $(function () {
        'use strict';

        $('#fileupload').fileupload({
            url: '/admin/media',
            dataType: 'json',
            formData: { },
            progressall: function (e, data) {
                var progress = parseInt(data.loaded / data.total * 100, 10);
                $('#progress .progress-bar').css(
                    'width',
                    progress + '%'
                );
            }
        }).prop('disabled', !$.support.fileInput)
            .parent().addClass($.support.fileInput ? undefined : 'disabled');
    });
    $('.js-media-browser-{{ $id }}').data('values', {{ $data }});
  </script>

  <button class="btn btn-info" data-bind="click: mediaLibrary.browse">Browse Media</button>

  @include('admin::fields.partials.media-modal')

</div>
